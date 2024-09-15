"use strict";

const GameBoard = class GameBoard {
    #canvas;
    #context;

    constructor() {
        this.#canvas = document.getElementById("board");
        this.#context = this.#canvas.getContext('2d');
    }

    clear() {
        this.#context.clearRect(0, 0, this.#canvas.width, this.#canvas.height);
    }

    draw() {
        this.#context.fillStyle = "red";
        this.#context.fillRect(0, 0, 150, 75);
    }


    updateGameArea() {
    }
}

export default GameBoard;