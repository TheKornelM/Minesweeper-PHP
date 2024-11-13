# Minesweeper (Aknakereső)
Készítette: Makári Kornél Sándor, MQOD6Q

## Alapvető információk
- Felület nyelve: angol
- Kliens oldalon megvalósított program (JavaScript)
- Reszponzív, így például telefonon is játszhatunk.
- Dokumentáció: https://thekornelm.github.io/Minesweeper-JS/
- Az alábbi oldalon játszható a játék: https://thekornel.web.elte.hu/Minesweeper

## Játékmenet
A játék célja, hogy felfedjünk minden mezőt, ahol **nem** található akna.\
Üres mező: nincs körülötte sehol sem akna.\
Szám van a mezőben: ennyi darab szomszédos akna van a mező körül.

## Játék tulajdonságai
A megvalósított aknakereső az alábbiakra képes.

### Tábla mérete
Lehetőség van új játék létrehozására 4x4-es mérettől kezdve 15x15-ös méretig.

### Játék nehézsége
Három fajta nehézség van, ami az aknák arányát határozza meg:
- Könnyű
- Közepes
- Nehéz

### Zászlók
Az aknák helyét zászlóval lehet jelölni.\
A mező megjelöléséhez vagy a zászló eltávolításához a mezőt hosszan nyomva kell tartani.\
A zászlóval fedett mezőt nem lehet felfedni, amíg el nem 
távolítjuk a zászlót.

### Aknák
Amennyire olyan mezőre lépünk, ahol akna található, a játék véget ér. Ekkor felfedésre kerül az összes akna helye, illetve piros alapon megjelölésre kerülnek az aknát nem tartalmazó, zászlóval jelölt mezők.

## Fejlesztési tervek
Az alábbi funkciók bevezetése van tervben szerveroldali megvalósítás esetén:
- Pontszámok
- XP rendszer
- Rekordok tárolása
- Achievement rendszer
- VIP tagság