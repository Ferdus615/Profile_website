<?php
require_once "config.php";

if (!isset($_GET['profile_id'])) {
    die("Profile ID missing");
}

$stmt = $pdo->prepare("SELECT first_name, last_name, email, headline, summary FROM Profile WHERE profile_id = :pid");
$stmt->execute([':pid' => $_GET['profile_id']]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    die("Profile not found");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Profile</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

    <div class="card shadow p-4">
        <h2 class="text-center">Profile Details</h2>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>First Name:</strong> <?= htmlentities($profile['first_name']) ?></li>
            <li class="list-group-item"><strong>Last Name:</strong> <?= htmlentities($profile['last_name']) ?></li>
            <li class="list-group-item"><strong>Email:</strong> <?= htmlentities($profile['email']) ?></li>
            <li class="list-group-item"><strong>Headline:</strong> <?= htmlentities($profile['headline']) ?></li>
        </ul>
        <div class="mt-3">
            <h5>Summary:</h5>
            <p class="border rounded p-3 bg-light"><?= htmlentities($profile['summary']) ?></p>
        </div>
        <a href="profile.php" class="btn btn-primary mt-3">Back</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>