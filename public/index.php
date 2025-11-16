<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Database;

$database = new Database();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Users</title>
</head>

<body>
    <container id="container">
        <header> Header </header>

        <nav>
            <ul class="nav-list">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
            </ul>
        </nav>

        <main>
            <h1>User List</h1>
            <ul id="user-list">
                <?php
                $stmt = $database->getResults('SELECT id, name, email FROM users');
                foreach ($stmt as $row) {
                    echo '<li>' . htmlspecialchars($row['name']) . ' (' . htmlspecialchars($row['email']) . ')</li>';
                }
                ?>
            </ul>
        </main>

        <footer class="footer"> Footer </footer>
    </container>
</body>

</html>