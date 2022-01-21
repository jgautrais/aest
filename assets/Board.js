import Helpers from './Helpers.js';
const $ = require('jquery');

export default class Board {
    _startButton = $('#startButton');
    _inputForm = $('#inputForm');
    _inputValue = $('#inputValue');
    _inputSubmit = $('#inputSubmit');
    _boardScore = $('#boardScore');
    _scoreEstimate = $('#scoreEstimate');
    _scoreTarget = $('#scoreTarget');
    _helpers = new Helpers();

    constructor() {}

    /**
     * Handle turn start
     */
    handleStartTurn() {
        this._helpers.disableButton(this._startButton);
        this._helpers.enableInput(this._inputValue);
        this._helpers.enableButton(this._inputSubmit);
        this._inputValue.focus();
    }

    handleFormSubmit() {
        this._helpers.disableInput(this._inputValue);
        this._helpers.disableButton(this._inputSubmit);
    }

    displayResults(percentageToGuess, userEstimate) {
        this._boardScore.show();
        this._scoreEstimate.html(userEstimate);
        this._scoreTarget.html(percentageToGuess);
    }

    handleEndTurn() {
        this._helpers.enableButton(this._startButton);
        this._startButton.focus();
    }

    resetDisplay() {
        this._boardScore.hide();
        this._inputValue.val('');
    }

    /**
     * Get input value
     */
    getInputValue() {
        const inputValue = this._inputValue.val();
        if (inputValue === '' || isNaN(+inputValue)) {
            this._inputValue.val('');
            return null;
        } else {
            return inputValue;
        }
    }

    getStartButton() {
        return this._startButton;
    }

    getInputForm() {
        return this._inputForm;
    }
}
