import Area from './Area.js';
import Board from './Board.js';
import Helpers from './Helpers.js';
import Turn from './Turn.js';
const helpers = new Helpers();
const $ = require('jquery');

export default class Game {
    _area = new Area();
    _board = new Board();
    _gameCount;
    _turnCount = 0;
    _accuracies = [];
    _resultsPrecisionCategories = [0, 0, 0]; // Precision categories: <5%, 5-10%, >10%

    constructor(gameCount) {
        this._gameCount = gameCount;
    }

    /**
     * Initialize game
     */
    startGame() {
        this._board.getStartButton().on('click', () => {
            if (this._turnCount === 0) {
                this._gameCount++;
                this._board.handleGameStart();
            }
            this._area.clearArea();

            this.newTurn();
        });

        this._board.getEndButton().on('click', () => {
            this._board.handleGameEnd(
                this._gameCount,
                this._turnCount,
                helpers.getMeanAccuracy(this._accuracies),
                this._resultsPrecisionCategories
            );
            this._turnCount = 0;
            this._accuracies = [];
            this._resultsPrecisionCategories = [0, 0, 0];
        });
    }

    /**
     * Set up new Turn
     */
    newTurn() {
        const turn = new Turn();
        this._turnCount++;

        this._area.fillArea(turn.getPercentageToGuess());
        this._board.handleStartTurn(this._turnCount);

        this.handleTurnSubmit(turn);
    }

    /**
     * Turn submit
     */
    handleTurnSubmit(turn) {
        this._board.getInputForm().on('submit', (e) => {
            e.preventDefault();

            const inputValue = this._board.getInputValue();
            if (!inputValue) {
                return;
            }
            turn.setUserEstimate(inputValue);

            this._board.handleFormSubmit();

            this._accuracies.push(turn.getAccuracy());
            this._resultsPrecisionCategories[turn.getPrecisionCategory()]++;

            this._board.displayResults(
                turn.getPercentageToGuess(),
                turn.getUserEstimate(),
                turn.getPrecisionCategory()
            );

            this._board.handleEndTurn();
        });
    }
}
