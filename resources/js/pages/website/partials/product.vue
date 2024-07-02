<template>
  <div class="col-6 col-sm-6 col-md-6 col-lg-4" v-if="product">
    <router-link
      exact
      :to="{
        name: 'website.productdetail',
        params: { slug: product.slug }
      }"
    >
      <div class="product__discount__item position-relative">
        <div class="product__discount__percent" v-if="product.discount">
          -{{ product.discount }}%
        </div>
        <div class="product__combo" v-else-if="product.is_combo">Combo</div>
        <div
          class="product__discount__item__pic set-bg"
          :style="`background-image: url('${getImage}');`"
        >
          <!-- <div class="product__discount__percent" v-if="product.discount">-{{ product.discount }}%</div> -->
          <ul class="product__item__pic__hover">
            <li>
              <a @click.stop="setFavorite" :class="{ selected: isSelected }"
                ><span v-html="getHeartIcon"></span
              ></a>
            </li>
            <li>
              <a href="#"><i class="fa fa-shopping-cart"></i></a>
            </li>
          </ul>
        </div>
        <div class="product__discount__item__text">
          <span v-if="product.categories.length">{{
            product.categories[0].description
          }}</span>
          <h5>
            <a href="#">{{ product.title }}</a>
          </h5>
          <div class="product__item__price" v-if="product.discount">
            ${{ product.price_discount }}
            <label v-if="product.sale_type === 'Peso'">
              x {{ product.variants[0].weight }}Gr.</label
            >
            <span>{{ getPrice }}</span>
          </div>
          <div class="product__item__price" v-if="!product.discount">
            ${{ getPrice }}
          </div>
        </div>
      </div>
    </router-link>
    <!-- <a href="" @click.prevent="showProduct(product.slug)" >
                
     </a>               -->
  </div>
</template>
<script>
import { mapGetters } from "vuex";
import Swal from "sweetalert2";
import { gsap } from "gsap";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";

export default {
  mounted() {},

  props: {
    product: {
      type: Object,
      required: true
    }
  },

  data() {
    return {};
  },

  computed: {
    ...mapGetters({
      favorites: "auth/favorites"
    }),
    getPrice() {
      return this.product.sale_type === "Unidad"
        ? this.product.price
        : this.product.variants[0].price +
            " x" +
            this.product.variants[0].weight +
            " Gr.";
    },
    getImage() {
      return this.product.images.length
        ? "/productos/" + this.product.images[0].name
        : "/img/no-image.png";
    },
    isSelected() {
      return this.favorites.includes(this.product.id);
    },
    getHeartIcon() {
      return this.favorites.includes(this.product.id)
        ? '<i class="fad fa-heart"></i>'
        : '<i class="far fa-heart"></i>';
    }
  },

  methods: {
    async setFavorite(e) {
      e.preventDefault();
      await this.$store.commit("auth/setFavorite", this.product.id);
      await this.$store.dispatch("auth/setFavorites");
      Swal.fire({
        toast: true,
        position: "top-end",
        text: "Producto agregado a favoritos!.",
        showConfirmButton: false,
        timer: 3000
      });
    },

    showProduct(slug) {
      if (this.$router.currentRoute.name !== "website.productdetail") {
        this.$router.push({
          name: "website.productdetail",
          params: { slug: slug }
        });
      } else {
        eventBus.$emit("show-product", slug);
        this.scrollToCustom("product-detail");
      }
    },
    scrollToCustom(selector) {
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
    }
  }
};
</script>
