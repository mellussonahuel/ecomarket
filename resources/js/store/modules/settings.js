import Vue from "vue";

export const store = Vue.observable({
    showSidebar: false
});

export const state = {
    showNav:false,
    showSidebar:false,
}
export const getters = {
    showSidebar: state => state.showSidebar,
}
export const mutations = {
    toggleSidebar(state) {
        state.showSidebar = !state.showSidebar
    },
    closeSidebar(state){
        state.showSidebar = false
    }
    
}
export const actions = {
    
}
