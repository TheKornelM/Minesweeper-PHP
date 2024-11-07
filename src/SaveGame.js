"use strict";

import { default as Minesweeper, MIN_SIZE, MAX_SIZE } from "./Minesweeper.js";
import Field from "./Field.js";
import Difficulty from "./Difficulty.js";

const parseGames = () => {
    let games = JSON.parse(localStorage.getItem("savedGames"));

    return !games ? [] : games;
};

const searchParams = new URLSearchParams(window.location.search);
let games = parseGames();

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

export function saveGame(saveName, game, elapsedTime) {
    games.push({
        name: saveName,
        board: game,
        elapsedTime: elapsedTime,
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

export function deleteSave(id) {
    let games = parseGames();
    games.splice(id, 1);

    if (games.length === 0) {
        deleteSaves();
    } else {
        refreshCachedGames(games);
    }
}
