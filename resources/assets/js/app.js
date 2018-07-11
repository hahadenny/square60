
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.use(require('vue-resource'));

require('./components/filters/filters');
require('./components/result/result');
require('./components/save/save');
require('./components/modal/modal');
require('./components/send/send');
require('./components/search/search');
require('./components/maps/maps');
require('./components/sendresults/sendresults');
require('./components/deleteimage/deleteimage');
require('./components/deletesellimages/deletesellimages');
require('./components/deletebuildimages/deletebuildimages');
require('./components/deletephoto/deletephoto');
require('./components/deleteopenhouse/deleteopenhouse');

window.Event = new Vue();

// Vue.component('example', require('./components/Example.vue'));

if (document.getElementById('app') !== null) {
    var app = new Vue({
        el: '#app',
        data: {
            showModal: false,
            showModalListing: false,
            saveModal: false,

            estateMap: {
                id: 0,
                type: 'result'
            }
        },

        methods: {
            changeEstateId: function changeEstateId(result, key) {
                this.estateMap.id = key;
                this.estateMap.type = result;
            }
        }
    });
    console.log('start app1');
}

if (document.getElementById('app2') !== null) {
    var app = new Vue({
        el: '#app2',
        data: {
            showModal: false,
            showModalListing: false,
            saveModal: false,

            estateMap: {
                id: 0,
                type: 'result'
            }
        },

        methods: {
            changeEstateId: function changeEstateId(result, key) {
                this.estateMap.id = key;
                this.estateMap.type = result;
            }
        }
    });
    console.log('start app2');
}


