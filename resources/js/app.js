/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import moment from 'moment';////

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('feeder-create', require('./components/feeder/FeederCreate.vue').default);
Vue.component('machine-create', require('./components/create/MachineCreate.vue').default);
Vue.component('department-create', require('./components/create/DepartmentCreate.vue').default);
Vue.component('shift-create', require('./components/create/ShiftCreate.vue').default);
Vue.component('productname-create', require('./components/create/ProductnameCreate.vue').default);
Vue.component('product-create', require('./components/create/ProductCreate.vue').default);
Vue.component('order-create', require('./components/order/OrderCreate.vue').default);
Vue.component('part-create', require('./components/part/PartCreate.vue').default);
Vue.component('user-index', require('./components/user/UserIndex.vue').default);

Vue.filter('myDate',function(created){ //////////////
	return moment(created).format('DD-MM-YYYY, H:mm:ss'); /////////////
}); //////////////

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',

    methods: { //for print out function////
        printme() {
            window.print();
        }
    }
});
