"use strict";

import Minesweeper from "./Minesweeper.js";
import State from "./State.js";

export default class BoardView {
    board;

    constructor(board) {
        this.board = board;
    }

    drawTable() {
        this.#printRemainFields();
        let p = document.getElementById("content");
        p.style.gridTemplateColumns = `repeat(${this.board.size}, 1fr)`;
        p.style.gridTemplateRows = `repeat(${this.board.size}, 1fr)`;
        for (let i = 0; i < this.board.size; i++) {
            for (let j = 0; j < this.board.size; j++) {
                let btn = document.createElement("input");
                btn.type = "button";
                btn.classList += "field";
                btn.id = i * this.board.size + j;
                btn.value = this.#getFieldText(i, j);

                let fieldState = this.board.fields[i][j].state;

                if (
                    fieldState === State.UNSELECTED ||
                    fieldState === State.FLAGGED
                ) {
                    btn.classList += " unrevealed";
                }

                p.appendChild(btn);
            }
        }

        if (this.board.hasRevealedMine) {
            this.revealMines();
        }
    }

    calculateRowColumnById(event) {
        const row =
            (event.target.id - (event.target.id % this.board.size)) /
            this.board.size;

        return {
            row: row,
            column: event.target.id - row * this.board.size,
        };
    }

    #getFieldText(row, column) {
        switch (this.board.fields[row][column].state) {
            case State.UNSELECTED:
                if (
                    this.board.hasRevealedMine &&
                    this.board.fields[row][column].hasMine
                ) {
                    return "💣";
                }

                return "";
            case State.REVEALED:
                if (this.board.fields[row][column].hasMine) {
                    return "💣";
                }

                let mines = this.board.fields[row][column].neighborMineCount;
                return mines === 0 ? "" : mines;
            case State.FLAGGED:
                return "🚩";
        }
    }

    #printRemainFields() {
        document.getElementById(
            "remain-fields"
        ).innerHTML = `Remain mines: ${this.board.getRemainFieldsByFlags()}`;
    }

    #unrevealFields(fields) {
        fields.forEach((item) => {
            this.updateField(item);
        });
    }

    updateField(fieldData) {
        let neighborMinesCount = this.#getFieldText(
            fieldData.row,
            fieldData.column
        );
        const button = document.getElementById(`${fieldData.id}`);
        button.value = neighborMinesCount === 0 ? "" : neighborMinesCount;
        this.#printRemainFields();

        let field = this.board.fields[fieldData.row][fieldData.column];

        if (field.state === State.FLAGGED) {
            return;
        }

        button.classList.remove("unrevealed");
    }

    unrevealArea(row, column) {
        this.board.selectField(row, column);
        let unrevealedFields = this.board.unrevealField(row, column);

        this.#unrevealFields(unrevealedFields);
        this.#printRemainFields();

        // Move to PlayMain
        if (this.board.hasRevealedMine) {
            this.revealMines();
        }
    }

    revealMines() {
        this.board.fields.forEach((row, i) => {
            row.forEach((field, j) => {
                var button = document.getElementById(
                    this.board.convertToId(i, j)
                );

                if (!field.hasMine && field.state === State.FLAGGED) {
                    button.classList.add("red-bg");
                    return;
                }

                if (!field.hasMine || field.state === State.FLAGGED) {
                    return;
                }

                button.value = "💣";

                if (field.state === State.REVEALED) {
                    button.classList.add("red-bg");
                } else {
                    button.classList = ["field", "revealed"];
                }
            });
        });
    }
}
