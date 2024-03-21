export default class UserStatsHelpers {
    constructor() {}

    isLogged() {
        return fetch('/isLogged')
            .then((res) => res.json())
            .then((data) => {
                return data.isLogged;
            });
    }

    incrementGameCount() {
        return fetch('/incrementGameCount')
            .then((res) => res.json())
            .then((data) => {
                return data.success;
            });
    }

    saveTurn(percentageToGuess, userEstimate, accuracy, accuracyCategory) {
        return fetch(
            `/saveTurn/${percentageToGuess}/${userEstimate}/${accuracy}/${accuracyCategory}`
        ).then((res) => res.json());
    }

    getUserData(period) {
        return fetch(`/getUserStats/${period}`).then((res) => res.json());
    }
}
