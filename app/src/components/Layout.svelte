<script>
    let { children } = $props();
</script>

<!-- min-h keeps the height the header had with its former 49px icon buttons -->
<header class="relative flex justify-center items-center min-h-[65px] font-bold mt-2 py-2 sm:mt-8 w-full mx-auto max-w-2xl sticky top-0 bg-gray-50 z-10">
    <a href="/">
        <h1 class="mx-auto text-4xl sm:text-5xl text-gray-700 max-w-min pt-1.5">Aest</h1>
    </a>
</header>

<main class="grow flex flex-col items-center w-full">
    {@render children()}
</main>

<footer class="text-center text-gray-400 text-xs my-6">
    <p class="mb-2">
        Coded by
        <a class="text-blue-400 hover:underline" href="https://www.jeremygautrais.fr/" target="_blank" rel="noopener">Jérémy</a>
    </p>
    <p>
        Source code on
        <a class="text-blue-400 hover:underline" href="https://github.com/jgautrais/aest/tree/main" target="_blank" rel="noreferrer">Github</a>
    </p>
</footer>
