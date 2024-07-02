import axios from "axios";
import createPersistedState from "vuex-persistedstate";
import Cookies from "js-cookie";

export const state = {
  cart: [],
  total: 0,
  subtotal: 0,
  cartCount: 0,
  item: {
    variant: {},
    product: {}
  }
};
// cart: Cookies.get('cart') ? JSON.parse(Cookies.get('cart')) : [] ,
// total: Cookies.get('cart') ?  JSON.parse(Cookies.get('cart')).reduce((a, b) => a + (b['total'] || 0), 0) : 0,
// cartCount: Cookies.get('cart') ? JSON.parse(Cookies.get('cart')).length : 0 ,

export const getters = {
  cart: state => state.cart,
  total: state => {
    if (state.cart.length > 0) {
      return state.cart
        .map(item => item.total)
        .reduce((total, amount) => total + amount);
    } else {
      return 0;
    }
  },
  count: state => state.cartCount
};
export const mutations = {
  addItem(state, item) {
    let index = state.cart.findIndex(it => it.id == item.id);

    // if product exist , update qty
    if (index > -1) {
      let cart = [...state.cart];
      state.cart.length = 0;
      cart[index] = item;
      state.cart = [...cart];
    } else {
      state.cart.push(item);
      state.cartCount++;
    }
  },
  removeItem(state, id) {
    let cart = [...state.cart];
    state.cart.length = 0;
    state.cart = cart.filter(it => it.id !== id)
    state.cartCount--;
  },
  cleanCart(state) {
    state.cart = [];
    state.cartCount = 0;
    state.total = 0;
  }
};
export const actions = {
  async fetchProducts({ commit, state }, opt) {
    try {
      commit("setPage", opt.pag);
      const { data } = await axios.get(`/api/products?page=${opt.pag}`);
      commit("setProducts", data.data);
      commit("setPagination", data);
    } catch (e) {
      commit("setProducts", { data: [] });
    }
  },
  getCartTotal(state) {
    var total = 0;
    total = state.cart.map(function(v) {
      if (v.product.sale_type === "Unidad") {
        total = total + v.product.price * v.qty;
      } else {
        total = total + v.variant.price * v.qty;
      }
    });
  }
};
