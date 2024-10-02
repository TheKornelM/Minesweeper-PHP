"use strict";

const CheckParameterIsNumber = function (number) {
    if (isNaN(number)) throw `${number} is not a number`;
};

export default CheckParameterIsNumber;
