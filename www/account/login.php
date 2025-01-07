<?php
include '../../db_connection.php';
include "../../src/Managers/UserManager.php";
include "../../src/Repository/Interfaces/UserRepositoryInterface.php";
include "../../src/Repository/PostgreRepositories/UserRepository.php";

use Repository\PostgreRepositories\UserRepository;
use Managers\UserManager;

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $repository = new UserRepository($conn);
    $manager = new UserManager($repository);

    try {
        if ($manager->isValidCredentials($username, $password)) {
            $message = "Login successful";
            $toastClass = "bg-success";
            // Start the session and redirect to the dashboard or home page
            session_start();
            $_SESSION['username'] = $username;
            header("Location: ../game/index.php");
            exit();
        } else {
            $message = "Incorrect credentials!";
            $toastClass = "bg-danger";
        }
    } catch (PDOException $e) {
        $message = "Connection failed: " . $e->getMessage();
        $toastClass = "bg-danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../style/mainpage.css" />
    <title>Login Page</title>
</head>

<body>
<div class="container p-5 d-flex flex-column align-items-center">
    <?php if ($message): ?>
        <div class="toast align-items-center text-white <?php echo $toastClass; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo $message; ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
    <form action="" method="post" class="form-control mt-5 p-4" style="height:auto; width:380px; box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;">
        <div class="row">
            <i class="fa fa-user-circle-o fa-3x mt-1 mb-2" style="text-align: center"></i>
            <h5 class="text-center p-4" style="font-weight: 700;">Login Into Your Account</h5>
        </div>
        <div class="col-mb-3">
            <label for="username"><i class="fa fa-user"></i> Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="col mb-3 mt-3">
            <label for="password"><i class="fa fa-lock"></i> Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="col mb-3 mt-3">
            <button type="submit" class="btn btn-success bg-success" style="font-weight: 600;">Login</button>
        </div>
        <div class="col mb-2 mt-4">
            <p class="text-center" style="font-weight: 600; color: navy;"><a href="register.php" style="text-decoration: none;">Create Account</a></p>
        </div>
    </form>
</div>
<script>
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl, { delay: 3000 });
    });
    toastList.forEach(toast => toast.show());
</script>
</body>

</html>


