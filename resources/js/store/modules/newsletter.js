import genericModule from "./../resourceController";
import axios from "axios";

const ResourceController = genericModule("newsletter", {
  role: "",
  load: "all"
});

export const state = {
  ...ResourceController.state,
  newsletters: [], // in base a current company
  pagination: null,
  loading: false,
  page: 1
};
export const getters = {
  ...ResourceController.getters,
  newsletters: state => state.newsletters,
  pagination: state => state.pagination,
  page: state => state.page,
  loading: state => state.loading
};
export const mutations = {
  ...ResourceController.mutations,
  setNewsletters(state, newsletters) {
    state.newsletters = newsletters;
  },

  toggleLoading(state) {
    state.loading = !state.loading;
  },

  setPagination(state, pagination) {
    state.pagination = pagination;
  },

  setPage(state, page) {
    state.page = page;
  }
};
export const actions = {
  ...ResourceController.actions,

  async fetchNewsletters({ commit, state }, opt) {
    try {
      commit("setPage", opt.pag);
      commit("toggleLoading");
      const { data } = await axios.get(`/api/newsletter?page=${opt.pag}`);
      commit("setNewsletters", data.data);
      commit("setPagination", data);
    } catch (e) {
      commit("setNewsletters", { data: [] });
    }
    commit("toggleLoading");
  }
};
