"use strict";

import { default as Minesweeper, MIN_SIZE, MAX_SIZE } from "./Minesweeper.js";
import Field from "./Field.js";
import Difficulty from "./Difficulty.js";

/**
 * Parses saved games from localStorage.
 *
 * @returns {Array} An array of saved games.
 */
const parseGames = () => {
    let games = JSON.parse(localStorage.getItem("savedGames"));
    return !games ? [] : games;
};

const searchParams = new URLSearchParams(window.location.search);
let games = parseGames();

/**
 * Refreshes the cached games in localStorage.
 *
 * @param {Array} games - The array of games to be saved.
 */
const refreshCachedGames = (games) =>
    localStorage.setItem("savedGames", JSON.stringify(games));

/**
 * Gets the current date in YYYY-MM-DD format.
 *
 * @returns {string} The current date.
 */
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

/**
 * Gets the current time in HH:MM format.
 *
 * @returns {string} The current time.
 */
const time = () =>
    new Date().toLocaleTimeString("hu-HU", {
        hour: "2-digit",
        minute: "2-digit",
    });

/**
 * Deletes all saved games from localStorage.
 */
export const deleteSaves = () => localStorage.removeItem("savedGames");

/**
 * Generates a save name based on the current date and time.
 *
 * @returns {string} The generated save name.
 */
export const getSaveName = () => `${date()} ${time()}`;

/**
 * Saves a game to localStorage.
 *
 * @param {string} saveName - The name of the save.
 * @param {Object} game - The game object to be saved.
 * @param {number} elapsedTime - The elapsed time of the game.
 */
export function saveGame(saveName, game, elapsedTime) {
    games.push({
        name: saveName,
        board: game,
        elapsedTime: elapsedTime,
    });

    refreshCachedGames(games);
}

/**
 * Loads a game from localStorage based on the URL search parameter.
 *
 * @returns {Object|null} The loaded game object or null if not found.
 */
export function loadGame() {
    let id = searchParams.get("id");
    if (!id || isNaN(id) || id < 1 || id > games.length) {
        return null;
    }

    id--;
    let gameData = games[id].board;
    let game = {
        board: new Minesweeper(gameData.size),
        elapsedTime: games[id].elapsedTime,
    };
    Object.assign(game.board, gameData);

    game.board.fields = gameData.fields.map((row) =>
        row.map((fieldData) => Object.assign(new Field(), fieldData))
    );

    return game;
}

/**
 * Creates a new game based on the URL search parameters.
 *
 * @returns {Object|null} The new game object or null if parameters are incorrect.
 */
export function newGame() {
    const size = searchParams.get("size");

    const isCorrectQuery =
        size &&
        !isNaN(size) &&
        size >= MIN_SIZE &&
        size <= MAX_SIZE &&
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

    return { board: new Minesweeper(searchParams.get("size"), result) };
}

/**
 * Deletes a specific saved game from localStorage.
 *
 * @param {number} id - The ID of the save to be deleted.
 */
export function deleteSave(id) {
    let games = parseGames();
    games.splice(id, 1);

    if (games.length === 0) {
        deleteSaves();
    } else {
        refreshCachedGames(games);
    }
}
