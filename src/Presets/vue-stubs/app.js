import Vue from 'vue';
import VueRouter from 'vue-router';

import router from './router';
import store from './store'

import Base from './Base'

Vue.config.productionTip = false

import createApp from '@shopify/app-bridge';
import getSessionToken from '@shopify/app-bridge-utils'
import { Redirect, History, Loading } from '@shopify/app-bridge/actions';

Vue.use(VueRouter);
Vue.router = router;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('connect-github', require('./components/ConnectGithub.vue').default);
// Vue.component('github-organisation', require('./components/GithubOrganisation.vue').default);

// const token = document.getElementById('token').innerText
const key = document.getElementById('shop_key').innerText
const name = document.getElementById('shop_name').innerText

store.commit('app/shop', name);

require('./bootstrap');

try {
  console.log('this will only work when we are out of iframe')
  const url = window.top.location.href
  console.log('this should not happen')
  console.log(key, name)
  const redirect = `https://${name}/admin/apps/${key}`
  window.top.location.href = redirect
} catch (e) {
  console.log('this should fail if app is embedded')
  console.log(e)
}


// const app = new Vue({
    // el: '#app',
    // router,
    // component: { template: '<router-view></router-view>' }
// })

const start = () => {
  // console.log('starting')
  /* eslint-disable no-new */
  const app = new Vue({
    el: '#app',
    router,
    store,
    // render: function (createElement) {
      // return createElement('router-view')
    // },
    // component: require('./Base.vue').default,
    template: '<Base/>',
    components: {
      Base
    }
  })

  const shopifyApp = createApp({
    apiKey: key,
    shopOrigin: name,
    forceRedirect: true,
  });

  shopifyApp.subscribe(Redirect.ActionType.APP, function(redirectData) {
    console.log(redirectData.path) // For example, '/settings'
    console.log(app.$router, router)
    app.$router.push(redirectData.path)
  });

  const history = History.create(shopifyApp);
  history.dispatch(History.Action.PUSH, `${window.location.pathname}`);

  const loading = Loading.create(shopifyApp);

  // shopifyApp.subscribe(Loading.ActionType.START, () => {
    // console.log('START')
  // })

  app.loading = loading
  app.shopifyApp = shopifyApp
}

window.onload = () => {
  start()
}
