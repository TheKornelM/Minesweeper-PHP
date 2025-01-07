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
        <script type="module" src="../src/NewGame.js"></script>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
            crossorigin="anonymous"
        />
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"
        ></script>
        <link rel="stylesheet" href="../style/style.css" />
        <link rel="stylesheet" href="../style/mainpage.css" />
        <title>New Game</title>
    </head>
    <body>
        <main class="d-flex justify-content-center align-items-center vh-100">
            <form id="new-game" class="text-center">
                <section class="row justify-content-center mb-3">
                    <div class="col-8 col-md-6 w-100">
                        <label for="size" class="form-label">Size: </label>
                        <input
                            type="range"
                            class="form-range"
                            min="4"
                            max="15"
                            step="1"
                            id="size"
                            name="size"
                        />
                    </div>
                </section>
                <section class="row justify-content-center mb-5">
                    <p id="current-size"></p>
                </section>

                <section class="row justify-content-center mb-5">
                    <div class="col-8 col-md-6 w-100">
                        <label for="difficulty" class="form-label"
                            >Difficulty:</label
                        >
                        <select
                            id="difficulty"
                            name="difficulty"
                            class="form-select"
                        >
                            <option value="1">Easy</option>
                            <option value="2">Medium</option>
                            <option value="3">Hard</option>
                        </select>
                    </div>
                </section>

                <!-- Updated Section for Button -->
                <section class="row">
                    <div class="col-8 col-md-6 h-100 w-100">
                        <input
                            type="submit"
                            class="btn btn-primary btn-lg"
                            value="Let's play!"
                        />
                    </div>
                </section>
            </form>
        </main>
    </body>
</html>
