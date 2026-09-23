import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
// import * as bootstrap from 'bootstrap';
window.Alpine = Alpine;
Alpine.plugin(focus);
Alpine.start();

window.registerJSON = function(hash, data, prop=null){
    let fc = new Date();
    let update = [fc.getFullYear(), (fc.getMonth() + 1).toString().padStart(2, '0'), fc.getDate().toString().padStart(2, '0')].join('-');
    if(prop != null){
        let raw = localStorage.getItem(hash);
        if(raw != null){
            let tmp = JSON.parse(raw);
            tmp.data[prop] = data;
            localStorage.setItem(hash, JSON.stringify(tmp));
        }else{
            let datajs = {};
            datajs[prop] = data;
            localStorage.setItem(hash, JSON.stringify({'data': datajs, 'update': update}));
        }
    }else{
        localStorage.setItem(hash, JSON.stringify({'data': data, 'update': update}));
    }
    // this.tokens[kid] = this.lc_token;
}

window.getRegisterJSON = function(hash, prop=null){
    let tmp = localStorage.getItem(hash);
    if(tmp != null){
        let rs = JSON.parse(tmp);
        if(prop == null){
            return rs.data;
        }else{
            return (rs.data == undefined)? null: rs.data[prop];
        }
        // return (prop == null)? rs.data: rs.data[prop];
    }
    return null;
}

window.unregisterJSON = function(hash){
    localStorage.removeItem(hash);
}


import Vue from 'vue';
window.Vue = Vue;
window.Vue.prototype.$eventBus = new Vue();


import dashboard from './components/dashboard-home.vue';
import movimientos from './components/movimientos.vue';
import setting from './components/setting.vue';
import amchartbarra from './components/amcharts/bar.vue';
import amchartbarra_vertical from './components/amcharts/bar-vertical.vue';
import amchartlinea from './components/amcharts/line.vue';
import amchartpie from './components/amcharts/pie.vue';
// import asistencia from './components/asistencia.vue';
import rifa_client from './components/rifa_client.vue';
import asistencia_admin from './components/asistencia_admin.vue';

Vue.component('dashboard', dashboard);
Vue.component('movimientos', movimientos);
Vue.component('configuracion', setting);
Vue.component('amchart-barra', amchartbarra);
Vue.component('amchart-barra-vertical', amchartbarra_vertical);
Vue.component('amchart-linea', amchartlinea);
Vue.component('amchart-torta',amchartpie);

// Vue.component('asistencia', asistencia);
// Vue.component('asistencia_admin', asistencia_admin);

Vue.component('rifa_client', rifa_client);

// window.Vue = require('vue').default;
// window.Vue.prototype.$eventBus = new Vue();
// //graficos componentes padres
// Vue.component('mainapp', require('./components/MainApp.vue').default);

const app = new Vue({
    el:'#app'
});
