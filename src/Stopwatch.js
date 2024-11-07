"use strict";

let timer = false;
export let elapsedTime = {
    hour: 0,
    minute: 0,
    second: 0,
    count: 0,
};

let timeout = setTimeout(stopWatch, 10);

export function startStopwatch() {
    timer = true;
    timeout = setTimeout(stopWatch, 10);
}

export function stopStopwatch() {
    timer = false;
    clearTimeout(timeout);
}

function stopWatch() {
    if (!timer) {
        return;
    }

    elapsedTime.count++;

    if (elapsedTime.count == 100) {
        elapsedTime.second++;
        elapsedTime.count = 0;
    }

    if (elapsedTime.second == 60) {
        elapsedTime.minute++;
        elapsedTime.second = 0;
    }

    if (elapsedTime.minute == 60) {
        elapsedTime.hour++;
        elapsedTime.minute = 0;
        elapsedTime.second = 0;
    }

    timeout = setTimeout(stopWatch, 10);
}

export function getElapsedTimeString() {
    let hrString = elapsedTime.hour;
    let minString = elapsedTime.minute;
    let secString = elapsedTime.second;
    let countString = elapsedTime.count;

    if (elapsedTime.hour < 10) {
        hrString = "0" + hrString;
    }

    if (elapsedTime.minute < 10) {
        minString = "0" + minString;
    }

    if (elapsedTime.second < 10) {
        secString = "0" + secString;
    }

    if (elapsedTime.count < 10) {
        countString = "0" + countString;
    }

    return `${hrString}:${minString}:${secString}:${countString}`;
}
