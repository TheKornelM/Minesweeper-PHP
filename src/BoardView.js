import Minesweeper from "./Minesweeper.js";
import State from "./State.js";

export default class BoardView {
    board;
    constructor() {
        this.board = new Minesweeper(7);
    }

    // For debug purpouses
    drawTableString() {
        document.getElementById(
            "remain-fields"
        ).innerHTML = `Remain fields: ${this.board.remainFields}`;
        document.getElementById("content").innerHTML = "";
        let str = "";
        for (let i = 0; i < this.board.size; i++) {
            for (let j = 0; j < this.board.size; j++) {
                str += this.displayField(i, j) + "\t";
            }
            str += "<br>";
        }
        document.getElementById("content").innerHTML = str;
    }

    drawTable() {
        this.#printRemainFields();
        let p = document.getElementById("content");
        for (let i = 0; i < this.board.size; i++) {
            for (let j = 0; j < this.board.size; j++) {
                let btn = document.createElement("input");
                btn.type = "button";
                btn.classList += "field unrevealed";
                btn.id = i * this.board.size + j;
                p.appendChild(btn);
            }
            p.append(document.createElement("br"));
        }
    }

    printInfo() {
        console.log(`Remain fields: ${this.board.remainFields}\n
            mineCount: ${this.board.mineCount}\n`);
    }

    #displayField(row, column) {
        switch (this.board.fields[row][column].state) {
            case State.UNSELECTED:
                if (
                    this.board.hasRevealedMine &&
                    this.board.fields[row][column].hasMine
                ) {
                    return "M";
                } else {
                    return this.board.fields[row][column].neighborMineCount;
                }
            case State.REVEALED:
                if (this.board.fields[row][column].hasMine) {
                    // A felhasználó által felfedett aknát piros alapon jelenítjük meg.
                    return "M";
                } else {
                    return this.board.fields[row][column].neighborMineCount;
                }
            case State.FLAGGED:
                return "F";
        }
    }

    #printRemainFields() {
        document.getElementById(
            "remain-fields"
        ).innerHTML = `Remain mines: ${this.board.mineCount}`;
    }

    unrevealArea(event, row, column) {
        this.board.selectField(row, column);
        let unrevealedFields = this.board.unrevealField(row, column);

        let neighborMinesCount = this.#displayField(row, column);
        event.target.value = neighborMinesCount == 0 ? "" : neighborMinesCount;

        unrevealedFields.forEach((item) => {
            neighborMinesCount = this.#displayField(item.row, item.column);
            const button = document.getElementById(`${item.id}`);
            button.value = neighborMinesCount == 0 ? "" : neighborMinesCount;
            button.classList.remove("unrevealed");
        });

        this.#printRemainFields();
    }
}
