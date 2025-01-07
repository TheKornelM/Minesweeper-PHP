<?php
session_start();
?>
<h1>
    <?php
    if (isset($_SESSION['username'])) {
        echo $_SESSION['username'];
    } else {
        echo "No username found in session.";
    }
    ?>
</h1>