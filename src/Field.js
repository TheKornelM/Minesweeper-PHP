"use strict";

import State from "./State.js";

/*
  Field osztály: Tárolja egy mező adatait

  Mezők:
    HasMine: Tárolja, hogy az adott mező akna-e
      true : igen, false: nem
    neighborMineCount: Tárolja a szomszédos aknásított mezők számát
    state: a mező állapota
*/

export default class Field {
    hasMine;
    neighborMineCount;
    state;

    constructor() {
        this.hasMine = false;
        this.neighborMineCount = 0;
        this.state = State.UNSELECTED;
    }
};
