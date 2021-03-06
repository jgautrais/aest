import Helpers from './Helpers.js';
const helpers = new Helpers();
const $ = require('jquery');

export default class Area {
    _rows = 20;
    _columns = 20;
    _area = $('#areaContainer');

    constructor() {
        this.drawHTMLArea();
    }

    /**
     * Generate 2D array of squares
     */
    drawHTMLArea() {
        for (let row = 0; row < this._rows; row++) {
            this._area.append('<tr>');
            for (let col = 0; col < this._columns; col++) {
                this._area
                    .children('tr')
                    .eq(row)
                    .append(
                        `<td class='area_cell' id='cell_${
                            row ? row : ''
                        }${col}'></td>`
                    );
            }
            this._area.append('</tr>');
        }
    }

    /**
     * Fill area with [fillValue] black squares
     */
    fillArea(fillValue) {
        const cells = $('.area_cell');
        for (let i = 0; i < fillValue * 4; i++) {
            const id = helpers.getRandomNum(0, cells.length - 1);
            cells.eq(id).addClass('bg-black');
            cells.splice(id, 1);
        }
    }

    clearArea() {
        const cells = $('.area_cell');
        cells.removeClass('bg-black');
    }
}
