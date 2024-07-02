import genericModule from './../resourceController'
import axios from 'axios'

const ResourceController = genericModule('users', {role: '',load : 'all' })


export const state = {
    ...ResourceController.state,
    user: null,
    users: [], // in base a current company
    pagination:{},
    page:1,
    loading:false,
    filters:[],
}
export const getters = {
    ...ResourceController.getters,
    users: state => state.users,
    user: state => state.user,
    pagination: state => state.pagination,
    page: state => state.page,
    loading: state => state.loading,
    filters: state => state.filters
}
export const mutations = {
    ...ResourceController.mutations,
    setUsers(state, users) {

        state.users = users

    },
    setUser(state, user) {

        state.user = user
       
    },
    setPagination(state,pagination){
        state.pagination = pagination
    },
    setPage(state,page){
        state.page = page 
    },
    removeFilter(state , filter){
        let index = state.filters.findIndex((f => f.slug == filter.slug));
        state.filters.splice(index, 1);
    },
    setFilter(state, filter){

        let index = state.filters.findIndex((f => f.slug == filter.slug));   
    
        if (index > -1) {
            if(filter.type === 'term'){   
                state.filters.splice(index, 1);
                state.filters.push(filter);
                //Vue.set(state.filters, index, filter)
            }else{
                state.filters.splice(index, 1);
            }
            
        } else {

            state.filters.push(filter);
        }
        
    }
}
export const actions = {
    ...ResourceController.actions,

    async fetchUsers ({commit, state},payload) {
        console.log(payload)
        try {
           
            commit('setPage',payload.pag)
            //var formData = new FormData();     
           
            const {data} = await axios.get(`/api/users?page=${payload.pag}&term=${payload.term}`)
            commit('setUsers', data.data)
            commit('setPagination', data)
        } catch (e) {
            commit('setUsers', {data: []})
        }
    },

    /**
     * Get Promise data by slug
     * Executed when page loads
     * @param commit
     * @param state
     * @param slug
     * @returns {Promise.<void>}
     */
    async getUserData({commit, state}, payload) {
        try {
            const {data} = await axios.get(`/api/${state.resource}/${payload.id}`)
            commit('setUser', data)
           
        } catch (e) {
            state.user= null
        }
    },

    async showUser({commit, state}, id){
        try {
            commit('setUser', {})
            const { data } = await axios.get(`/api/${state.resource}/${id}`)
            commit('setUser', data)
        } catch (e) {
            state.user = {}
        }
    },

    async filter({commit, state}, payload){
        try {
            commit('setUsers', [])
            state.loading = true
            var form_data = new FormData();
            
            form_data.append('page',payload.pag ? payload.pag : 1)         
            form_data.append('term' , payload.term)
  
            
            const { data } = await axios.post(`/api/${state.resource}/filter`, form_data)
            
        
            commit('setPage',payload.pag)
    
            commit('setUsers', data.data)
            commit('setPagination', data)
            state.loading = false
        } catch (e) {
            commit('setUsers', [])
            state.loading = false
        }
    }

}
