<?php
session_start();
require_once "config.php";

$salt = 'XyZzy12*_'; // Fixed salt value

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['email']) || !isset($_POST['pass'])) {
        $_SESSION["error"] = "Email and password are required";
        header("Location: index.php");
        exit();
    } elseif (strpos($_POST['email'], '@') === false) {
        $_SESSION["error"] = "Email must have an @ sign";
        header("Location: index.php");
        exit();
    } else {
        $check = hash('md5', $salt . $_POST['pass']);
        $stmt = $pdo->prepare("SELECT user_id, name FROM users WHERE email = :em AND password = :pw");
        $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row !== false) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION["success"] = "Logged in successfully";
            header("Location: profile.php");
            exit();
        } else {
            $_SESSION["error"] = "Incorrect password";
            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function doValidate() {
            console.log('Validating...');
            try {
                let email = document.getElementById('email').value.trim();
                let pw = document.getElementById('id_1723').value.trim();

                console.log("Validating email=" + email);
                console.log("Validating pw=" + pw);

                if (email === "" || pw === "") {
                    alert("Both fields must be filled out");
                    return false;
                }

                if (email.indexOf('@') === -1) {
                    alert("Invalid email format");
                    return false;
                }

                return true;
            } catch (e) {
                console.log("Validation error: " + e);
                return false;
            }
        }
    </script>
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow p-4">
                    <h2 class="text-center">Login</h2>

                    <?php
                    if (isset($_SESSION["error"])) {
                        echo '<div class="alert alert-danger">' . htmlentities($_SESSION["error"]) . '</div>';
                        unset($_SESSION["error"]);
                    }
                    if (isset($_SESSION["success"])) {
                        echo '<div class="alert alert-success">' . htmlentities($_SESSION["success"]) . '</div>';
                        unset($_SESSION["success"]);
                    }
                    ?>

                    <form name="indexForm" method="post" action="index.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="pass" class="form-label">Password</label>
                            <input type="password" class="form-control" id="id_1723" name="pass">
                        </div>
                        <input type="submit" class="btn btn-primary w-100" onclick="return doValidate();" value="Log In">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>