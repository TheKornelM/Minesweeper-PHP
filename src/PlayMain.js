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

document.getElementById("content").addEventListener("click", (event) => {
    const isButton =
        event.target.nodeName === "INPUT" &&
        event.target.type === "button" &&
        event.target.classList.contains("field");

    if (!isButton || bw.board.hasRevealedMine) {
        return;
    }

    let row =
        (event.target.id - (event.target.id % bw.board.size)) / bw.board.size;
    let column = event.target.id - row * bw.board.size;

    bw.unrevealArea(event, row, column);
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
