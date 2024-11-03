"use strict";

import BoardView from "./BoardView.js";
import { saveGame, loadGame, newGame, getSaveName } from "./SaveGame.js";

let game = loadGame() || newGame();

if (!game) {
    window.location.href = "new.html";
}

const bw = new BoardView(game);

bw.drawTable();
document.querySelector("body").removeAttribute("hidden");

document.getElementById("content").addEventListener("mousedown", (event) => {
    const isButton =
        event.target.nodeName === "INPUT" &&
        event.target.type === "button" &&
        event.target.classList.contains("field");

    if (!isButton || bw.board.hasRevealedMine) {
        return;
    }

    let holdTimer;

    // Set a timer to detect if the button is held down for 500ms
    holdTimer = setTimeout(() => {
        holdTimer = null; // Clear the timer to mark as hold click
    }, 500); // 500ms threshold for detecting a hold

    // If mouseup occurs before 500ms, treat it as a short click
    const handleMouseUp = () => {
        if (holdTimer) {
            clearTimeout(holdTimer); // Clear the hold timer
            let row =
                (event.target.id - (event.target.id % bw.board.size)) /
                bw.board.size;
            let column = event.target.id - row * bw.board.size;
            bw.unrevealArea(row, column); // Perform the short click action
        }
        cleanup();
    };

    const cleanup = () => {
        document.removeEventListener("mouseup", handleMouseUp);
        document.removeEventListener("mouseleave", handleMouseUp);
    };

    // Attach event listeners to detect when mouse is released or leaves the button area
    document.addEventListener("mouseup", handleMouseUp);
    document.addEventListener("mouseleave", handleMouseUp);
});

document
    .getElementById("save-game")
    .addEventListener("click", (event) => saveGame(getSaveName(), bw.board));

document.getElementById("save-quit").addEventListener("click", (event) => {
    saveGame(getSaveName(), bw.board);
    window.location.href = "index.html";
});

document
    .getElementById("new-game")
    .addEventListener("click", (event) => (window.location.href = "new.html"));

document
    .getElementById("quit")
    .addEventListener(
        "click",
        (event) => (window.location.href = "index.html")
    );
