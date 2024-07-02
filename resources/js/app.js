import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'
import Helpers from './mixins/helpers'

import '~/plugins'
import '~/components'


Vue.config.productionTip = false
Vue.mixin(Helpers)

window.eventBus = new Vue();
window.Event = new class {
  constructor() {
      this.vue = new Vue();
  }

  fire(event, data = null) {
      this.vue.$emit(event, data);
  }

  listen(event, callback) {
      this.vue.$on(event, callback);
  }
};

/* eslint-disable no-new */
new Vue({
  i18n,
  store,
  router,
  ...App,
})
