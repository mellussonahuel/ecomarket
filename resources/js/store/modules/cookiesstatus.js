import Cookies from "js-cookie";

export const state = {
    status: Cookies.get('accepted-cookie') === true ? true : false // in base a current company
}

export const getters = {

    status: state => state.status,
   
}
export const mutations = {
    setCookies(state, status) {
        state.status = status 
    },
}
export const actions = {
   
}