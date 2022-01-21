import Area from './Area.js';
import Board from './Board.js';
import Turn from './Turn.js';
const $ = require('jquery');

export default class Game {
    _area = new Area();
    _board = new Board();
    _turnCount = 0;
    _accuracy = [];
    _resultsPrecisionCategories = [0, 0, 0]; // Precision categories: <5%, 5-10%, >10%

    constructor() {}

    /**
     * Initialize game
     */
    startGame() {
        this._board.getStartButton().on('click', () => {
            if (this._turnCount === 0) {
                this._board.getStartButton().html('New estimate');
            } else {
                this._area.clearArea();
                this._board.resetDisplay();
            }

            this.newTurn();
        });
    }

    /**
     * Set up new Turn
     */
    newTurn() {
        const turn = new Turn();
        this._turnCount++;

        this._area.fillArea(turn.getPercentageToGuess());
        this._board.handleStartTurn();

        this._board.getInputForm().on('submit', (e) => {
            e.preventDefault();

            const inputValue = this._board.getInputValue();
            if (!inputValue) {
                return;
            }
            turn.setUserEstimate(inputValue);
            this._board.handleFormSubmit();

            this._accuracy.push(turn.getAccuracy());
            this._resultsPrecisionCategories[turn.getPrecisionCategory()]++;

            this._board.displayResults(
                turn.getPercentageToGuess(),
                turn.getUserEstimate()
            );

            this._board.handleEndTurn();
        });
    }
}
