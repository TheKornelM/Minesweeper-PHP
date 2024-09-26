"use strict";

import Field from "./Field.js";
import Minesweeper from "./Minesweeper.js";
import BoardView from "./BoardView.js";

const field = new Field();

// For test purposes
const bw = new BoardView();

bw.board.selectField(0, 0);
bw.board.unrevealField(0, 0);
bw.drawTable();

const wrapper = document.getElementById("content");

wrapper.addEventListener("click", (event) => {
    const isButton = event.target.nodeName === "BUTTON";
    if (!isButton) {
        return;
    }

    let row =
        (event.target.id - (event.target.id % bw.board.size)) / bw.board.size;
    let column =
        event.target.id -
        (event.target.id % (bw.board.size - 1)) * bw.board.size;
    alert(`Row: ${row}, column: ${column}`);

    //alert(event.target.id);
});
