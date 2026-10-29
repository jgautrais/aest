<script>
    import { COLUMNS, ROWS } from '../lib/game.js';

    let { filled } = $props();
</script>

<table class="area_container w-full h-full">
    <tbody>
        {#each { length: ROWS }, row}
            <tr>
                {#each { length: COLUMNS }, col}
                    <td class="area_cell" class:bg-black={filled.has(row * COLUMNS + col)}></td>
                {/each}
            </tr>
        {/each}
    </tbody>
</table>
