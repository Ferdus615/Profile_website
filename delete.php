<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    die("Access Denied");
}

if (!isset($_GET['profile_id'])) {
    die("Profile ID missing");
}

$stmt = $pdo->prepare("SELECT profile_id FROM Profile WHERE profile_id = :pid AND user_id = :uid");
$stmt->execute([':pid' => $_GET['profile_id'], ':uid' => $_SESSION['user_id']]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    $_SESSION["error"] = "Profile not found or you don't have permission";
    header("Location: profile.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("DELETE FROM Profile WHERE profile_id = :pid AND user_id = :uid");
    $stmt->execute([':pid' => $_GET['profile_id'], ':uid' => $_SESSION['user_id']]);

    $_SESSION["success"] = "Profile deleted";
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete Profile</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h1 class="mb-4 text-danger">Confirm Delete</h1>

    <div class="card shadow p-4 border-danger">
        <form method="post">
            <p class="text-warning">Are you sure you want to delete this profile?</p>
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="profile.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>