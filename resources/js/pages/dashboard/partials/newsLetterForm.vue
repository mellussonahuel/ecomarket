<template>
  <div id="category-form" class="form">
    <form @submit.prevent="saveNewsletter" @keydown="form.onKeydown($event)">
      <div class="fields-container">
        <!-- Description-->
        <div class="form-group row">
          <div class="col-md-12">
            <label> Email</label>
            <input
              v-model="form.email"
              class="form-control"
              type="text"
              name="description"
            />
            <has-error :form="form" field="email" />
          </div>
        </div>

        <!-- Submit Button -->
        <div class="form-group row mt-5">
          <div class="col-md-12">
            <v-button :loading="form.busy" class="btn-primary">
              Guardar
            </v-button>

            <button
              type="button"
              class="btn-primary inverse"
              @click="closeModal"
            >
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import Form from "vform";
import { mapGetters } from "vuex";
import Swal from "sweetalert2";
import axios from "axios";

export default {
  name: "CategoryForm",
  scrollToTop: true,

  components: {},

  data() {
    return {
      message: null,

      form: new Form({
        email: ""
      }),
     
    };
  },

  computed: {
     ...mapGetters({
      page: "newsletter/page"
    })
  },

  props: {
    edit: {
      default: false,
      type: Boolean
    }
  },

  created() {},

  methods: {
    closeModal() {
      this.$emit("close");
    },

    async getNewsletters(pag = 1) {
      await this.$store.dispatch("newsletter/fetchNewsletters", {
        pag: this.page
      });
    },

    async saveNewsletter() {
      await this.form.post("/api/newsletter");

      if (this.form.successful) {
        var self = this;
        Swal.fire({
          text: "Email agregado.",
          type: "success",
          confirmButtonText: '<i class="fa fa-thumbs-up"></i>',
          showCancelButton: false
        });
        self.getNewsletters()
        self.closeModal()
      } else {
        this.toast("Error!.");
      }
    }
  }
};
</script>

<style scoped></style>
