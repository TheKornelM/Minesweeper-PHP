"use strict";

import * as SaveGame from "./SaveGame.js";
import Field from "./Field.js";

const BASE_DIRECTORY = "/www"

async function deleteSaves(){
    await SaveGame.deleteSaves();
    printSaves();
}

document.querySelector("#delete-saves").addEventListener("click", deleteSaves);

window.addEventListener("load", (event) => {
    printSaves();
});

async function printSaves() {
    clearSavesContainer();
    const games = await getSavedGames();

    if (!games) {
        redirectToNewGame();
        return;
    }

    games.forEach((element) => {
        const saveElement = createSaveElement(element, element.id);
        document.querySelector("#saves").append(saveElement);
    });

    document.querySelector("body").removeAttribute("hidden");
    attachDeleteButtonHandlers();
}

function clearSavesContainer() {
    document.querySelector("#saves").innerHTML = "";
}

async function getSavedGames() {
    try {
        const response = await fetch(`savegame.php`);

        if (!response.ok) {
            return null;
        }

        return await response.json();
    } catch (error) {
        console.error("Error getting games:", error);
        return null;
    }
}

function redirectToNewGame() {
    window.location.href = "new.php";
}

function createSaveElement(element, index) {
    const save = document.createElement("div");
    save.className = "row save";

    const saveNameDiv = createSaveNameDiv(element, index);
    save.append(saveNameDiv);

    const deleteDiv = createDeleteDiv(index);
    save.append(deleteDiv);

    return save;
}

function createSaveNameDiv(element, index) {
    const saveNameDiv = document.createElement("div");
    saveNameDiv.className = "col-md-6 d-flex align-items-center";

    const link = document.createElement("a");
    link.href = `${BASE_DIRECTORY}/game/play.php?id=${index}`;
    link.append(element.name);

    saveNameDiv.append(link);
    return saveNameDiv;
}

function createDeleteDiv(index) {
    const deleteDiv = document.createElement("div");
    deleteDiv.className = "col-md-6";

    const deleteButton = createDeleteButton(index);
    deleteDiv.append(deleteButton);

    return deleteDiv;
}

function createDeleteButton(index) {
    const deleteButton = document.createElement("button");
    deleteButton.id = index;
    deleteButton.classList = "delete-button btn btn-danger btn-md";
    deleteButton.innerHTML = "<i class='fa fa-trash'></i> Delete";
    deleteButton.title = "Delete";

    return deleteButton;
}

async function attachDeleteButtonHandlers() {
    document.querySelectorAll(".delete-button").forEach((elem) => {
        elem.addEventListener("click", async (event) => {
            if(await SaveGame.deleteSave(event.target.id)){
                await printSaves();
            }
        });
    });
}
