"use strict";

import BoardView from "./BoardView.js";

const bw = new BoardView();
bw.drawTable();

const wrapper = document.getElementById("content");
wrapper.addEventListener("click", (event) => {
    const isButton =
        event.target.nodeName === "INPUT" && event.target.type === "button";

    if (!isButton) {
        return;
    }

    let row =
        (event.target.id - (event.target.id % bw.board.size)) / bw.board.size;
    let column = event.target.id - row * bw.board.size;

    bw.board.selectField(row, column);
    let unrevealedFields = bw.board.unrevealField(row, column);

    event.target.value = bw.displayField(row, column);

    unrevealedFields.forEach((item) => {
        document.getElementById(`${item.id}`).value = bw.displayField(
            item.row,
            item.column
        );
    });
});
