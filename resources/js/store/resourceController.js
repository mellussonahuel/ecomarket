import axios from 'axios'

export default function (resource, newItemProperties) {
    return {
        namespaced: true,

        state: {
            resource: resource,
            list: [],
            meta: {
                current_page: 1,
                per_page: 10,
                next_page: null,
                previous_page: null,
                last_page: null,
                first_page: null,
                total_rows: null,
                sort: 'created_at',
                sort_type: 'desc',
                // load relationships. Eg. all or 'user.survey,survey.provider.organisation'
                // have to add your relationships on laravel model in loadable property
                load: 'all'
            }
        },
        getters: {
            list: state => state.list,
            meta: state => state.meta
        },
        mutations: {
            setItems(state, {items}) {
                state.list = items.data
                state.meta.current_page = items.current_page
                state.meta.total_rows = items.total
                state.meta.per_page = items.per_page
                state.meta.last_page = items.last_page
                state.meta.first_page = 1
                if (items.next_page_url != null) {
                    state.meta.next_page = items.current_page + 1
                } else {
                    state.meta.next_page = false
                }
                if (items.prev_page_url != null) {
                    state.meta.previous_page = items.current_page - 1
                } else {
                    state.meta.previous_page = false
                }
            },
            setPerPage(state, perPage) {
                state.meta.per_page = perPage
            },
            setSort(state, sortField) {
                state.meta.sort = sortField
            },
            setSortType(state, sortType) {
                state.meta.sort_type = sortType
            }
        },
        actions: {
            async fetch({commit, state}, page = false) {
                try {
                    let params
                    if (page) {
                        params = {
                            page: page,
                            perPage: state.meta.per_page,
                            load: state.meta.load,
                            sort: state.meta.sort,
                            sortType: state.meta.sort_type
                        }
                    } else {
                        params = {
                            load: 'all',
                            perPage: false
                        }
                    }
                    let query = Object.keys(params).map(key => key + '=' + params[key]).join('&')
                    const {data} = await axios.get(`/api/${state.resource}?${query}`)
                    commit('setItems', {items: data})
                } catch (e) {
                    commit('setItems', {items: {data: []}})
                }
            },

            async search({commit, state}, search) {
                try {
                    let params = {
                        page: 1,
                        perPage: state.meta.per_page,
                        load: state.meta.load,
                        sort: state.meta.sort,
                        sortType: state.meta.sort_type,
                        search: search
                    }
                    let query = Object.keys(params).map(key => key + '=' + params[key]).join('&')
                    const {data} = await axios.get(`/api/${state.resource}?${query}`)
                    commit('setItems', {items: data})
                } catch (e) {
                    commit('setItems', {items: {data: []}})
                }
            },
            async destroy({commit, dispatch, state}, id) {
                try {
                    // const { data } = await axios.delete('/api/organisations/' + id)
                    await axios.delete(`/api/${state.resource}/${id}`)
                    dispatch('fetch', state.meta.current_page)
                    this._vm.notifySuccess('User has been deleted successfully')
                } catch (e) {
                    this._vm.notifyError('Ups.. Sorry we could not delete the resource in this moment. Please try again later.')
                    console.error(e)
                }
            },

            rowsPerPage({commit, dispatch}, perPage) {
                commit('setPerPage', perPage)
                dispatch('fetch', 1)
            },
            prevPage({dispatch, state}) {
                if (state.meta.previous_page) {
                    let prev = state.meta.current_page - 1
                    dispatch('fetch', prev)
                }
            },
            nextPage({dispatch, state}) {
                if (state.meta.next_page) {
                    let next = state.meta.current_page + 1
                    dispatch('fetch', next)
                }
            },

            sort({dispatch, commit, state}, {sortField, sortType}) {
                commit('setSort', sortField)
                commit('setSortType', sortType)
                dispatch('fetch', state.meta.current_page)
            }
        }
    }
}
