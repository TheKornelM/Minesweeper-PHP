<?php
    session_start();

    if(!isset($_SESSION["username"])){
        header("Location: ../account/login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Minesweeper: Home</title>
        <script src="../src/HomeMain.js" type="module"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"
        ></script>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
            crossorigin="anonymous"
        />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        />
        <link rel="stylesheet" href="../style/style.css" />
        <link rel="stylesheet" href="../style/mainpage.css" />
    </head>
    <body hidden>
        <main>
            <h1>Your saved games</h1>

            <section id="saves"></section>
            <form action="new.php">
                <input
                    type="submit"
                    value="New game"
                    class="btn btn-lg btn-primary"
                />
                <input
                    type="button"
                    value="Delete saves"
                    id="delete-saves"
                    class="btn btn-danger btn-lg"
                />
            </form>
        </main>
    </body>
</html>
