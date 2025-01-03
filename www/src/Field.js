/**
 * @module
 */

"use strict";

import State from "./State.js";

/**
 * Represents a single field in the Minesweeper game.
 */
export default class Field {
    /**
     * Indicates whether the field contains a mine.
     * @type {boolean}
     */
    hasMine;

    /**
     * The number of mines in the neighboring fields.
     * @type {number}
     */
    neighborMineCount;

    /**
     * The current state of the field.
     * @type {State}
     */
    state;

    /**
     * Creates a new Field instance.
     */
    constructor() {
        this.hasMine = false;
        this.neighborMineCount = 0;
        this.state = State.UNSELECTED;
    }
}
