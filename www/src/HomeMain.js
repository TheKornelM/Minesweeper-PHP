"use strict";

import * as SaveGame from "./SaveGame.js";

const deleteSaves = () => {
    SaveGame.deleteSaves();
    printSaves();
};

document.querySelector("#delete-saves").addEventListener("click", deleteSaves);

window.addEventListener("load", (event) => {
    printSaves();
});

function printSaves() {
    clearSavesContainer();
    const games = getSavedGames();

    if (!games) {
        redirectToNewGame();
        return;
    }

    games.forEach((element, ind) => {
        const saveElement = createSaveElement(element, ind);
        document.querySelector("#saves").append(saveElement);
    });

    document.querySelector("body").removeAttribute("hidden");
    attachDeleteButtonHandlers();
}

function clearSavesContainer() {
    document.querySelector("#saves").innerHTML = "";
}

function getSavedGames() {
    return JSON.parse(localStorage.getItem("savedGames"));
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
    link.href = `/play.html?id=${index + 1}`;
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

function attachDeleteButtonHandlers() {
    document.querySelectorAll(".delete-button").forEach((elem) => {
        elem.addEventListener("click", (event) => {
            SaveGame.deleteSave(event.target.id);
            printSaves();
        });
    });
}
