import genericModule from './../resourceController'
import axios from 'axios'

const ResourceController = genericModule('products', {role: '',load : 'all' })


export const state = {
    ...ResourceController.state,
    product: null,
    products: [], // in base a current company
    pagination:{},
    images:[],
    page:1,
    highlighted:[],
    related:[],
    latest:[],
    loading:false,
    filters:[],
}
export const getters = {
    ...ResourceController.getters,
    products: state => state.products,
    product: state => state.product,
    pagination: state => state.pagination,
    images: state => state.images,
    page: state => state.page,
    related: state => state.related,
    highlighted: state => state.highlighted,
    latest: state => state.latest,
    loading: state => state.loading,
    filters: state => state.filters
}
export const mutations = {
    ...ResourceController.mutations,
    setProducts(state, products) {
        state.products = products 
    },
    setProduct(state, product) {
 
        state.product = product
        if(product && product.images){
            state.images = product.images
        }else{
            state.images = [] 
        }
    },
    setPagination(state,pagination){
        state.pagination = pagination
    },
    setImages(state,images){
        state.images.length = 0
        
        images.map(i => {
            state.images.push(i)
        })
    },
    setPage(state,page){
        state.page = page 
    },
    setHighlightedProducts(state,highlighted){
        state.highlighted = highlighted
    },
    setRelated(state,related){
        state.related = related 
    },
    setLatest(state,latest){
        state.latest = latest
    },
    toggleLoading(state){
        state.loading = !state.loading
    },
    removeFilter(state , filter){
        let index = state.filters.findIndex((f => f.slug == filter.slug));
        state.filters.splice(index, 1);
    },
    cleanFilters(state){
        state.filter.length = 0
    },
    setFilter(state, filter){
        
        state.filters.length = 0
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
        
    },
    cleanFilters(state){
        state.filters = []
    }
}
export const actions = {
    ...ResourceController.actions,

    async fetchProducts ({commit, state}, opt) {
        try {
            commit('setPage',opt.pag)
            var formData = new FormData();
            
            if(opt.pag){
                formData.append('page',opt.pag)
            }

            if(opt.term){
                formData.append('term',opt.term)
            }

            const {data} = await axios.post(`/api/products/get-all`,formData)
            commit('setProducts', data.data)
            commit('setPagination', data)
        } catch (e) {
            commit('setProducts', {data: []})
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
    async getProductData({commit, state}, payload) {
        
        try {
            commit('toggleLoading')
            const {data} = await axios.get(`/api/${state.resource}/${payload.id}`,{load:'images'})
            commit('setProduct', data)
            if(data.images){
                commit('setImages', data.images)
            }
            commit('toggleLoading')
           
        } catch (e) {
            state.product= null
            state.images = []
            commit('toggleLoading')
        }
    },

    async fetchHighlighted({commit, state}){
        try {
            var form_data = new FormData();
            form_data.append('highlighted',true)
            form_data.append('perPage',8)

            const { data } = await axios.post(`/api/${state.resource}/filter`, form_data)
            
            commit('setHighlightedProducts', data.data)
            
        } catch (e) {
            state.product = null
        }
    },

    async getLatest({commit, state}){
        try {
           
            const { data } = await axios.get(`/api/${state.resource}/latest`)
            
            commit('setLatest', data)
            
        } catch (e) {
            state.latest = []
        }
    },

    async showProduct({commit, state}, slug){
        try {
            commit('toggleLoading')
            
            commit('setProduct', {})
            const { data } = await axios.get(`/api/${state.resource}/slug/${slug}`)
            commit('setProduct', data.data)
            commit('setRelated', data.related)
            commit('toggleLoading')
        } catch (e) {
            state.product = {}
            commit('toggleLoading')
        }
    },
    da(){

    },

    async filter({commit, state}, payload){
        try {
            commit('setProducts', [])
            state.loading = true
            var form_data = new FormData();
            
            form_data.append('page',payload.pag ? payload.pag : 1)         
            form_data.append('filters' , JSON.stringify(state.filters))
            
            
            const { data } = await axios.post(`/api/${state.resource}/filter`, form_data)
            
        
            commit('setPage',payload.pag)
    
            commit('setProducts', data.data)
            commit('setPagination', data)
            state.loading = false
        } catch (e) {
            commit('setProducts', [])
            state.loading = false
        }
    }

}
