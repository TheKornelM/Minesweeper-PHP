"use strict";

import { default as Minesweeper, MIN_SIZE } from "./Minesweeper.js";
import Field from "./Field.js";
import Difficulty from "./Difficulty.js";

const parseGames = () => {
    let games = JSON.parse(localStorage.getItem("savedGames"));

    return !games ? [] : games;
};

const searchParams = new URLSearchParams(window.location.search);
let games = parseGames();

/**
 *
 * @param {Minesweeper} game
 */

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
    games.push({
        name: saveName,
        board: game,
    });

    refreshCachedGames(games);
}

export function loadGame() {
    let id = searchParams.get("id");
    if (!id || isNaN(id) || id < 1 || id > games.length) {
        return null;
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

export function newGame() {
    const size = searchParams.get("size");

    const isCorrectQuery =
        size &&
        !isNaN(size) &&
        size >= MIN_SIZE &&
        searchParams.get("difficulty");

    const result =
        {
            1: Difficulty.EASY,
            2: Difficulty.MEDIUM,
            3: Difficulty.HARD,
        }[searchParams.get("difficulty")] ?? null;

    if (!(isCorrectQuery && result)) {
        return null;
    }

    return new Minesweeper(searchParams.get("size"), result);
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
