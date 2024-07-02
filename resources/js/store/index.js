import Vue from 'vue'
import Vuex from 'vuex'
import Cookies from 'js-cookie';
import createPersistedState from 'vuex-persistedstate';


Vue.use(Vuex)



// Load store modules dynamically.
const requireContext = require.context('./modules', false, /.*\.js$/)

const modules = requireContext.keys()
  .map(file =>
    [file.replace(/(^.\/)|(\.js$)/g, ''), requireContext(file)]
  )
  .reduce((modules, [name, module]) => {
    if (module.namespaced === undefined) {
      module.namespaced = true
    }

    return { ...modules, [name]: module }
  }, {})

  // const dataState = createPersistedState({
  //   paths: ['cart']
  // })

export default new Vuex.Store({
  modules,
  paths:['cart','product.filters','cookiesstatus'],
  plugins: [createPersistedState()],
})


// storage: {
//   getItem: key => Cookies.get(key),
//   setItem: (key, value) => Cookies.set(key, value, { expires: 1, secure: false }),
//   removeItem: key => Cookies.remove(key)
// }