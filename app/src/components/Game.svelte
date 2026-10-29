<script>
    import { tick } from 'svelte';
    import Area from './Area.svelte';
    import Stats from './Stats.svelte';
    import {
        PRECISION_COLORS,
        fillCells,
        getAccuracy,
        getMeanAccuracy,
        getPrecisionCategory,
        newTarget,
        parseEstimate,
    } from '../lib/game.js';

    // idle: no game running | turn: waiting for an estimate | result: estimate submitted
    let phase = $state('idle');
    let gameCount = $state(0);
    let turnCount = $state(0);
    let accuracies = [];
    let precisions = [0, 0, 0];

    let target = $state(0);
    let filled = $state(new Set());
    let inputValue = $state('');
    let estimate = $state(null);
    let category = $state(null);
    let stats = $state(null);
    let showHint = $state(false);

    let startButton;
    let input;

    let startLabel = $derived(turnCount > 0 ? 'New estimate' : gameCount > 0 ? 'New Game !' : 'Start Game !');
    let turnRunning = $derived(phase === 'turn');

    const hasHover = () => window.matchMedia('(hover: hover)').matches;

    async function startTurn() {
        if (turnCount === 0) {
            gameCount++;
            stats = null;
        }
        turnCount++;

        target = newTarget();
        filled = fillCells(target);
        inputValue = '';
        estimate = null;
        category = null;
        phase = 'turn';
        if (hasHover()) {
            showHint = false;
        }

        await tick();
        input.focus();
    }

    async function submitEstimate(event) {
        event.preventDefault();
        if (phase !== 'turn') {
            return;
        }

        const value = parseEstimate(inputValue);
        if (value === null) {
            inputValue = '';
            return;
        }

        const accuracy = getAccuracy(target, value);
        estimate = value;
        category = getPrecisionCategory(accuracy);
        accuracies.push(accuracy);
        precisions[category]++;
        phase = 'result';
        if (hasHover()) {
            showHint = true;
        }

        await tick();
        startButton.focus();
    }

    function endGame() {
        stats = {
            gameCount,
            turnCount,
            accuracy: getMeanAccuracy(accuracies),
            precisions,
        };
        inputValue = '';
        estimate = null;
        category = null;
        turnCount = 0;
        accuracies = [];
        precisions = [0, 0, 0];
        phase = 'idle';
    }
</script>

<div class="flex flex-col md:flex-row items-center md:justify-around md:items-start">
    <div class="area_border relative border-2 border-black">
        {#if turnCount > 0}
            <p class="absolute pb-4 text-sm flex" id="turnCounter">Turn&nbsp;<span>{turnCount}</span></p>
        {/if}
        <Area {filled} />
        {#if showHint}
            <div class="hint text-sm font-bold absolute bg-white px-3 py-2.5 w-52 text-center border-8 border-purple-500 shadow-xl">
                Hit ENTER to start next turn !
            </div>
        {/if}
        {#if stats}
            <Stats {...stats} />
        {/if}
    </div>

    <div class="relative board_container mt-6 md:mt-0 flex flex-col items-center {category !== null ? PRECISION_COLORS[category] : ''}">
        <div class="my-4">
            <button
                type="button"
                class="text-sm text-white px-4 py-3 hover:bg-gray-700 {turnRunning ? 'button-disabled' : 'bg-black'}"
                disabled={turnRunning}
                bind:this={startButton}
                onclick={startTurn}
            >
                {startLabel}
            </button>
            {#if turnCount > 0}
                <button
                    type="button"
                    class="absolute right-0 top-4 text-sm text-white px-4 py-3 hover:bg-gray-700 {turnRunning ? 'button-disabled' : 'bg-black'}"
                    disabled={turnRunning}
                    onclick={endGame}
                >
                    End
                </button>
            {/if}
        </div>
        <form class="text-sm flex flex-col items-center mt-2" onsubmit={submitEstimate}>
            <label for="inputValue">Blackened area in %:</label>
            <input
                type="text"
                inputmode="numeric"
                pattern="[0-9]|[1-9][0-9]|100"
                id="inputValue"
                class="mt-3 mb-4 bg-gray-100 text-center py-2 px-2 w-16 rounded-sm"
                title="Estimate should be in the range 0-100%"
                disabled={!turnRunning}
                bind:value={inputValue}
                bind:this={input}
            />
            <input
                type="submit"
                value="Submit"
                class="text-sm text-white px-3 py-2 cursor-pointer hover:bg-gray-700 {turnRunning ? 'bg-black' : 'button-disabled'}"
                disabled={!turnRunning}
            />
        </form>
        {#if estimate !== null}
            <div class="text-sm mt-6 py-2 px-4 bg-white">
                <p>
                    <span class="inline-block w-36">Your estimate : </span>
                    <b>{estimate}</b>
                    %
                </p>
                <p>
                    <span class="inline-block w-36">Actual is : </span>
                    <b>{target}</b>
                    %
                </p>
            </div>
        {/if}
    </div>
</div>
