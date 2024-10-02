"use strict";

/** 
   State

   Feladata:
      Egy mező állapotának reprezentálása

   Állapotok:
      - UNSELECTED: a mező tartalma nem ismert a felhasználónak, nem volt még kiválasztva/felfedve
      - FLAGGED: zászlóval megjelölve, potenciálisan akna van ott
      - REVEALED: a mezőt kiválasztotta a felhasználó / az algoritmus felfedte, mert üres

*/

export default Object.freeze({
    UNSELECTED: 0,
    FLAGGED: 1,
    REVEALED: 2,
});
