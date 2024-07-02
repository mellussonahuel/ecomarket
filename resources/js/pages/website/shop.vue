<template>
  <div id="shop">
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <div class="breadcrumb__text"></div>
          </div>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Product Section Begin -->
    <section id="product-list" class="product spad">
      <div class="container">
        <div class="row">
          <sidebar-filter></sidebar-filter>

          <div class="col-lg-9 col-md-7">
            <div class="row">
              <div
                class="col-12 d-block d-sm-none "
                v-if="filters && filters.length"
              >
                <h2>Filtros</h2>
                <transition name="fade" mode="out-in">
                  <div class="filters" v-if="filters">
                    <span
                      v-for="(filter, index) in filters"
                      :key="index"
                      @click="removeFilter(filter)"
                    >
                      {{ filter.label }}
                      <i class="fad fa-times"></i>
                    </span>
                  </div>
                </transition>
              </div>
            </div>
            <div class="product__discount">
              <div class="section-title product__discount__title">
                <h2>Productos</h2>
              </div>
              <transition-group name="fade" mode="out-in">
                <div key="1" class="row product-loader" v-if="loading">
                  <div class="col-12 text-center">
                    <i class="fad fa-spinner-third fa-spin"></i>
                    <h3>Cargando, aguardá un instante</h3>
                  </div>
                </div>

                <div
                  key="2"
                  class="row mt-5"
                  v-if="products && !products.length && !loading"
                >
                  <div class="col-12 text-center">
                    <h3 class="mt-5">No se han encontrado productos.</h3>
                  </div>
                </div>
                <!-- </transition> -->
                <!-- <transition name="fade" mode="out-in"> -->
                <div key="3" class="row" v-if="products && !loading">
                  <product
                    v-for="(product, index) in products"
                    :key="index"
                    :product="product"
                  >
                  </product>
                </div>
              </transition-group>
            </div>

            <div class="product__pagination" v-if="products">
              <paginator
                :limit="3"
                :data="paginationData"
                @pagination-change-page="changePage"
              ></paginator>
            </div>
          </div>
        </div>
        <div class="latest-title">
            <section class="featured spad">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title">
                                <h2>Novedades que te van a gustar</h2>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="latest row" v-if="latest.length">
          <product
            v-for="(product, index) in latest"
            :key="index"
            :product="product"
          >
          </product>
        </div>
      </div>
    </section>
    <!-- Product Section End -->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Form from "vform";
import axios from "axios";
import Swal from "sweetalert2";
import SidebarFilter from "./partials/sidebar-filter";
import Product from "./partials/product";
import Paginator from "laravel-vue-pagination";
import { gsap } from "gsap";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";

export default {
  scrollToTop: true,
  metaInfo() {
    return { title: "Productos" };
  },

  components: {
    SidebarFilter,
    Product,
    Paginator
  },

  mounted() {},

  data() {
    return {
      parent_category: null,
      sale: false
    };
  },

  computed: {
    ...mapGetters({
      products: "product/products",
      filters: "product/filters",
      paginationData: "product/pagination",
      category: "categories/category",
      loading: "product/loading",
      latest: "product/latest"
    }),
    getBannerImage() {
      return "/img/tienda.png";
    }
  },
  created() {
    
    var self = this;
    // self.cleanFilters();
    this.parent_category = this.$router.currentRoute.params.parent_id;

    if (this.$router.currentRoute.name == "website.promos") {
      if (
        this.isUndefined(
          this.filters.find(filter => filter.slug === "sale-discount")
        )
      ) {
        this.$store.commit("product/setFilter", {
          type: "sale",
          label: "Combos y promos",
          slug: "sale-discount",
          id: -1,
          parent: null
        });
      }
      this.scrollToCustom();
    }

    if (this.$router.currentRoute.name == "website.combos") {
      if (
        this.isUndefined(
          this.filters.find(filter => filter.slug === "sale-combos")
        )
      ) {
        this.$store.commit("product/setFilter", {
          type: "sale-combo",
          label: "Combos",
          slug: "sale-combos",
          id: -1,
          parent: null
        });
      }
    }
 
     this.getProducts(this.$router.currentRoute.params.page || 1);

    if (this.$router.currentRoute.params.scroll) {
      this.scrollToCustom(this.$router.currentRoute.params.scrollTo);
    }
  },
  beforeRouteUpdate(to, from, next) {
    this.getProducts(to.params.page);
    next();
  },
  methods: {
    async cleanFilters() {
      await this.$store.commit("product/cleanFilters");
    },
    changePage(pag = 1){
      this.$store.commit('product/setPage',pag)
       this.$router.push({
        'name': 'website.shop',
        'params': {page:pag}
      });
    },
    async getProducts(pag) {
      // let pag = this.$router.currentRoute.params.page || 1
      await this.$store.dispatch("product/filter", { pag: pag });
      this.scrollToCustom();
    },
    scrollToCustom(selector = "product-list") {
      let wait = 0;

      gsap.registerPlugin(ScrollToPlugin);
      gsap.to(window, {
        duration: 1,
        scrollTo: { y: "#" + selector, offsetY: 50 },
        delay: wait
      });
     
    },

    async removeFilter(filter) {
      var params = {}
      this.$store.commit("product/cleanFilters", filter);
      params.pag = 1
      await this.$store.dispatch('product/filter', params)
    }
  }
};
</script>
<style lang="scss" scoped>
.invalid-feedback {
  position: absolute;
  bottom: 5px;
}
</style>
<style lang="scss"></style>
