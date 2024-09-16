"use strict";

import Field from "./Field.js";
import Minesweeper from "./Minesweeper.js"
import BoardView from "./BoardView.js";

const field = new Field();

// For test purposes
const bw = new BoardView();

bw.board.selectField(0, 0);
bw.board.unrevealField(0, 0);
bw.drawTable();