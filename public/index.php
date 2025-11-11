<?php

// Simple example page that lists users from the Postgres database using App\Database
require __DIR__ . '/../vendor/autoload.php';

use App\Database;

$pdo = Database::getPdo();
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Users</title>
  <style>body{font-family:Arial,Helvetica,sans-serif;margin:20px}table{border-collapse:collapse;width:100%}th,td{border:1px solid #ddd;padding:8px}th{background:#f4f4f4}</style>
</head>
<body>
  <h1>Users</h1>
<?php
if ($pdo === null) {
    echo "<p><strong>Database not available.</strong> Make sure Postgres is running and credentials match.</p>";
    echo "<p>Defaults: host=db port=5432 db=appdb user=appuser</p>";
    exit;
}

try {
    $stmt = $pdo->query('SELECT id, name, email FROM users ORDER BY id');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    echo '<p>Error querying users: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

if (empty($users)) {
    echo '<p>No users found.</p>';
} else {
    echo '<table><thead><tr><th>ID</th><th>Name</th><th>Email</th></tr></thead><tbody>';
    foreach ($users as $u) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($u['id']) . '</td>';
        echo '<td>' . htmlspecialchars($u['name']) . '</td>';
        echo '<td>' . htmlspecialchars($u['email']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}
?>
</body>
</html>
