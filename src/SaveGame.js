"use strict";

import Minesweeper from "./Minesweeper.js";
import Field from "./Field.js";

/**
 *
 * @param {Minesweeper} game
 */

const parseGames = () => {
    let games = JSON.parse(localStorage.getItem("savedGames"));

    return !games ? [] : games;
};

const refreshCachedGames = (games) =>
    localStorage.setItem("savedGames", JSON.stringify(games));

const date = () =>
    new Date()
        .toLocaleDateString("hu-HU", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
        })
        .split("/")
        .reverse()
        .join("-");

const time = () =>
    new Date().toLocaleTimeString("hu-HU", {
        hour: "2-digit",
        minute: "2-digit",
    });

export const deleteSaves = () => localStorage.removeItem("savedGames");
export const getSaveName = () => `${date()} ${time()}`;

export function saveGame(saveName, game) {
    let games = parseGames();
    games.push({
        name: saveName,
        board: game,
    });

    refreshCachedGames(games);
}

export function loadGame() {
    // Should split this function
    const searchParams = new URLSearchParams(window.location.search);
    let id = searchParams.get("id");

    let games = parseGames();

    if (!id) {
        return new Minesweeper(7);
    }

    if (isNaN(id) || id < 1 || id > games.length) {
        window.location.href = "play.html";
    }

    id--;

    let gameData = games[id].board;

    let game = new Minesweeper(gameData.size);
    Object.assign(game, gameData);

    game.fields = gameData.fields.map((row) =>
        row.map((fieldData) => Object.assign(new Field(), fieldData))
    );

    return game;
}

export function deleteSave(id) {
    let games = parseGames();
    games.splice(id, 1);
    console.log(games);

    if (games.length === 0) {
        deleteSaves();
    } else {
        refreshCachedGames(games);
    }
}
