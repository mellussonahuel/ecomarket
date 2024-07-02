import axios from 'axios'
import Cookies from 'js-cookie'
import * as types from '../mutation-types'

// state
export const state = {
  user: null,
  token: Cookies.get('token'),
  favorites:[],
}

// getters
export const getters = {
  user: state => state.user,
  token: state => state.token,
  check: state => state.user !== null,
  favorites: state => state.favorites 
}

// mutations
export const mutations = {
  [types.SAVE_TOKEN] (state, { token, remember }) {
    if(token.length)
    window.auth_user = true
    
    state.token = token
    Cookies.set('token', token, { expires: remember ? 365 : null })
  },

  [types.FETCH_USER_SUCCESS] (state, { user }) {
    window.auth_user = true
    state.user = user
  },

  [types.FETCH_USER_FAILURE] (state) {
    window.auth_user = false
    state.token = null
    Cookies.remove('token')
  },

  [types.LOGOUT] (state) {
    window.auth_user = false
    state.user = null
    state.token = null

    Cookies.remove('token') 
  },

  [types.UPDATE_USER] (state, { user }) {
    state.user = user
  },

  removeFavorite(state, id){

    let index = state.favorites.findIndex((p => p == id));  
    if (index > -1) {
      state.favorites.splice(index, 1);
    }

  },

  setFavorite(state, id){
    
        let index = state.favorites.findIndex((p => p == id));   
    
        if (!index > -1) {
          state.favorites.push(id)
        } 
        else 
          state.favorites.splice(index, 1);
          
        }
      
}

// actions
export const actions = {
  saveToken ({ commit, dispatch }, payload) {
    commit(types.SAVE_TOKEN, payload)
  },


  async fetchUser ({ commit }) {
    try {
      const { data } = await axios.get('/api/user')

      commit(types.FETCH_USER_SUCCESS, { user: data })
     
      data.products.map( d => commit('setFavorite', d.id ))

    } catch (e) {
      commit(types.FETCH_USER_FAILURE)
    }
  },

  updateUser ({ commit }, payload) {
    commit(types.UPDATE_USER, payload)
  },

  async logout ({ commit,state }) {
    try {
      await axios.post('/api/logout')
    } catch (e) { }
    
    state.favorites = []
    commit(types.LOGOUT)
  },

  async fetchOauthUrl (ctx, { provider }) {
    const { data } = await axios.post(`/api/oauth/${provider}`)

    return data.url
  },

  async setFavorites ({commit, state}) {
    
    if(state.user == null)
      return;

    try {
      var formData = new FormData()
      
      formData.append('favorites',state.favorites)
      const { data } = await axios.post('/api/set-favorites', formData)
      
      state.favorites = []
      data.map( d => commit('setFavorite', d ))
      
    } catch (e) {
      commit('setFavorite',[])
    }
  },
}
