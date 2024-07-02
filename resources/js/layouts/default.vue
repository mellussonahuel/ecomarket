<template>
  <div>
    <!-- <div v-if="getStatus">
      <div>
        <p class="text-center">
          Estamos realizando tareas de mantenimiento, estaremos online
          muy pronto!
        </p>
      </div>
    </div> -->
    <div class="main-layout">
      <navbar />

      <div class="container mt-4">
        <child />
      </div>
      <footer class="footer spad">
        <div class="container">
          <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6">
              <div class="footer__about">
                <template>
                  <router-link :to="{ name: 'website.home' }" class="">
                    <img src="/img/eco-logo-up.png" alt="" />
                  </router-link>
                </template>
                <ul>
                  <li>Mar del Plata, Argentina.</li>
                  <li>Teléfono: 223 5764153</li>
                  <li>Email: hola@ecomarketonline.com.ar</li>
                </ul>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 offset-lg-1">
              <div class="footer__widget">
                <h6>Links útiles</h6>
                <ul>
                  <li>
                    <router-link
                      :to="{ name: 'website.about' }"
                      active-class="active"
                      exact
                    >
                      Quiénes somos
                    </router-link>
                  </li>
                  <li>
                    <router-link
                      :to="{ name: 'website.contact' }"
                      active-class="active"
                      exact
                    >
                      Contacto
                    </router-link>
                  </li>
                  <li>
                    <router-link
                      :to="{ name: 'website.shop' }"
                      active-class="active"
                      exact
                    >
                      Tienda
                    </router-link>
                  </li>
                  <li>
                    <a href="" @click.prevent="showHowTo">
                      Cómo comprar
                    </a>
                  </li>
                  <li>
                    <router-link
                      :to="{ name: 'website.promos' }"
                      active-class="active"
                      exact
                    >
                      Promociones
                    </router-link>
                  </li>
                  <li>
                    <a href="" @click.prevent="showPaymentOptions">
                      Forma de pago
                    </a>
                  </li>
                  <li>
                    <a href="" @click.prevent="showShips">
                      Envíos
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-lg-4 col-md-12">
              <div class="footer__widget">
                <h6>Suscribite al newsletter</h6>
                <p>
                  Recibí primero las últimas novedades y ofertas de EcoMarket.
                </p>
                <form
                  @submit.prevent="saveNewsletter"
                  @keydown="form.onKeydown($event)"
                >
                  <div class="input-group form-group mb-3">
                    <input
                      type="text"
                      class="form-control shadow-none"
                      :class="{ 'is-invalid': form.errors.has('email') }"
                      v-model="form.email"
                      placeholder="Ingresa tu email"
                    />
                    <div class="input-group-append">
                      <span class="input-group-text" id="basic-addon2">
                        <v-button :loading="form.busy" class="site-btn">
                          SUSCRIBIRSE
                        </v-button>
                      </span>
                    </div>
                    <has-error :form="form" field="email" />
                  </div>
                </form>
                <div class="footer__widget__social">
                  <a
                    href="https://www.facebook.com/Ecomarketonline-100529298492797"
                    target="_blank"
                    ><i class="fab fa-facebook-f"></i
                  ></a>
                  <a
                    href="https://www.instagram.com/ecomarketonline_/"
                    target="_blank"
                    ><i class="fab fa-instagram"></i
                  ></a>
                  <a
                    href="https://api.whatsapp.com/send?phone=5492235764153&text=Hola, tengo una consulta sobre "
                    target="_blank"
                    ><i class="fab fa-whatsapp"></i
                  ></a>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="footer__copyright">
                <div class="footer__copyright__text">
                  <p>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    All rights reserved | This template is made with
                    <i class="fa fa-heart" aria-hidden="true"></i> by
                    <a href="https://colorlib.com" target="_blank">Colorlib</a>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </footer>
      <div class="position-relative">
        <cookies-message v-if="!cookiesStatus"></cookies-message>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "~/components/Navbar";
import Form from "vform";
import axios from "axios";
import Swal from "sweetalert2";
import { gsap } from "gsap";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";
import CookiesMessage from "../pages/website/partials/cookies-message.vue";
import { mapGetters } from "vuex";

export default {
  name: "MainLayout",
  data() {
    return {
      form: new Form({
        email: ""
      })
    };
  },
  components: {
    Navbar,
    CookiesMessage
  },
  computed: {
    ...mapGetters({
      cookiesStatus: "cookiesstatus/status"
    })
  },
  methods: {
    getStatus() {
      return window.location.pathname === "/home";
    },
    showHowTo() {
      if (this.$router.currentRoute.name === "website.how.to") {
        this.scrollToCustom("how-to");
        return;
      }
      let params = {};
      params.scroll = true;
      params.scrollTo = "how-to";
      this.$router.push({ name: "website.how.to", params });
    },
    showShips() {
      if (this.$router.currentRoute.name === "website.ships") {
        this.scrollToCustom("ships");
        return;
      }
      let params = {};
      params.scroll = true;
      params.scrollTo = "ships";
      this.$router.push({ name: "website.ships", params });
    },
    showPaymentOptions() {
      if (this.$router.currentRoute.name === "website.payments.options") {
        this.scrollToCustom("payments-options");
        return;
      }
      let params = {};
      params.scroll = true;
      params.scrollTo = "payments-options";
      this.$router.push({ name: "website.payments.options", params });
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
    },
    async saveNewsletter() {
      await this.form.post("/api/newsletter");

      if (this.form.successful) {
        var self = this;
        Swal.fire({
          text: "Gracias por suscribirte al newsletter de ecomarket!.",
          type: "success",
          confirmButtonText: '<i class="fa fa-thumbs-up"></i>',
          showCancelButton: false
        });
      } else {
        this.toast("Error!.");
      }
    }
  }
};
</script>
