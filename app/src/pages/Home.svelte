<script>
    import Layout from '../components/Layout.svelte';
    import Game from '../components/Game.svelte';
</script>

<Layout>
    <div class="text-center font-bold mb-5 sm:mb-8">
        <h2 class="mt-1 text-lg sm:text-xl text-gray-400">Area estimation tool</h2>
        <h3 class="description text-sm text-gray-500 sm:w-80 mt-12 mx-5">
            Try to guess the percentage of black squares cover in the area below.
            <br />
            <a href="/about" class="text-purple-600 underline">Learn more&nearr;</a>
        </h3>
    </div>
    <div class="relative grow max-w-2xl w-full">
        <Game />
        <div class="relative flex flex-col mt-12 sm:mt-28 mb-16">
            <p class="text-xs text-gray-600 mx-5">
                After starting a game, hit
                <span class="inline-block pt-0.5 px-1.5 bg-black text-white rounded-sm leading-6">End</span>
                to stop and get stats about your estimates.
            </p>
        </div>
    </div>
</Layout>
