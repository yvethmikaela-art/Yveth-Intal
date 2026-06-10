<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mt. Grace Protect</title>
    <script type="module" src="/react/assets/index.js"></script>
    <?php if (isset($user)): ?>
    <script>
      window.__CI4_USER__ = <?= json_encode($user) ?>;
    </script>
    <?php endif; ?>
  </head>
  <body>
    <div id="root"></div>
