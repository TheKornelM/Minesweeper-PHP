"use strict";

let timer = false;

/**
 * Object to store the elapsed time.
 *
 * @property {number} hour - The number of hours.
 * @property {number} minute - The number of minutes.
 * @property {number} second - The number of seconds.
 * @property {number} count - The count of 10-millisecond intervals.
 */
export let elapsedTime = {
    hour: 0,
    minute: 0,
    second: 0,
    count: 0,
};

let timeout = setTimeout(stopWatch, 10);

/**
 * Starts the stopwatch timer.
 */
export function start() {
    timer = true;
    timeout = setTimeout(stopWatch, 10);
}

/**
 * Stops the stopwatch timer.
 */
export function stop() {
    timer = false;
    clearTimeout(timeout);
}

/**
 * Sets the elapsed time to a new value.
 *
 * @param {Object} newElapsedTime - The new elapsed time.
 * @param {number} newElapsedTime.hour - The number of hours.
 * @param {number} newElapsedTime.minute - The number of minutes.
 * @param {number} newElapsedTime.second - The number of seconds.
 * @param {number} newElapsedTime.count - The count of 10-millisecond intervals.
 */
export function set(newElapsedTime) {
    elapsedTime = newElapsedTime;
}

/**
 * The main stopwatch function that increments the elapsed time.
 * This function is called recursively every 10 milliseconds.
 */
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

/**
 * Gets the elapsed time as a formatted string.
 *
 * @returns {string} The elapsed time in the format HH:MM:SS:CC.
 */
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
