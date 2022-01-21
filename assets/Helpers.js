export default class Helpers {
    constructor() {}

    getRandomNum(min, max) {
        return min + Math.floor(Math.random() * (max - min));
    }

    disableButton(element) {
        element.prop('disabled', true);
        element.removeClass('bg-black');
        element.addClass('bg-gray-200');
    }

    enableButton(element) {
        element.prop('disabled', false);
        element.removeClass('bg-gray-200');
        element.addClass('bg-black');
    }

    disableInput(element) {
        element.prop('disabled', true);
    }

    enableInput(element) {
        element.prop('disabled', false);
    }
}
