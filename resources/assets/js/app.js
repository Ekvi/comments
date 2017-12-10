import VueResource from 'vue-resource';

require('./bootstrap');

window.Vue = require('vue');

export const eventBus = new Vue();

Vue.use(VueResource);

Vue.http.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;

Vue.component('comments-panel', require('./components/CommentsPanel.vue'));

const app = new Vue({
    el: '#app'
});
