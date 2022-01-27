import Helpers from './Helpers.js';
const helpers = new Helpers();
export default class Turn {
    _percentageToGuess;
    _userEstimate;
    _diff;

    constructor() {
        this._percentageToGuess = helpers.getRandomNum(5, 96);
    }

    getAccuracy() {
        this._diff = Math.abs(this._userEstimate - this._percentageToGuess);
        return this._diff;
    }

    getPrecisionCategory() {
        if (this._diff < 5) {
            return 0;
        } else if (this._diff < 10) {
            return 1;
        } else {
            return 2;
        }
    }

    /**
     * Getter _percentageToGuess
     */
    getPercentageToGuess() {
        return this._percentageToGuess;
    }

    getUserEstimate(inputValue) {
        return this._userEstimate;
    }

    setUserEstimate(inputValue) {
        this._userEstimate = +inputValue;
    }
}
