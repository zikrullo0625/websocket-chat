import { createRouter, createWebHistory } from 'vue-router';

import Home from '../js/components/Home.vue'
import Auth from "./components/Auth.vue";
import Chat from "./components/Chat.vue";

const routes = [
    { path: '/', name: 'home', component: Home },
    { path: '/auth', name: 'auth', component: Auth },
    { path: '/chat/:id', name: 'chat', component: Chat },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
