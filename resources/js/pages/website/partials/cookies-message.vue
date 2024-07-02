<template>
  <transition name="fade" mode="out-in">
    <div id="cookies-message">
      <p>
        Utilizamos cookies propias y de terceros para obtener datos estadísticos
        de la navegación de nuestros usuarios y mejorar nuestros servicios. Si
        acepta o continúa navegando, consideramos que acepta su uso.
      </p>
      <div class="cookies-buttons">
          <button @click="acceptCookies" class="site-btn">Aceptar</button>
          <a class="btn btn-default" href="https://www.google.com/">Salir del sitio</a>
      </div>
    </div>
  </transition>
</template>
<script>
import { mapGetters } from "vuex";
import Cookies from "js-cookie";

export default {
  scrollToTop: true,

  metaInfo() {
    return { title: "Inicio" };
  },

  mounted() {},

  created() {},

  data() {
    return {};
  },

  computed: {
    ...mapGetters({})
  },

  methods: {
      acceptCookies(){
          Cookies.set('accepted-cookie', true)
          var minutes = new Date(new Date().getTime() + 180 * 60 * 1000);
          this.$store.commit('cookiesstatus/setCookies',true, { expires: minutes })
      }
  }
};
</script>
