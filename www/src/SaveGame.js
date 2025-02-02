/**
 * @module
 */

"use strict";

import { default as Minesweeper, MIN_SIZE, MAX_SIZE } from "./Minesweeper.js";
import Field from "./Field.js";
import Difficulty from "./Difficulty.js";
import * as CurrentDate from "./CurrentDate.js";

const searchParams = new URLSearchParams(window.location.search);
CurrentDate.setLocationCode("hu-HU");

/**
 * Refreshes games from the server.
 *
 * @param {Array} games - The array of games to be saved.
 */
const refreshCachedGames = (games) =>
    localStorage.setItem("savedGames", JSON.stringify(games));

/**
 * Deletes all saved games by user from the server.
 */
export async function deleteSaves()
{
    //localStorage.removeItem("savedGames");
    try {
        const response = await fetch('../api/games.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ saveId: "*" })
        });

        if (!response.ok) {
            throw new Error('Failed to delete user saves');
        }

        return true;
    } catch (error) {
        console.error('Error:', error);
        return false;
    }
}

/**
 * Generates a save name based on the current date and time.
 *
 * @returns {string} The generated save name.
 */
export const getSaveName = () => `${CurrentDate.date()} ${CurrentDate.time()}`;

/**
 * Saves a game to the server.
 *
 * @param {string} saveName - The name of the save.
 * @param {Object} game - The game object to be saved.
 * @param {number} elapsedTime - The elapsed time of the game.
 */
export async function saveGame(saveName, game, elapsedTime) {

    try {
        const response = await fetch('../api/game.php', {
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

        // Check response status
        if (!response.ok) {
            console.error('Failed to save game. HTTP status:', response.status);
            return;
        }

        const result = await response.json();

        // Handle JSON response
        if (result.error) {
            console.error('Error saving game:', result.message || 'Unknown error');
        }

    } catch (error) {
        console.error('Request failed:', error);
    }
}


/**
 * Loads a game from the server based on the URL search parameter.
 *
 * @returns {Object|null} The loaded game object or null if not found.
 */
export async function loadGame() {
    let id = searchParams.get("id");
    if (!id || isNaN(id) || id < 1) {
        return null;
    }

    try {
        const response = await fetch(`../api/game.php?id=${id}`);
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
 * Deletes a specific saved game from the server.
 *
 * @param {number} id - The ID of the save to be deleted.
 */
export async function deleteSave(id) {
    try {
        const response = await fetch('../api/game.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ saveId: parseInt(id) })
        });

        if (!response.ok) {
            throw new Error('Failed to delete save');
        }

        return true;
    } catch (error) {
        console.error('Error:', error);
        return false;
    }
}