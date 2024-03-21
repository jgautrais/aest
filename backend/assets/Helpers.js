export default class Helpers {
    constructor() {}

    getRandomNum(min, max) {
        return min + Math.floor(Math.random() * (max - min));
    }

    getMeanAccuracy(array) {
        const average = (array) =>
            array.reduce((a, b) => a + b, 0) / array.length;
        return average(array).toFixed(1);
    }

    getTotal(array) {
        return array.reduce((a, b) => a + b, 0);
    }

    disableButton(element) {
        element.prop('disabled', true);
        element.removeClass('bg-black');
        element.addClass('button-disabled');
    }

    enableButton(element) {
        element.prop('disabled', false);
        element.removeClass('button-disabled');
        element.addClass('bg-black');
    }

    disableInput(element) {
        element.prop('disabled', true);
    }

    enableInput(element) {
        element.prop('disabled', false);
    }
}
