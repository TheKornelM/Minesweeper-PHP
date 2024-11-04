"use strict";

export function showOverlay(content) {
    document.getElementById("popup").style.display = "block";
    document.getElementById("game").classList.add("disabled");
    document.querySelector("#popup .content").textContent = content;
    document.getElementById("popup").style.display = "block";

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
