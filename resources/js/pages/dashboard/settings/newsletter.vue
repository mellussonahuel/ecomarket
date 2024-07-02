<template>
  <div id="dashboard-newsletter">
    <card :title="'Newsletter'" id="">
      <div class="head">
        <small>Emails adheridos</small>
        <button class="btn btn-action" @click="showModalNewsletter = true">
          <i class="fas fa-plus"></i>
        </button>
      </div>

      <div class="newsletter-table">
        <div class="row mt-5">
          <div class="col-12 text-center " v-if="loading">
            <div class="load-spinner">
              <span>
                <i class="fad fa-spinner-third fa-spin"></i>
              </span>
            </div>
          </div>
          <transition name="fade" mode="out-in">
            <div class="col-12" v-if="!loading">
              <table class="table table-sales">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th class="text-center" scope="col">Email</th>
                    <th class="text-center" scope="col">Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(news, index) in newsletters" :key="index">
                    <th class="text-center" scope="row">{{ news.id }}</th>
                    <th class="text-center" scope="row">{{ news.email }}</th>

                    <td class="text-center">
                      <button
                        class="btn btn-delete"
                        @click="deleteConfirmation(news.id)"
                      >
                        <i class="fad fa-trash-alt"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <paginator
                :limit="3"
                :data="paginationData"
                @pagination-change-page="getNewsletters"
              ></paginator>
            </div>
          </transition>
        </div>
      </div>
    </card>

    <!--MODAL CATEGORIES QUEUES-->

    <Modal
      v-if="showModalNewsletter"
      :show-footer="false"
      @close="showModalNewsletter = false"
    >
      <h3 slot="header">
        Agregar al newsletter
      </h3>
      <template slot="body">
        <NewsLetterForm @close="showModalNewsletter = false"  />
      </template>
    </Modal>
    <!-- END MODAL ///-->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Swal from "sweetalert2";
import Form from "vform";
import Paginator from "laravel-vue-pagination";
import NewsLetterForm from '../partials/newsLetterForm'
import Modal from "./../../../components/Modal";

export default {
  middleware: "admin",

  metaInfo() {
    return { title: "Admin | newsletter" };
  },

  components: {
    Paginator,
    NewsLetterForm,
    Modal
  },

  data() {
    return {
      item: {},
      edit: false,
      form: new Form(),
      loading: false,
      showModalNewsletter: false  
    };
  },

  async created() {
    this.getNewsletters();
  },

  mounted() {},

  computed: {
    ...mapGetters({
      user: "auth/user",
      newsletters: "newsletter/newsletters",
      paginationData: "newsletter/pagination",
      page: "newsletter/page"
    })
  },

  methods: {
    async getNewsletters(pag = 1) {
      this.loading = true;
      await this.$store.dispatch("newsletter/fetchNewsletters", {
        pag: pag
      });
      this.loading = false;
    },
    async editItem(slug) {},
    getFormattedDate(date) {
      return this.moment(date).format("DD/MM/YYYY");
    },
    async create() {},
    deleteConfirmation(id) {
      var self = this;
      Swal.fire({
        text: "Eliminar registro ?",
        type: "warning",
        confirmButtonText: "Ok",
        showCancelButton: true,
        cancelButtonText: "Cancelar"
      }).then(function(isConfirm) {
        if (isConfirm.value) {
          self.deleteItem(id);
        }
      });
    },
    async deleteItem(id) {
      await this.form.delete(`/api/newsletter/${id}`);
      if (this.form.successful) {
        // pull premises after insert.
        this.getNewsletters();
        this.toast("Registro eliminado!.");
      } else {
        this.toast("Error!.");
      }
    }
  }
};
</script>
