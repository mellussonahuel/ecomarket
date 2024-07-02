import genericModule from './../resourceController'
import axios from 'axios'

const ResourceController = genericModule('sales', {role: '',load : 'all' })


export const state = {
    ...ResourceController.state,
    sale: null,
    sales: [], // in base a current company
    pagination:{},
    page:1,
    unread:0,
    loading:false,
}
export const getters = {
    ...ResourceController.getters,
    sales: state => state.sales,
    sale: state => state.sale,
    pagination: state => state.pagination,
    page: state => state.page,
    unread: state => state.unread,
    loading: state => state.loading
    
}
export const mutations = {
    ...ResourceController.mutations,
    setSales(state, sales) {
        state.sales = sales 
    },
    setSale(state, sale) {
        state.sale = sale
    },
    setPagination(state,pagination){
        state.pagination = pagination
    },
   
    setPage(state,page){
        state.page = page 
    },
    setUnread(state,count){
        state.unread = count 
    },
    toggleLoading(state){
        state.loading = !state.loading
    }
   
}
export const actions = {
    ...ResourceController.actions,

    async fetchSales ({commit, state}, payload) {
        try {
            commit('toggleLoading')
            commit('setPage',payload.pag)
            const {data} = await axios.get(`/api/sales?page=${payload.pag}&term=${payload.term}`)
            commit('setSales', data.data)
            commit('setPagination', data)
        } catch (e) {
            commit('setSales', {data: []})
        }
        commit('toggleLoading')

    },
    async fetchByUser({commit,state}, payload){
        commit('toggleLoading')
        commit('setPage',payload.pag)
        const {data} = await axios.get(`/api/sales/by-user/${payload.user}?pag=${payload.pag}`)
        commit('setSales', data.data)
        commit('setPagination', data)
        commit('toggleLoading')
    },
    async fetchUnread({commit, state},){
        try {
            
            const {data} = await axios.get(`/api/sales/unread`)
            commit('setUnread', data)
        } catch (e) {
            commit('setUnread', 0)
        }
    }
}
