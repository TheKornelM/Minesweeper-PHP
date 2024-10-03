"use strict";

window.addEventListener("load", (event) => {
    const games = JSON.parse(localStorage.getItem("savedGames"));

    if (!games) {
        alert("SaveGame not found");
        window.location.replace("/play.html");
    }

    games.forEach((element) => {
        let text = document.createElement("p");

        console.log(element.name);
        text.append(element.name);
        document.querySelector("#saves").append(text);
    });
});
