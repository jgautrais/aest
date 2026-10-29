<script>
    import { getPrecisionWidths } from '../lib/game.js';

    let { gameCount, turnCount, accuracy, precisions } = $props();

    const bars = [
        { color: 'bg-green-500', label: '< 5%' },
        { color: 'bg-yellow-300', label: '< 10%' },
        { color: 'bg-red-500', label: '>=10%' },
    ];
    let widths = $derived(getPrecisionWidths(precisions));
</script>

<div class="stats_container absolute w-full h-full py-4 px-8 bg-gray-50">
    <div class="flex flex-col h-full">
        <div class="text-center">
            <p class="text-3xl mb-0.5">Game {gameCount}</p>
            <p>
                <span class="font-bold text-lg">{turnCount} {turnCount > 1 ? 'turns' : 'turn'}</span>
            </p>
            <p class="mt-2 text-xs">
                Avg Difference:
                <span class="font-bold text-xl">{accuracy}</span>
                %
            </p>
        </div>
        <div class="text-xs border-l-2 border-black grow mt-3.5 mb-2 flex justify-between">
            <div class="w-5/6">
                {#each bars as bar, i}
                    <p class="{bar.color} my-4 py-1" style:width="{widths[i]}%">
                        <span class="pl-4">{bar.label}</span>
                    </p>
                {/each}
            </div>
            <div class="w-1/6">
                {#each precisions as count}
                    <p class="my-4 pl-4 py-1">{count}</p>
                {/each}
            </div>
        </div>
    </div>
</div>
