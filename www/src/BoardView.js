/**
 * @module
 */

"use strict";

import Minesweeper from "./Minesweeper.js";
import State from "./State.js";

export default class BoardView {
    /**
     * The Minesweeper board to display.
     * @type {Minesweeper}
     */
    board;

    /**
     * Creates an instance of BoardView.
     *
     * @param {Minesweeper} board - The Minesweeper board to show.
     */
    constructor(board) {
        this.board = board;
    }

    /**
     * Draws the Minesweeper board as a table.
     */
    drawTable() {
        this.printRemainFields();
        let p = document.getElementById("content");
        p.style.gridTemplateColumns = `repeat(${this.board.size}, 1fr)`;
        p.style.gridTemplateRows = `repeat(${this.board.size}, 1fr)`;
        for (let i = 0; i < this.board.size; i++) {
            for (let j = 0; j < this.board.size; j++) {
                p.appendChild(this.#createButton(i, j));
            }
        }

        if (this.board.hasRevealedMine) {
            this.revealMines();
        }
    }

    /**
     * Creates a button element for a field.
     *
     * @param {number} row - The row number of the field.
     * @param {number} column - The column number of the field.
     * @returns {HTMLInputElement} The created button element.
     * @private
     */
    #createButton(row, column) {
        let btn = document.createElement("input");
        btn.type = "button";
        btn.classList += "field";
        btn.id = row * this.board.size + column;
        btn.value = this.#getFieldText(row, column);

        let fieldState = this.board.fields[row][column].state;

        if (fieldState === State.UNSELECTED || fieldState === State.FLAGGED) {
            btn.classList += " unrevealed";
        }

        return btn;
    }

    /**
     * Calculates the row and column of a field based on its ID.
     *
     * @param {Event} event - The event triggered by the user.
     * @returns {Object} An object containing the row and column numbers.
     */
    calculateRowColumnById(event) {
        const row =
            (event.target.id - (event.target.id % this.board.size)) /
            this.board.size;

        return {
            row: row,
            column: event.target.id - row * this.board.size,
        };
    }

    /**
     * Gets the text to display on a field button.
     *
     * @param {number} row - The row number of the field.
     * @param {number} column - The column number of the field.
     * @returns {string|number} The text to display on the field button.
     * @private
     */
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

    /**
     * Prints the number of remaining fields.
     */
    printRemainFields() {
        document.getElementById("remaining-mines").innerHTML =
            this.board.getRemainingFieldsByFlags();
    }

    /**
     * Unreveals a list of fields.
     *
     * @param {Array} fields - The list of fields to unreveal.
     * @private
     */
    #unrevealFields(fields) {
        fields.forEach((item) => {
            this.updateField(item);
        });
    }

    /**
     * Updates the display of a field.
     *
     * @param {Object} fieldData - The data of the field to update.
     * @param {number} fieldData.id - The ID of the field.
     * @param {number} fieldData.row - The row number of the field.
     * @param {number} fieldData.column - The column number of the field.
     */
    updateField(fieldData) {
        let neighborMinesCount = this.#getFieldText(
            fieldData.row,
            fieldData.column
        );
        const button = document.getElementById(`${fieldData.id}`);
        button.value = neighborMinesCount === 0 ? "" : neighborMinesCount;

        let field = this.board.fields[fieldData.row][fieldData.column];

        if (field.state === State.FLAGGED) {
            return;
        }

        button.classList.remove("unrevealed");
    }

    /**
     * Unreveals an area starting from a specific field.
     *
     * @param {number} row - The row number of the starting field.
     * @param {number} column - The column number of the starting field.
     */
    unrevealArea(row, column) {
        this.board.selectField(row, column);
        let unrevealedFields = this.board.unrevealField(row, column);

        this.#unrevealFields(unrevealedFields);
        this.printRemainFields();

        if (this.board.hasRevealedMine) {
            this.revealMines();
        }
    }

    /**
     * Reveals all mines on the board.
     */
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
