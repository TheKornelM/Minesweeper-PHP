/**
 * @module
 */

"use strict";

import { default as Minesweeper, MIN_SIZE, MAX_SIZE } from "./Minesweeper.js";
import Field from "./Field.js";
import Difficulty from "./Difficulty.js";
import * as CurrentDate from "./CurrentDate.js";

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
CurrentDate.setLocationCode("hu-HU");

/**
 * Refreshes the cached games in localStorage.
 *
 * @param {Array} games - The array of games to be saved.
 */
const refreshCachedGames = (games) =>
    localStorage.setItem("savedGames", JSON.stringify(games));

/**
 * Deletes all saved games from localStorage.
 */
export const deleteSaves = () => localStorage.removeItem("savedGames");

/**
 * Generates a save name based on the current date and time.
 *
 * @returns {string} The generated save name.
 */
export const getSaveName = () => `${CurrentDate.date()} ${CurrentDate.time()}`;

/**
 * Saves a game to localStorage.
 *
 * @param {string} saveName - The name of the save.
 * @param {Object} game - The game object to be saved.
 * @param {number} elapsedTime - The elapsed time of the game.
 */
export async function saveGame(saveName, game, elapsedTime) {

    try {
        const response = await fetch('saveGame.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                save_name: saveName,
                elapsed_time: elapsedTime, // Send object as is
                board: JSON.stringify(game),
            }),
        });

        const result = await response.json();
        if (result.status === 'success') {
            console.log('Game saved successfully!');
        } else {
            console.error('Error saving game:', result.message);
        }
    } catch (error) {
        console.error('Request failed:', error);
    }
}


/**
 * Loads a game from localStorage based on the URL search parameter.
 *
 * @returns {Object|null} The loaded game object or null if not found.
 */
export async function loadGame() {
    let id = searchParams.get("id");
    if (!id || isNaN(id) || id < 1) {
        return null;
    }

    try {
        const response = await fetch(`load.php?id=${id}`);
        if (!response.ok) {
            return null;
        }

        const gameData = await response.json();

        if (!gameData || !gameData.board_data) {
            return null; // Redirect to new game if no data or board is found
        }

        let game = {
            board: new Minesweeper(gameData.board_data.size),
            elapsedTime: gameData.elapsed_time,
        };

        Object.assign(game.board, gameData.board_data);

        game.board.fields = gameData.board_data.fields.map((row) =>
            row.map((fieldData) => Object.assign(new Field(), fieldData))
        );

        return game;
    } catch (error) {
        console.error("Error loading game:", error);
        return null;
    }
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