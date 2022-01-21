import Helpers from './Helpers.js';
const helpers = new Helpers();
const $ = require('jquery');

export default class Board {
    _boardContainer = $('#board');
    _turnCounter = $('#turnCounter');
    _turnCount = $('#turnCount');
    _startButton = $('#startButton');
    _endButton = $('#endButton');
    _inputForm = $('#inputForm');
    _inputValue = $('#inputValue');
    _inputSubmit = $('#inputSubmit');
    _boardScore = $('#boardScore');
    _scoreEstimate = $('#scoreEstimate');
    _scoreTarget = $('#scoreTarget');

    constructor() {}

    handleGameStart() {
        this._startButton.html('New estimate');
        this._endButton.show();
        this._turnCounter.removeClass('hidden');
        this._turnCounter.addClass('flex');
    }
    /**
     * Handle turn start
     */
    handleStartTurn(turnCount) {
        this.resetBgColor();
        this._turnCount.html(turnCount);
        helpers.disableButton(this._startButton);
        helpers.disableButton(this._endButton);
        helpers.enableInput(this._inputValue);
        helpers.enableButton(this._inputSubmit);
        this._inputValue.focus();
    }

    handleFormSubmit() {
        helpers.disableInput(this._inputValue);
        helpers.disableButton(this._inputSubmit);
    }

    displayResults(percentageToGuess, userEstimate, precisionCategory) {
        this.setBgColor(precisionCategory);
        this._boardScore.show();
        this._scoreEstimate.html(userEstimate);
        this._scoreTarget.html(percentageToGuess);
    }

    handleEndTurn() {
        helpers.enableButton(this._startButton);
        helpers.enableButton(this._endButton);
        this._startButton.focus();
        this._inputForm.off();
    }

    resetDisplay() {
        this._boardScore.hide();
        this._inputValue.val('');
    }

    setBgColor(precisionCategory) {
        switch (precisionCategory) {
            case 0:
                this._boardContainer.addClass('bg-green-500');
                break;
            case 1:
                this._boardContainer.addClass('bg-yellow-300');
                break;
            case 2:
                this._boardContainer.addClass('bg-red-500');
                break;
        }
    }

    resetBgColor() {
        const colors = ['bg-green-500', 'bg-yellow-300', 'bg-red-500'];
        colors.forEach((color) => {
            if (this._boardContainer.hasClass(color)) {
                console.log('here');
                this._boardContainer.removeClass(color);
            }
        });
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

    getTurnCounter() {
        return this._turnCounter;
    }

    getStartButton() {
        return this._startButton;
    }

    getEndButton() {
        return this._endButton;
    }

    getInputForm() {
        return this._inputForm;
    }
}
