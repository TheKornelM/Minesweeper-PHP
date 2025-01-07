"use strict";

const form = document.querySelector("#new-game");
form.addEventListener("submit", (event) => {
    const values = event.target.elements;
    window.location.href =
        `play.php?size=${values[0].value}&difficulty=${values[1].value}`;
    event.preventDefault();
});

let range = document.getElementById("size");

function updateSize() {
    document.getElementById(
        "current-size"
    ).innerHTML = `${range.value}x${range.value}`;
}

range.addEventListener("input", (event) => {
    updateSize();
});

window.addEventListener("load", (event) => {
    updateSize();
});
