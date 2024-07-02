<template>
  <div id="web-home">
    <div class="row">
      <div class="col-12">
        <div
          id="carouselExampleControls"
          class="carousel slide"
          data-ride="carousel"
        >
          <div class="carousel-inner">

            <div class="carousel-item">
              <router-link
                :to="{ name: 'website.how.to' }"
                active-class="active"
                exact
              >
                <img
                  class="d-block w-100"
                  src="/img/banner/banner1.png"
                  alt="First slide"
                />
              </router-link>
            </div>
            <div class="carousel-item">
              <template>
                <router-link
                  :to="{ name: 'website.promos', params: { discount: true } }"
                  active-class="active"
                  exact
                >
                  <img
                    class="d-block w-100"
                    src="/img/banner/promos-y-combos.png"
                    alt="Second slide"
                  />
                </router-link>
              </template>
            </div>
            <div class="carousel-item active">
              <template>
                <router-link
                  :to="{ name: 'website.shop' }"
                  active-class="active"
                  exact
                >
                  <img
                    class="d-block w-100"
                    src="/img/banner/banner-tienda.png"
                    alt="Second slide"
                  />
                </router-link>
              </template>
            </div>

            <div class="carousel-item">
              <img
                class="d-block w-100"
                src="/img/banner/banner4.png"
                alt="Third slide"
              />
            </div>
          </div>
          <a
            class="carousel-control-prev home-controls"
            href="#carouselExampleControls"
            role="button"
            data-slide="prev"
          >
            <i class="fas fa-chevron-left"></i>
          </a>
          <a
            class="carousel-control-next home-controls"
            href="#carouselExampleControls"
            role="button"
            data-slide="next"
          >
            <i class="fas fa-chevron-right"></i>
          </a>
        </div>
      </div>
    </div>
    <div class="highlighted-title">
      <section class="featured spad">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="section-title">
                <h2>Los productos más elegidos</h2>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <div id="highlighted" class="row" v-if="highlighted.length">
      <product
        v-for="(product, index) in highlighted"
        :key="index"
        :product="product"
      >
      </product>
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
</template>

<script>
import { mapGetters } from "vuex";
import { VueAgile } from "vue-agile";
import Product from "./partials/product";

export default {
  metaInfo() {
    return { title: this.$t("settings") };
  },

  mounted() {},

  data() {
    return {
      searchInput: "",
      searching: false,
      options: {
        dots: false
      }
    };
  },

  components: {
    agile: VueAgile,
    Product
  },

  computed: {
    ...mapGetters({
      highlighted: "product/highlighted",
      latest: "product/latest"
    })
  },

  created() {
    //
  },

  methods: {}
};
</script>
