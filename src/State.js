/**
 * @module
 */

"use strict";

/**
 * State
 *
 * Represents the state of a field.
 *
 * States:
 * - UNSELECTED: The content of the field is unknown to the user, it has not been selected/revealed yet.
 * - FLAGGED: Marked with a flag, potentially indicating a mine.
 * - REVEALED: The field has been selected by the user or revealed by the algorithm because it is empty.
 */

export default Object.freeze({
    UNSELECTED: 0,
    FLAGGED: 1,
    REVEALED: 2,
});
