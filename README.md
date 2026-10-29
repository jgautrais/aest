# Aest
Area estimation tool.

## Context
Visual assessment of disease is a key aspect to many phytopathological research studies. Estimation of disease severity is a basic skill required of people working on plant pathology studies. Several applications have been developed to train people to visual assessment of disease.

This project is an attempt to reproduce such a training tool.

Play it on https://aest.jeremygautrais.fr

## Stack
Static site built with [Svelte 5](https://svelte.dev), [Tailwind CSS 4](https://tailwindcss.com) and [Vite](https://vite.dev), served by nginx.

## Development
With Docker (Traefik + Vite dev server with hot reload, on http://aest.localhost):

```bash
docker compose up -d
```

Without Docker:

```bash
cd app
npm install
npm run dev
```

Tests: `npm test` (in `app/`).
