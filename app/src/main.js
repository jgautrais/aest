import { mount } from 'svelte';
import './app.css';
import Home from './pages/Home.svelte';
import About from './pages/About.svelte';
import NotFound from './pages/NotFound.svelte';

const pages = { Home, About, NotFound };
const page = document.body.dataset.page;

mount(pages[page], { target: document.body });
