<?php
    session_start();

    if(!isset($_SESSION["username"])){
        header("Location: ../account/login.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Minesweeper by TheKornel</title>
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
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        />
        <script src="../src/PlayMain.js" type="module"></script>
        <link rel="stylesheet" href="../style/style.css" />
        <link rel="stylesheet" href="../style/game.css" />
        <link rel="stylesheet" href="../style/popup.css" />
    </head>

    <body hidden>
        <main>
            <section id="popup" class="col-md-6 col-lg-4">
                <h2>Info</h2>
                <div id="popup-content">
                    <div id="popup-message"></div>
                    <input
                        type="button"
                        class="new-game btn btn-primary"
                        value="New Game"
                    />
                </div>
            </section>
            <section id="game">
                <section id="navigation">
                    <div class="row">
                        <div class="col-lg-2 col-md-12 mb-1">
                            <button
                                id="quit"
                                class="btn btn-primary btn-lg w-100"
                            >
                                <i class="fa fa-sign-out-alt"></i> Quit
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-12 mb-1">
                            <button
                                type="button"
                                id="save-game"
                                class="btn btn-primary btn-lg w-100"
                            >
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-12 mb-1">
                            <button
                                type="button"
                                id="new-game"
                                class="btn btn-primary btn-lg new-game w-100"
                            >
                                <i class="fa fa-square-plus"></i> New game
                            </button>
                        </div>
                    </div>
                </section>
                <section id="board">
                    <h1>Remaining mines: <span id="remaining-mines"></span></h1>
                    <h2 id="elapsed-time"></h2>
                    <div id="container">
                        <div id="content"></div>
                    </div>
                </section>
            </section>
        </main>
    </body>
</html>
