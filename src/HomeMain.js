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
    document.querySelector("#saves").innerHTML = "";
    const games = JSON.parse(localStorage.getItem("savedGames"));

    if (!games) {
        window.location.replace("/play.html");
        return;
    }

    games.forEach((element, ind) => {
        let save = document.createElement("div");
        save.className = "row save";

        let saveNameDiv = document.createElement("div");
        saveNameDiv.className = "col-md-3 col-sm-8";

        // Print save names

        let link = document.createElement("a");
        link.href = `/play.html?id=${ind + 1}`;
        link.append(element.name);

        saveNameDiv.append(link);
        save.append(saveNameDiv);

        // Print save delete buttons

        let deleteDiv = document.createElement("div");
        deleteDiv.className = "col-md-1 col-sm-4";

        let deleteButton = document.createElement("input");
        deleteButton.type = "button";
        deleteButton.id = ind;
        deleteButton.value = "Delete";
        deleteButton.className = "delete-button";

        deleteDiv.append(deleteButton);
        save.append(deleteDiv);

        document.querySelector("#saves").append(save);
    });

    document.querySelectorAll(".delete-button").forEach((elem) =>
        elem.addEventListener("click", (event) => {
            SaveGame.deleteSave(event.target.id);
            printSaves();
        })
    );
}
