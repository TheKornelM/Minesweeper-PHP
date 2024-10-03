"use strict";

import Minesweeper from "./Minesweeper.js";
import Field from "./Field.js";

/**
 *
 * @param {Minesweeper} game
 */

const parseGames = () => {
    let games = JSON.parse(localStorage.getItem("savedGames"));
    if (!games) {
        games = [];
    }

    return games;
};

export function saveGame(saveName, game) {
    let games = parseGames();
    games.push({
        name: saveName,
        board: game,
    });

    localStorage.setItem("savedGames", JSON.stringify(games));
    console.log(games);
}

export function loadGame() {
    const searchParams = new URLSearchParams(window.location.search);
    let id = searchParams.get("id");

    let games = parseGames();

    if (!id) {
        return new Minesweeper(7);
    }

    if (id < games.length || id > games.length) {
        document.querySelector("body").innerHTML = "Error during loading";
    }

    console.log(games);
    console.log(games[id].board.size);
    let gameData = games[id].board;

    let game = new Minesweeper(gameData.size);
    Object.assign(game, gameData); // Copy properties from gameData to the new instance

    // Rebuild any necessary methods, if needed (e.g., fields matrix)
    game.fields = gameData.fields.map((row) =>
        row.map((fieldData) => Object.assign(new Field(), fieldData))
    );

    return game;
}
