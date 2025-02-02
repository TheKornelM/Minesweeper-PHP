﻿<?php
include '../../db_connection.php';
include "../../src/Managers/UserManager.php";
include "../../src/Repository/Interfaces/UserRepositoryInterface.php";
include "../../src/Repository/PostgreRepositories/UserRepository.php";
include "../../src/Validators/Result.php";
include "../../src/Validators/UserDataValidator.php";

use Repository\PostgreRepositories\UserRepository;
use Managers\UserManager;
use Validators\UserDataValidator;

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $validator = new UserDataValidator();
    $validationResult = $validator->validate($username, $email, $password);

    try {

        $repository = new UserRepository($conn);
        $manager = new UserManager($repository);

        if (!$validationResult->isSuccess) {
            $message = implode("<br>", $validationResult->errors);
            $toastClass = "#007bff";
        }
        else if ($manager->EmailExists($email)) {
            $message = "Email already exists";
            $toastClass = "#007bff"; // Primary color
        } else if($manager->UsernameExists($username)) {
            $message = "Username already exists";
            $toastClass = "#007bff";
        } else {
            if ($manager->registerUser($username, $email, $password)) {
                $message = "Account created successfully";
                $toastClass = "#28a745"; // Success color
            } else {
                $message = "Error during registration!";
                $toastClass = "#dc3545"; // Danger color
            }
        }
    } catch (PDOException $e) {
        $message = "Connection failed: " . $e->getMessage();
        $toastClass = "#dc3545"; // Danger color
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
    <link rel="stylesheet" href="../style/mainpage.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Registration</title>
</head>

<body>
<div class="container p-5 d-flex flex-column align-items-center">
    <?php if ($message): ?>
        <div class="toast align-items-center text-white border-0"
             role="alert" aria-live="assertive" aria-atomic="true"
             style="background-color: <?php echo $toastClass; ?>;">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo $message; ?>
                </div>
                <button type="button" class="btn-close
                    btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"
                        aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
    <form method="post" class="form-control mt-5 p-4"
          style="height:auto; width:380px;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
            rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;">
        <div class="row text-center">
            <i class="fa fa-user-circle-o fa-3x mt-1 mb-2"></i>
            <h5 class="p-4" style="font-weight: 700;">Create Your Account</h5>
        </div>
        <div class="mb-2">
            <label for="username"><i
                    class="fa fa-user"></i> User Name</label>
            <input type="text" name="username" id="username"
                   class="form-control" required>
        </div>
        <div class="mb-2 mt-2">
            <label for="email"><i
                    class="fa fa-envelope"></i> Email</label>
            <input type="text" name="email" id="email"
                   class="form-control" required>
        </div>
        <div class="mb-2 mt-2">
            <label for="password"><i
                    class="fa fa-lock"></i> Password</label>
            <input type="password" name="password" id="password"
                   class="form-control" required>
        </div>
        <div class="mb-2 mt-3">
            <button type="submit"
                    class="btn btn-success
                bg-success" style="font-weight: 600;">Create
                Account</button>
        </div>
        <div class="mb-2 mt-4">
            <p class="text-center" style="font-weight: 600;
                color: navy;"><a href="login.php"
                                                   style="text-decoration: none;">Login to exist account</a></p>
        </div>
    </form>
</div>
<script>
    let toastElList = [].slice.call(document.querySelectorAll('.toast'))
    let toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl, { delay: 3000 });
    });
    toastList.forEach(toast => toast.show());
</script>
</body>

</html>

