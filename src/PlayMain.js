"use strict";

import BoardView from "./BoardView.js";
import { saveGame, loadGame, newGame, getSaveName } from "./SaveGame.js";
import State from "./State.js";
import * as Popup from "./Popup.js";
import * as Stopwatch from "./Stopwatch.js";

let game = loadGame() || newGame();

if (!game) {
    window.location.href = "new.html";
}

const bw = new BoardView(game);

Stopwatch.start();
let timeout = setTimeout(logTime, 50);

function showTime() {
    document.querySelector("#elapsed-time").innerHTML =
        Stopwatch.getElapsedTimeString();
}

function logTime() {
    showTime();
    timeout = setTimeout(logTime, 50);
}

function stopLogTime() {
    clearTimeout(timeout);
    Stopwatch.stop();
    showTime(); // Showing final time after stop
}

bw.drawTable();
document.querySelector("body").removeAttribute("hidden");

document
    .getElementById("content")
    .addEventListener("mousedown", handleInteractionStart);

document
    .getElementById("content")
    .addEventListener("touchstart", handleInteractionStart);

function handleInteractionStart(event) {
    if (event.type === "touchstart") {
        event.preventDefault();
    }

    const isButton =
        event.target.nodeName === "INPUT" &&
        event.target.type === "button" &&
        event.target.classList.contains("field");

    if (!isButton || bw.board.hasRevealedMine || bw.board.isGameWon()) {
        return;
    }

    let holdTimer;

    let fieldPositions = bw.calculateRowColumnById(event);
    fieldPositions.id = event.target.id;

    // If the button is held down for 500ms, we change the flag on the field
    holdTimer = setTimeout(() => {
        bw.board.changeFlag(fieldPositions.row, fieldPositions.column);
        let fieldState =
            bw.board.fields[fieldPositions.row][fieldPositions.column].state;
        bw.printRemainFields();

        if (fieldState === State.FLAGGED) {
            bw.updateField(fieldPositions);
        } else if (fieldState === State.UNSELECTED) {
            event.target.value = "";
        }
        holdTimer = null;
    }, 500); // 500ms threshold for detecting a hold

    // If mouseup occurs before 500ms, unreveal field
    const handleMouseUp = () => {
        if (!holdTimer) {
            return;
        }

        clearTimeout(holdTimer);
        bw.unrevealArea(fieldPositions.row, fieldPositions.column);

        if (bw.board.isGameWon()) {
            stopLogTime();
            let content = "You won the game! ";
            Popup.showOverlay(content);
        }

        if (bw.board.hasRevealedMine) {
            stopLogTime();
            let content = "You lost the game! ";
            Popup.showOverlay(content);
        }

        cleanup();
    };

    const cleanup = () => {
        document.removeEventListener("mouseup", handleMouseUp);
        document.removeEventListener("mouseleave", handleMouseUp);
        document.removeEventListener("touchend", handleMouseUp);
        document.removeEventListener("touchcancel", handleMouseUp);
    };

    // Attach event listeners to detect when mouse is released or leaves the button area
    document.addEventListener("mouseup", handleMouseUp);
    document.addEventListener("mouseleave", handleMouseUp);
    document.addEventListener("touchend", handleMouseUp);
    document.addEventListener("touchcancel", handleMouseUp);
}

document
    .getElementById("save-game")
    .addEventListener("click", (event) => saveGame(getSaveName(), bw.board));

for (let button of document.getElementsByClassName("new-game")) {
    button.addEventListener(
        "click",
        (event) => (window.location.href = "new.html")
    );
}

document.getElementById("quit").addEventListener("click", (event) => {
    window.location.href = "index.html";
});
