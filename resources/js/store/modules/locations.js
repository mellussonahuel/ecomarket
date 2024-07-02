import genericModule from './../resourceController'
import axios from 'axios'

const ResourceController = genericModule('location', {role: '',load : '' })


export const state = {
    ...ResourceController.state,
    locations: [], // in base a current company
    location:{},
    pagination:{},
    page:1,
    loading:false,
}
export const getters = {
    ...ResourceController.getters,
    locations: state => state.locations,
    location: state => state.location,
    pagination: state => state.pagination,
    page: state => state.page,
    loading: state => state.loading
}
export const mutations = {
    ...ResourceController.mutations,
    setLocations(state, locations) {
        state.locations = locations 
    },

    setLocation(state, location){
        state.location = location
    },
    
    setPagination(state,pagination){
        state.pagination = pagination
    },
   
    setPage(state,page){
        state.page = page 
    },

    setToggleLoading(state){
        state.loading = !state.loading 
    },
   
}
export const actions = {
    ...ResourceController.actions,

    async fetchLocations ({commit, state}, opt) {
        try {
            commit('setToggleLoading')
            commit('setPage',opt.pag)
            let active = opt.active ? true : false
            const {data} = await axios.get(`/api/locations?page=${opt.pag}&active=${active}`)
            commit('setLocations', data.data)
            commit('setPagination', data)
             commit('setToggleLoading')
        } catch (e) {
            commit('setLocations', {data: []})
             commit('setToggleLoading')
        }
    },
}
