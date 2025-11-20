import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import apiPlugin from "./plugins/apiPlugin.js";
import './echo.js';

const app = createApp(App);

app.use(router);
app.use(apiPlugin);

app.mount('#app');
