<?php
session_start();
require_once "config.php";


$stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM Profile");
$profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Database</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h1 class="mb-4">Profiles</h1>

    <?php

    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }

    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }

    $stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM Profile");
    $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Headline</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($profiles as $profile) { ?>
                <tr>
                    <td><a href="view.php?profile_id=<?= $profile['profile_id'] ?>">
                            <?= htmlentities($profile['first_name'] . " " . $profile['last_name']) ?></a></td>
                    <td><?= htmlentities($profile['headline']) ?></td>
                    <td>
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <a href="edit.php?profile_id=<?= $profile['profile_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete.php?profile_id=<?= $profile['profile_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php if (!isset($_SESSION['user_id'])) { ?>
        <p><a href="login.php" class="btn btn-primary">Please log in</a></p>
    <?php } else { ?>
        <p>
            <a href="add.php" class="btn btn-success">Add New Profile</a>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </p>
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>