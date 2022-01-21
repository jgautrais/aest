import Helpers from './Helpers.js';
const $ = require('jquery');

export default class Area {
    _rows = 10;
    _columns = 10;
    _area = $('#areaContainer');
    _helpers = new Helpers();

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
        for (let i = 0; i < fillValue; i++) {
            const id = this._helpers.getRandomNum(0, cells.length - 1);
            cells.eq(id).addClass('bg-black');
            cells.splice(id, 1);
        }
    }

    clearArea() {
        const cells = $('.area_cell');
        cells.removeClass('bg-black');
    }
}
