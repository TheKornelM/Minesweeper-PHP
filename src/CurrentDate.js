"use strict";

/**
 * Default location code
 * If the language code is not defined among the parameters of the function, then it is used
 * @type {string}
 */
export let locationCode = "en-EN"; // default location code

/**
 * Sets the location code for date and time formatting.
 *
 * @param {string} newLocationCode - The new location code (e.g., en-EN).
 */
export function setLocationCode(newLocationCode) {
    locationCode = newLocationCode;
}

/**
 * Gets the current date in YYYY-MM-DD format.
 *
 * @param {string} [locale=locationCode] - Location code (e.g., en-EN). Defaults to the globally set location code.
 * @returns {string} The current date.
 */
export const date = (locale = locationCode) => {
    return new Date()
        .toLocaleDateString(locale, {
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
        })
        .split("/")
        .reverse()
        .join("-");
};

/**
 * Gets the current time in HH:MM format.
 *
 * @param {string} [locale=locationCode] - Location code (e.g., en-EN). Defaults to the globally set location code.
 * @returns {string} The current time.
 */
export const time = (locale = locationCode) => {
    return new Date().toLocaleTimeString(locale, {
        hour: "2-digit",
        minute: "2-digit",
    });
};
