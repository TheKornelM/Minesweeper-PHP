"use strict";

window.addEventListener("load", (event) => {
    const games = localStorage.getItem("savedGames");

    if (!games) {
        alert("SaveGame not found");
    }

    window.location.replace("/play.html");
});
