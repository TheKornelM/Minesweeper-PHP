<?php
session_start();
?>
<h1>
    <?php
    if (isset($_SESSION['email'])) {
        echo $_SESSION['email'];
    } else {
        echo "No email found in session.";
    }
    ?>
</h1>