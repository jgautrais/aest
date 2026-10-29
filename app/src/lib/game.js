export const ROWS = 20;
export const COLUMNS = 20;
export const CELLS = ROWS * COLUMNS;

// Precision categories: < 5%, < 10%, >= 10%
export const PRECISION_COLORS = ['bg-green-500', 'bg-yellow-300', 'bg-red-500'];

/**
 * Random integer in [min, max[
 */
export function getRandomNum(min, max, random = Math.random) {
    return min + Math.floor(random() * (max - min));
}

/**
 * Percentage to guess, in [5, 95]
 */
export function newTarget(random = Math.random) {
    return getRandomNum(5, 96, random);
}

/**
 * Set of distinct cell indexes to blacken for a given percentage
 */
export function fillCells(percentage, random = Math.random) {
    const cells = Array.from({ length: CELLS }, (_, i) => i);
    const filled = new Set();
    const count = Math.round((percentage * CELLS) / 100);
    for (let i = 0; i < count; i++) {
        const id = getRandomNum(0, cells.length, random);
        filled.add(cells[id]);
        cells.splice(id, 1);
    }
    return filled;
}

/**
 * Validated estimate as a number, or null if the raw input is not in 0-100
 */
export function parseEstimate(raw) {
    const value = raw.trim();
    if (value === '' || isNaN(+value) || +value < 0 || +value > 100) {
        return null;
    }
    return +value;
}

export function getAccuracy(target, estimate) {
    return Math.abs(estimate - target);
}

export function getPrecisionCategory(accuracy) {
    if (accuracy < 5) {
        return 0;
    } else if (accuracy < 10) {
        return 1;
    }
    return 2;
}

export function getMeanAccuracy(accuracies) {
    return (accuracies.reduce((a, b) => a + b, 0) / accuracies.length).toFixed(1);
}

/**
 * Bar widths (in %) of each precision category
 */
export function getPrecisionWidths(precisions) {
    const total = precisions.reduce((a, b) => a + b, 0);
    return precisions.map((precision) => ((precision / total) * 100).toFixed());
}
