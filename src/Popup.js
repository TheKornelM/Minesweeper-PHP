"use strict";

export function showOverlay(content, hasCloseButton) {
    document.getElementById("popup").style.display = "block";
    document.getElementById("game").classList.add("disabled");

    const contentElement = document.querySelector("#popup #popup-message");
    contentElement.innerHTML = content; // Set inner HTML directly

    if (!hasCloseButton) {
        return;
    }

    document
        .querySelector("#popup .close")
        .addEventListener("click", function () {
            document.getElementById("popup").style.display = "none";
        });
}

export function hideOverlay() {
    document.getElementById("popup").style.display = "none";
    document.getElementById("game").classList.remove("disabled");
}
