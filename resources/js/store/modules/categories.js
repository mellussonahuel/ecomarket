import genericModule from './../resourceController'
import axios from 'axios'

const ResourceController = genericModule('categories', {role: ''})


export const state = {
    ...ResourceController.state,
    showNav:false,
    category: null,
    image:{},
    pagination:{},
    categories: [], // in base a current company
    page:1,
}
export const getters = {
    ...ResourceController.getters,
    categories: state => state.categories,
    category: state => state.category,
    showNav: state => state.showNav,
    image: state => state.category.image,
    pagination: state => state.pagination,
    page: state => state.page,
}
export const mutations = {
    ...ResourceController.mutations,
    
    setCategories(state, categories) {
        state.categories.length = 0
        state.categories = categories
    },
    setCategory(state, category) {
        state.category = category
    },
    setShowNav(state, value){
        state.showNav = value
    },
    setPagination(state,pagination){
        state.pagination = pagination
    },
    setPage(state,page){
        state.page = page
    }
}
export const actions = {
    ...ResourceController.actions,

    async fetchCategories ({commit, state}) {
        
        try {
            const {data} = await axios.get(`/api/categories/get-categories`)
            commit('setCategories', data)
        } catch (e) {
            commit('setCategories', {data: []})
        }
    },

    async fetchAll ({commit, state}, opt ) {
      
        try {
            const {data} = await axios.get(`/api/categories?page=${opt.pag}`)
            commit('setCategories', data.data)
            commit('setPagination', data)
            commit('setPage',opt.pag)
        } catch (e) {
            commit('setCategories', {data: []})
        }
    },

    /**
     * Get Promise data by slug
     * Executed when page loads
     * @param commit
     * @param state
     */
    async getCategoryData({commit, state}, slug) {
        try {
            const {data} = await axios.get(`/api/${state.resource}/slug/${slug}`)
 
            commit('setCategory', data)
        } catch (e) {
            state.category= null
        }
    },
}
