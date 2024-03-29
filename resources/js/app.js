
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

require('./components/bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

var accountingJs = require('accounting');
VueMoney = {
    install: function (Vue, accountingJs) {
        Vue.money = function() {
            return accountingJs;
        }
    }
};
Vue.use(VueMoney, accountingJs);
Vue.filter('currency', function(val, dec){
    return accountingJs.formatNumber(val, dec)
});

// add the ziggy route method to Vue
Vue.prototype.route = route;

const app = new Vue({
    el: '#app'
});
