/**
 * @module
 */

"use strict";

import State from "./State.js";
import Field from "./Field.js";

/**
 * Minesweeper class: Stores the data necessary for the game.
 *
 * Fields:
 * - size: The size of the board (size x size).
 * - mineCount: The number of mines in the game, which depends on the generated board size.
 * - remainFields: The number of remaining, unselected fields. If equal to mineCount, the game is won.
 * - flaggedFields: The number of flagged locations.
 * - hasRevealedMine: Indicates whether the player has revealed a mine (true: yes, false: no).
 * - fields: A matrix storing the fields, dynamically allocated.
 */

export const MIN_SIZE = 4;
export const MAX_SIZE = 15;

/**
 * Represents a Minesweeper board
 */
export default class Minesweeper {
    /**
     * The size of the board (size x size).
     * @type {number}
     */
    size;

    /**
     * The number of mines in the game, which depends on the generated board size.
     * @type {number}
     */
    mineCount;

    /**
     * The number of remaining, unselected fields.
     * If equal to mineCount, the game is won.
     * @type {number}
     */
    remainFields;

    /**
     * The number of flagged locations.
     * @type {number}
     */
    flaggedFields;

    /**
     * Indicates whether the player has revealed a mine.
     * True if a mine has been revealed, false otherwise.
     * @type {boolean}
     */
    hasRevealedMine;

    /**
     * A matrix storing the fields, dynamically allocated.
     * @type {Field[][]}
     */
    fields;

    /**
     * Creates a new Minesweeper game.
     *
     * @param {number} size - The size of the board.
     * @param {number} difficulty - The ratio of mines.
     */
    constructor(size, difficulty) {
        this.size = size;
        let tableSize = this.size * this.size;
        this.mineCount = 0;
        this.hasRevealedMine = false;
        this.mineCount = Math.floor(tableSize * difficulty);
        this.remainFields = tableSize;
        this.flaggedFields = 0;
        this.#allocateFields();
    }

    /**
     * Checks if the given field position exists.
     *
     * @param {number} row - The row number.
     * @param {number} column - The column number.
     * @returns {boolean} True if the field exists, false otherwise.
     */
    fieldExists(row, column) {
        return row >= 0 && column >= 0 && row < this.size && column < this.size;
    }

    /**
     * Selects a field on the board.
     *
     * @param {number} row - The row number.
     * @param {number} column - The column number.
     */
    selectField(row, column) {
        // Previously revealed fields or flagged locations cannot be selected until the flag is removed.
        if (
            this.fields[row][column].state == State.REVEALED ||
            this.fields[row][column].state == State.FLAGGED
        )
            return;

        if (this.fields[row][column].hasMine) {
            this.hasRevealedMine = true;
            this.fields[row][column].state = State.REVEALED;
        }

        // Mines are generated only after the first step to prevent the player from stepping on a mine immediately.
        if (this.remainFields == this.size * this.size) {
            this.#generateMines(row, column);
        }
    }

    /**
     * Converts the field position to a one-dimensional array index.
     *
     * @param {number} row - The row number.
     * @param {number} column - The column number.
     * @returns {number} The index in the one-dimensional array.
     */
    convertToId(row, column) {
        return row * this.size + column;
    }

    /**
     * Determines if the game is won.
     *
     * @returns {boolean} True if the game is won, false otherwise.
     */
    isGameWon() {
        return this.remainFields === this.mineCount;
    }

    /**
     * Reveals a field and its neighbors if applicable.
     *
     * @param {number} row - The row number.
     * @param {number} column - The column number.
     * @param {Array} revealedFields - JSON array of fields data (ID, row, column).
     * @returns {Array} The list of revealed fields as objects with row and column.
     */
    unrevealField(row, column, revealedFields = []) {
        // If the field is invalid or already revealed, the recursion ends.
        if (
            !this.fieldExists(row, column) ||
            this.fields[row][column].state == State.REVEALED ||
            this.fields[row][column].hasMine
        ) {
            return revealedFields;
        }

        let field = this.fields[row][column];

        if (field.state == State.FLAGGED) {
            this.flaggedFields--;

            // Return from recursion if the first field is flagged
            if (revealedFields.length === 0) {
                return revealedFields;
            }
        }

        this.fields[row][column].state = State.REVEALED;
        this.remainFields--;

        // Store row and column as an object
        const fieldId = this.convertToId(row, column);
        revealedFields.push({ id: fieldId, row: row, column: column });

        // If the field has neighboring mines, reveal it and end the recursion.
        if (this.fields[row][column].neighborMineCount != 0) {
            return revealedFields;
        }

        // Reveal neighboring fields.
        for (let i = -1; i <= 1; i++) {
            for (let j = -1; j <= 1; j++) {
                this.unrevealField(row + i, column + j, revealedFields);
            }
        }

        // Return the list of revealed fields as objects with row and column
        return revealedFields;
    }

    /**
     * Marks an unselected field with a flag or removes the flag if the field is already marked.
     *
     * @param {number} row - The row number.
     * @param {number} column - The column number.
     */
    changeFlag(row, column) {
        if (this.fields[row][column].state == State.FLAGGED) {
            this.fields[row][column].state = State.UNSELECTED;
            this.flaggedFields--;
        } else if (this.fields[row][column].state == State.UNSELECTED) {
            this.fields[row][column].state = State.FLAGGED;
            this.flaggedFields++;
        }
    }

    /**
     * Gets the number of remaining fields calculated by flags.
     *
     * @returns {number} The number of remaining fields.
     */
    getRemainingFieldsByFlags() {
        return this.mineCount - this.flaggedFields;
    }

    // Private methods:

    /**
     * Allocates the fields 2D array.
     *
     * @private
     */
    #allocateFields() {
        this.fields = new Array(this.size);

        for (let i = 0; i < this.size; i++) {
            this.fields[i] = new Array(this.size);

            for (let j = 0; j < this.size; j++) {
                this.fields[i][j] = new Field(this.size);
            }
        }
    }

    /**
     * Algorithm responsible for generating mines.
     *
     * @param {number} firstStepRow - The row number of the first selected tile.
     * @param {number} firstStepColumn - The column number of the first selected tile.
     * These parameters are necessary to ensure no mine is placed at the first selected position.
     *
     * @private
     */
    #generateMines(firstStepRow, firstStepColumn) {
        for (let i = 0; i < this.mineCount; ) {
            let row = Math.floor(Math.random() * this.size);
            let column = Math.floor(Math.random() * this.size);

            if (
                this.fields[row][column].hasMine ||
                (row == firstStepRow && column == firstStepColumn)
            ) {
                continue;
            }

            this.fields[row][column].hasMine = true;
            i++;
            this.#addNeighborMines(row, column);
        }
    }

    /**
     * Increments the neighbor mine count for all valid adjacent fields.
     *
     * @param {number} row - The row number of the current tile.
     * @param {number} column - The column number of the current tile.
     *
     * @private
     */
    #addNeighborMines(row, column) {
        for (let j = -1; j <= 1; j++) {
            for (let k = -1; k <= 1; k++) {
                const isValidField =
                    this.fieldExists(row + j, column + k) &&
                    !(j == 0 && k == 0);
                if (isValidField) {
                    this.fields[row + j][column + k].neighborMineCount++;
                }
            }
        }
    }
}
