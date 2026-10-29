import { describe, expect, it } from 'vitest';
import {
    CELLS,
    fillCells,
    getAccuracy,
    getMeanAccuracy,
    getPrecisionCategory,
    getPrecisionWidths,
    newTarget,
    parseEstimate,
} from './game.js';

describe('newTarget', () => {
    it('stays in [5, 95]', () => {
        expect(newTarget(() => 0)).toBe(5);
        expect(newTarget(() => 0.9999)).toBe(95);
    });
});

describe('fillCells', () => {
    it('blackens 4 distinct cells per percent', () => {
        for (const percentage of [5, 42, 95]) {
            const cells = fillCells(percentage);
            expect(cells.size).toBe(percentage * 4);
            for (const cell of cells) {
                expect(cell).toBeGreaterThanOrEqual(0);
                expect(cell).toBeLessThan(CELLS);
            }
        }
    });
});

describe('parseEstimate', () => {
    it('accepts 0-100', () => {
        expect(parseEstimate('0')).toBe(0);
        expect(parseEstimate('42')).toBe(42);
        expect(parseEstimate('100')).toBe(100);
    });

    it('rejects empty, non numeric and out of range values', () => {
        for (const raw of ['', ' ', 'abc', '-1', '101']) {
            expect(parseEstimate(raw)).toBeNull();
        }
    });
});

describe('precision', () => {
    it('categorizes the difference', () => {
        expect(getPrecisionCategory(getAccuracy(50, 54))).toBe(0);
        expect(getPrecisionCategory(getAccuracy(50, 45))).toBe(1);
        expect(getPrecisionCategory(getAccuracy(50, 60))).toBe(2);
    });

    it('computes mean accuracy and bar widths', () => {
        expect(getMeanAccuracy([1, 2, 4])).toBe('2.3');
        expect(getPrecisionWidths([1, 1, 2])).toEqual(['25', '25', '50']);
    });
});
