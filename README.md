# Minesweeper
Created by: Kornél Sándor Makári, MQOD6Q

## Basic Information
- Interface language: English
- Javascript and PHP backend
- Responsive, so it can be played on phones as well.
- Documentation: https://thekornelm.github.io/Minesweeper-PHP/
- Live demo: https://thekornel.web.elte.hu/www/

## Gameplay
The goal of the game is to uncover all the tiles where there are **no** mines.\
Empty tile: no mines around it.\
Number on the tile: indicates the number of adjacent mines around the tile.

## Game Features
The implemented Minesweeper has the following capabilities.

### Board Size
You can start a new game with sizes ranging from 4x4 to 15x15.

### Game Difficulty
There are three difficulty levels, which determine the ratio of mines:
- Easy
- Medium
- Hard

### Flags
You can mark the location of mines with flags.\
To mark or remove a flag, press and hold the tile.\
A tile covered with a flag cannot be uncovered until the flag is removed.

### Mines
If you step on a tile with a mine, the game ends. All mine locations are then revealed, and tiles marked with flags that do not contain mines are highlighted in red.

## Development Plans
- Scores
- XP system
- Record storage
- Achievement system
- VIP membership
