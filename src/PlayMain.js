"use strict";

import BoardView from "./BoardView.js";
import { saveGame, loadGame } from "./SaveGame.js";

const game = loadGame();
const bw = new BoardView(game);
console.log(bw);
bw.drawTable();

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

document.getElementById("save-game").addEventListener("click", (event) => {
    const date = new Date()
        .toLocaleDateString("hu-HU", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
        })
        .split("/")
        .reverse()
        .join("-");

    const time = new Date().toLocaleTimeString("hu-HU", {
        hour: "2-digit",
        minute: "2-digit",
    });

    saveGame(date + " " + time, bw.board);
});
