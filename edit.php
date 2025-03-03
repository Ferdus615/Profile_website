<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id'])) {
    die("Access Denied");
}

if (!isset($_GET['profile_id'])) {
    die("Profile ID missing");
}

$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :pid AND user_id = :uid");
$stmt->execute([':pid' => $_GET['profile_id'], ':uid' => $_SESSION['user_id']]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    $_SESSION["error"] = "Profile not found or you don't have permission";
    header("Location: profile.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['headline']) || empty($_POST['summary'])) {
        $_SESSION["error"] = "All fields are required";
        header("Location: edit.php?profile_id=" . $_GET['profile_id']);
        exit();
    }

    if (strpos($_POST['email'], '@') === false) {
        $_SESSION["error"] = "Email must contain an '@' sign";
        header("Location: edit.php?profile_id=" . $_GET['profile_id']);
        exit();
    }

    $stmt = $pdo->prepare("UPDATE Profile SET first_name = :fn, last_name = :ln, email = :em, headline = :hl, summary = :su 
                           WHERE profile_id = :pid AND user_id = :uid");
    $stmt->execute([
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':hl' => $_POST['headline'],
        ':su' => $_POST['summary'],
        ':pid' => $_GET['profile_id'],
        ':uid' => $_SESSION['user_id']
    ]);

    $_SESSION["success"] = "Profile updated";
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h1 class="mb-4">Edit Profile</h1>

    <?php
    if (isset($_SESSION["error"])) {
        echo '<div class="alert alert-danger">' . $_SESSION["error"] . '</div>';
        unset($_SESSION["error"]);
    }
    ?>

    <div class="card shadow p-4">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">First Name:</label>
                <input type="text" name="first_name" value="<?= htmlentities($profile['first_name']) ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name:</label>
                <input type="text" name="last_name" value="<?= htmlentities($profile['last_name']) ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="text" name="email" value="<?= htmlentities($profile['email']) ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Headline:</label>
                <input type="text" name="headline" value="<?= htmlentities($profile['headline']) ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Summary:</label>
                <textarea name="summary" rows="4" class="form-control"><?= htmlentities($profile['summary']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="profile.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>