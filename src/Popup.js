"use strict";

/**
 * Displays an overlay with the specified content.
 * @param {string} content - The HTML content to display inside the overlay.
 * @param {boolean} hasCloseButton - Indicates whether the overlay should have a close button.
 */
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

/**
 * Hides the overlay and re-enables the game.
 */
export function hideOverlay() {
    document.getElementById("popup").style.display = "none";
    document.getElementById("game").classList.remove("disabled");
}
