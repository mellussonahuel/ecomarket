<template>
  <transition name="fade" mode="out-in">
    <router-view />
  </transition>
</template>
<script>
import { mapGetters } from "vuex";
import Swal from "sweetalert2";
export default {
  layout: "AppLayout",
  scrollToTop: true,

  metaInfo() {
    return { title: "Inicio" };
  },

  mounted() {},

  created() {
    if (!window.auth_user && this.user) this.$store.commit("auth/LOGOUT");

    this.$store.commit("product/cleanFilters");

    if (!this.categories.length) {
      this.fetchCategories();
    }
   
    this.fetchHighlighted();
    this.fetchLatest();
    

     if (!this.locations.length) {
      this.fetchLocations();
    }
  },

  data() {
    return {};
  },

  computed: {
    ...mapGetters({
      user: "auth/user",
      highlighted: "product/highlighted",
      latest: "product/latest",
      categories: "categories/categories",
      locations: "locations/locations"
    })
  },

  methods: {
    async fetchCategories() {
      await this.$store.dispatch("categories/fetchCategories");
    },
    async fetchHighlighted() {
      await this.$store.dispatch("product/fetchHighlighted");
    },
    async fetchLatest() {
      await this.$store.dispatch("product/getLatest");
    },
    async fetchLocations(pag = 1) {
      await this.$store.dispatch("locations/fetchLocations", {
        pag: pag,
        active: true
      });
    }
  }
};
</script>
