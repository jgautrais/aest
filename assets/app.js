/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
import Game from './Game.js';

const $ = require('jquery');

$(document).ready(function () {
    handleLoginModal();

    const game = new Game(0);

    game.startGame();
});

function handleLoginModal() {
    const loginModal = $('#login-modal');
    const loginModalHide = $('#login-modal-hide');
    const loginIcon = $('#login-icon');

    loginIcon.on('click', () => {
        if (loginModal.hasClass('hidden')) {
            loginModal.removeClass('hidden');
        }
    });

    loginModalHide.on('click', () => {
        if (!loginModal.hasClass('hidden')) {
            loginModal.addClass('hidden');
        }
    });
}
