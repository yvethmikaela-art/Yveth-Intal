<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\MyOAuthStorage;
use App\Models\UserModel;
use OAuth2\Server;
use OAuth2\GrantType\UserCredentials;
use OAuth2\Request as OAuth2Request;
use OAuth2\Response as OAuth2Response;

class User extends ResourceController
{
    protected $format = 'json';
    protected $server;
    protected $storage;

    public function __construct()
    {
        $db  = \Config\Database::connect();
        $dsn = 'mysql:dbname=' . $db->getDatabase() . ';host=' . $db->hostname;
        $pdo = new \PDO($dsn, $db->username, $db->password);

        $this->storage = new MyOAuthStorage($pdo);

        $this->server = new Server($this->storage);
        $this->server->addGrantType(new UserCredentials($this->storage));
    }

    // ---------------------------------------------------------------
    // Helper: verify user credentials
    // ---------------------------------------------------------------
    private function verifyUser(string $email, string $password): bool
    {
        return $this->storage->checkUserCredentials($email, $password);
    }

    // ---------------------------------------------------------------
    // Helper: verify Bearer token from Authorization header
    // ---------------------------------------------------------------
    private function verifyToken(): bool
    {
        $request  = OAuth2Request::createFromGlobals();
        $response = new OAuth2Response();

        return $this->server->verifyResourceRequest($request, $response);
    }

    // ---------------------------------------------------------------
    // GET /user        → fetch all users
    // GET /user/{id}   → fetch specific user by ID
    // Requires: Authorization: Bearer <access_token>
    // ---------------------------------------------------------------
    public function index()
    {
        // --- Step 1: Check if token is valid ---
        if (!$this->verifyToken()) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Unauthorized. Please login first to get a token.',
            ], 401);
        }

        // --- Step 2: Fetch all users via stored procedure ---
        $userModel = new UserModel();
        $users     = $userModel->getAllUsers();

        return $this->respond([
            'status' => 'ok',
            'data'   => $users,
        ], 200);
    }

    public function show($id = null)
    {
        // --- Step 1: Check if token is valid ---
        if (!$this->verifyToken()) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Unauthorized. Please login first to get a token.',
            ], 401);
        }

        // --- Step 2: Fetch specific user via stored procedure ---
        $userModel = new UserModel();
        $user      = $userModel->getUserById($id);

        if (!$user) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'User not found!',
            ], 404);
        }

        return $this->respond([
            'status' => 'ok',
            'data'   => $user,
        ], 200);
    }

    // ---------------------------------------------------------------
    // POST /user/registration
    // ---------------------------------------------------------------
    public function registration()
    {
        $json = $this->request->getJSON(true);

        $rules = [
            'first_name' => 'required|min_length[2]',
            'last_name'  => 'required|min_length[2]',
            'email'      => 'required|valid_email',
            'password'   => 'required|min_length[6]',
        ];

        if (!$this->validateData($json ?? [], $rules)) {
            return $this->respond([
                'status'  => 'error',
                'message' => $this->validator->getErrors(),
            ], 422);
        }

        $db = \Config\Database::connect();

        $existing = $db->table('users')
                       ->where('email', $json['email'])
                       ->get()
                       ->getRowArray();

        if ($existing) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Email already exists!',
            ], 409);
        }

        $db->table('users')->insert([
            'firstname' => $json['first_name'],
            'lastname'  => $json['last_name'],
            'email'     => $json['email'],
            'password'  => password_hash($json['password'], PASSWORD_BCRYPT),
            'scope'     => 'app',
        ]);

        return $this->respond([
            'status'  => 'ok',
            'id'      => $db->insertID(),
            'message' => 'User successfully registered!',
        ], 200);
    }

    // ---------------------------------------------------------------
    // POST /user/login
    // ---------------------------------------------------------------
    public function login()
    {
        $json = $this->request->getJSON(true);

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validateData($json ?? [], $rules)) {
            return $this->respond([
                'status'  => 'error',
                'message' => $this->validator->getErrors(),
            ], 422);
        }

        // --- Step 1: Verify user credentials ---
        $verified = $this->verifyUser($json['email'], $json['password']);

        if (!$verified) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Invalid email or password!',
            ], 401);
        }

        // --- Step 2: Generate OAuth access token ---
        $request = new OAuth2Request(
            [],
            [
                'grant_type'    => 'password',
                'username'      => $json['email'],
                'password'      => $json['password'],
                'client_id'     => 'testclient',
                'client_secret' => 'testsecret',
            ],
            [],
            [],
            [],
            ['REQUEST_METHOD' => 'POST']
        );

        $response = new OAuth2Response();
        $this->server->handleTokenRequest($request, $response);

        $body = json_decode($response->getResponseBody(), true);

        if (!isset($body['access_token'])) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Could not generate token!',
                'debug'   => $body,
            ], 500);
        }

        return $this->respond([
            'status'       => 'ok',
            'message'      => 'Login successful!',
            'access_token' => $body['access_token'],
            'expires_in'   => $body['expires_in'],
            'token_type'   => $body['token_type'],
        ], 200);
    }
}