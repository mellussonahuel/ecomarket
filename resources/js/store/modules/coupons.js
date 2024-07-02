import genericModule from './../resourceController'
import axios from 'axios'

const ResourceController = genericModule('coupons', {role: '',load : 'all' })


export const state = {
    ...ResourceController.state,
    coupons: [], // in base a current company
    pagination:{},
    page:1,
}
export const getters = {
    ...ResourceController.getters,
    coupons: state => state.coupons,
    coupon: state => state.coupon,
    pagination: state => state.pagination,
    page: state => state.page,
}
export const mutations = {
    ...ResourceController.mutations,
    setCoupons(state, coupons) {
        state.coupons = coupons 
    },

    setCoupon(state, coupon) {
        state.coupon = coupon 
    },
    
    setPagination(state,pagination){
        state.pagination = pagination
    },
   
    setPage(state,page){
        state.page = page 
    },
   
}
export const actions = {
    ...ResourceController.actions,

    async fetchCoupons ({commit, state}, opt) {
        try {
            commit('setPage',opt.pag)
            const {data} = await axios.get(`/api/coupons?page=${opt.pag}`)
            commit('setCoupons', data.data)
            commit('setPagination', data)
        } catch (e) {
            commit('setCoupons', {data: []})
        }
    },
}
