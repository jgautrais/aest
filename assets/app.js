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
import UserStatsHelpers from './UserStatsHelpers.js';

const $ = require('jquery');
const userStatsHelpers = new UserStatsHelpers();

$(document).ready(function () {
    handleLoginModal();
    handleFlashMessagesDisplay();
    handleUserStats();

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

function handleFlashMessagesDisplay() {
    $('.flash').delay(5000).fadeOut('slow');
}

function handleUserStats() {
    if ($('#userTurns')) {
        const buttonAll = $('#userPrecisionAll');
        const buttonMonth = $('#userPrecisionMonth');
        const buttonWeek = $('#userPrecisionWeek');
        const buttonDay = $('#userPrecisionDay');

        const buttons = [buttonAll, buttonMonth, buttonWeek, buttonDay];
        const periods = ['all', 'month', 'week', 'day'];

        buttons.forEach((button, index) => {
            button.on('click', () => {
                if (button.hasClass('userStatsActive')) {
                    return;
                }

                userStatsHelpers.getUserData(periods[index]).then((data) => {
                    $('.userStatsActive').addClass('userStatsInactive');
                    $('.userStatsActive').removeClass('userStatsActive');

                    button.addClass('userStatsActive');
                    button.removeClass('userStatsInactive');

                    $('#userStatsPrecisionTurns').html(data.stats.turns);
                    $('#userStatsPrecisionAccuracy').html(
                        data.stats.meanAccuracy
                    );

                    $('#userStatsPrecision0Percentage').css(
                        'width',
                        `${data.stats.precision0.width}%`
                    );
                    $('#userStatsPrecision1Percentage').css(
                        'width',
                        `${data.stats.precision1.width}%`
                    );
                    $('#userStatsPrecision2Percentage').css(
                        'width',
                        `${data.stats.precision2.width}%`
                    );

                    $('#userStatsPrecision0Count').html(
                        data.stats.precision0.turns
                    );
                    $('#userStatsPrecision1Count').html(
                        data.stats.precision1.turns
                    );
                    $('#userStatsPrecision2Count').html(
                        data.stats.precision2.turns
                    );
                });
            });
        });
    }
}
