"use strict";

import BoardView from "./BoardView.js";
import { saveGame, loadGame, getSaveName } from "./SaveGame.js";

const game = loadGame();
const bw = new BoardView(game);

if (!game) {
    document.querySelector("#content").innerHTML = "Error during loading";
} else {
    console.log(game);
    bw.drawTable();
}

document.getElementById("content").addEventListener("click", (event) => {
    const isButton =
        event.target.nodeName === "INPUT" &&
        event.target.type === "button" &&
        event.target.classList.contains("field");

    if (!isButton) {
        return;
    }

    let row =
        (event.target.id - (event.target.id % bw.board.size)) / bw.board.size;
    let column = event.target.id - row * bw.board.size;

    bw.unrevealArea(event, row, column);
});

document
    .getElementById("save-game")
    .addEventListener("click", saveGame(getSaveName(), bw.board));

document.getElementById("save-quit").addEventListener("click", () => {
    saveGame(getSaveName(), bw.board);
    window.location.href = "index.html";
});
