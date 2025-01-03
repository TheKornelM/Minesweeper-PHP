/**
 * @module
 */

"use strict";

/**
 * Class representing a stopwatch.
 */
export default class Stopwatch {
    /**
     * Creates an instance of Stopwatch.
     */
    constructor() {
        this.timer = false;
        this.elapsedTime = {
            hour: 0,
            minute: 0,
            second: 0,
            count: 0,
        };
        this.timeout = null;
    }

    /**
     * Starts the stopwatch timer.
     */
    start() {
        this.timer = true;
        this.timeout = setTimeout(() => this.stopWatch(), 10);
    }

    /**
     * Stops the stopwatch timer.
     */
    stop() {
        this.timer = false;
        clearTimeout(this.timeout);
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
    set(newElapsedTime) {
        this.elapsedTime = newElapsedTime;
    }

    /**
     * The main stopwatch function that increments the elapsed time.
     * This function is called recursively every 10 milliseconds.
     * @private
     */
    stopWatch() {
        if (!this.timer) {
            return;
        }

        this.elapsedTime.count++;

        if (this.elapsedTime.count === 100) {
            this.elapsedTime.second++;
            this.elapsedTime.count = 0;
        }

        if (this.elapsedTime.second === 60) {
            this.elapsedTime.minute++;
            this.elapsedTime.second = 0;
        }

        if (this.elapsedTime.minute === 60) {
            this.elapsedTime.hour++;
            this.elapsedTime.minute = 0;
            this.elapsedTime.second = 0;
        }

        this.timeout = setTimeout(() => this.stopWatch(), 10);
    }

    /**
     * Gets the elapsed time as a formatted string.
     *
     * @returns {string} The elapsed time in the format HH:MM:SS:CC.
     */
    getElapsedTimeString() {
        let hrString = this.elapsedTime.hour;
        let minString = this.elapsedTime.minute;
        let secString = this.elapsedTime.second;
        let countString = this.elapsedTime.count;

        if (this.elapsedTime.hour < 10) {
            hrString = "0" + hrString;
        }

        if (this.elapsedTime.minute < 10) {
            minString = "0" + minString;
        }

        if (this.elapsedTime.second < 10) {
            secString = "0" + secString;
        }

        if (this.elapsedTime.count < 10) {
            countString = "0" + countString;
        }

        return `${hrString}:${minString}:${secString}:${countString}`;
    }
}
