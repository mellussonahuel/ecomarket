<template>
  <div id="subscribe-newsletter">
    <section class="breadcrumb-section set-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center"></div>
        </div>
      </div>
    </section>
    <div class="container">
      <div class="row">
        <div class="result">
           <transition name="fade" mode="out-in">
          <div key="0" class="col-12" v-if="loading">
            <div class="load-spinner">
              <span>
                <i class="fad fa-spinner-third fa-spin"></i>
              </span>
            </div>
          </div>
          <div key="1" v-else class="col-lg-12 m-auto result-description">
            <card >
              <p class="my-auto" v-if="!loading && result">
                Ya estás subscritp@ a nuestro newsletter!
              </p>
              <p class="my-auto" v-else>
                Ocurrió un error, <br> intentá de nuevo o mandanos un mensaje desde
                <router-link :to="{ name: 'website.contact' }">
                  acá
                </router-link>
              </p>
            </card>
          </div>
        </transition>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

import axios from "axios";
import Form from "vform";
export default {
  metaInfo() {
    return {
      title: "Subscribirse al newsletter"
    };
  },

  props: {},

  components: {},

  data() {
    return {
      result: false,
      form: new Form({
        email: ""
      })
    };
  },

  computed: {
     ...mapGetters({
      loading: "newsletter/loading",
    }),
  },

  created() {
      this.form.email = this.$route.params.email
      this.saveNewsletter()
  },

  methods: {
    async saveNewsletter() {
      await this.form.post("/api/newsletter/store-external");

      if (this.form.successful) {
        this.result = true
      } else {
        this.toast("Error!.");
      }
    }
  }
};
</script>
