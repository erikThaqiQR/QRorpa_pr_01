

require('./bootstrap');



require('./owljs01');



require('./admin03');
require('./admin04');
require('./admin06');
require('./datatables');
require('./jqueryui');

window.Vue = require('vue');
let axios = require('axios');

Vue.component('example-component', require('./components/ExampleComponent.vue'));

Vue.component('Recomendet-Pro', require('./components/RecomendetPro.vue'));

const app = new Vue({
    el: '#app'
});



