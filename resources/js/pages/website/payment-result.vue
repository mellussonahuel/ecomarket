<template>
  <div id="payment-result">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 m-auto result-description">
          <div class="result" v-if="result === 'approved'">
            <img src="/img/pago_ok.png" alt="pago-ok" />
          </div>
          <div class="result" v-else>
            <img src="/img/pago_error.png" alt="pago-error" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { gsap } from "gsap";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";
export default {
  metaInfo() {
    return {
      title: "Resultado de la compra"
    };
  },

  props: {},

  components: {},

  data() {
    return {
      result: ""
    };
  },

  computed: {},

  created() {
    this.result = this.$route.params.result;

    if (this.result === "approved") {
      this.$store.commit("cart/cleanCart");
    }

    if (this.$router.currentRoute.params.scroll) {
      this.scrollToCustom(this.$router.currentRoute.params.scrollTo);
    }
  },

  methods: {
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
    }
  }
};
</script>
