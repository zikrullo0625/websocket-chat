import api from '../api.js';

export default {
    install(app) {
        app.config.globalProperties.api = api;
    }
};
