<template>
  <div class="col-lg-3 col-md-5">
    <div class="sidebar">
      <div id="sidebar-categories" class="sidebar__item">
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
        <categories></categories>
      </div>
      <div class="sidebar__item">
        <div class="latest-product__text">
          <h4>Nuestro productos destacados</h4>
          <div class="latest-product__slider owl-carousel">
            <div class="latest-prdouct__slider__item">
              <latest
                v-for="(product, index) in highlighted"
                :key="index"
                :product="product"
              >
              </latest>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters } from "vuex";
import Latest from "../partials/latest";
import Categories from "../partials/categories";
import {gsap} from 'gsap';
import {ScrollToPlugin} from 'gsap/ScrollToPlugin' 

export default {
  created() {
    if (!this.latest.length) {
      this.getLatest();
    }
  },

  data() {
    return {};
  },

  components: {
    Latest,
    Categories
  },

  computed: {
    ...mapGetters({
      highlighted: "product/highlighted",
      latest: "product/latest",
      filters: "product/filters"
    })
  },

  methods: {
    async getLatest() {
      await this.$store.dispatch("product/getLatest");
    },

    async removeFilter(filter) {
      this.$store.commit("product/removeFilter", filter);

      if (filter.slug === "sale-discount") {
        this.$router.push({ name: "website.shop" });
        //await this.$store.dispatch("product/filter", { pag: 1 });
        this.scrollToCustom()
        //return;
      }

      await this.$store.dispatch("product/filter", { pag: 1 });
    },
    scrollToCustom(selector = "product-list") {
      let wait = 0;
      if (window.innerWidth < 992) {
        this.menuChange();
        wait = 2;
      }
      gsap.registerPlugin(ScrollToPlugin);
      gsap.to(window, {
        duration: 1,
        scrollTo: { y: "#" + selector, offsetY: 50 },
        delay: wait
      });
    },
  }
};
</script>
