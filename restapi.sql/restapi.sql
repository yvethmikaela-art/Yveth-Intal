CREATE TABLE `oauth_access_tokens` (
  `access_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT current_timestamp(),
  `scope` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `oauth_clients` (
  `client_id` varchar(80) NOT NULL,
  `client_secret` varchar(80) DEFAULT NULL,
  `redirect_uri` varchar(2000) DEFAULT NULL,
  `grant_types` varchar(80) DEFAULT NULL,
  `scope` varchar(4000) DEFAULT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `oauth_clients` VALUES ('testclient','testsecret',NULL,'password','app',NULL);

CREATE TABLE `oauth_refresh_tokens` (
  `refresh_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(80) DEFAULT NULL,
  `expires` timestamp NOT NULL DEFAULT current_timestamp(),
  `scope` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`refresh_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `scope` varchar(20) NOT NULL DEFAULT 'app',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;