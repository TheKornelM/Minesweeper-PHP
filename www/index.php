<?php
    if(!isset($_SESSION["username"])){
        header("Location: account/login.php");
    }else{
        header("Location: game/new.php");
    }
?>