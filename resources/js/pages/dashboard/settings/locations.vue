<template>
  <div id="dashboard-newsletter">
    <card :title="'Newsletter'" id="">
      <div class="head">
        <small>Localidades</small>
        <button class="btn btn-action" @click="showLocationsModal = true">
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
                    <th class="text-center" scope="col">Localidad</th>
                    <th class="text-center" scope="col">Mínimo</th>
                    <th class="text-center" scope="col">Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(location, index) in locations" :key="index">
                    <td class="text-center" scope="row">{{ location.id }}</td>
                    <td class="text-center" scope="row">{{ location.description }}</td>
                    <td class="text-center" scope="row">${{ location.min }}</td>
                    <td class="text-center">
                      <button class="btn btn-action" @click="editItem(location)">
                        <i class="fad fa-pencil"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <paginator
                :limit="3"
                :data="paginationData"
                @pagination-change-page="getLocations"
              ></paginator>
            </div>
          </transition>
        </div>
      </div>
    </card>

    <!--MODAL CATEGORIES QUEUES-->

    <Modal
      v-if="showLocationsModal"
      :show-footer="false"
      @close="showLocationsModal = false"
    >
      <h3 slot="header">
        Agregar al newsletter
      </h3>
      <template slot="body">
        <LocationsForm :edit="edit" @close="showLocationsModal = false"  />
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
import LocationsForm from '../partials/LocationsForm'
import Modal from "./../../../components/Modal";

export default {
  middleware: "admin",

  metaInfo() {
    return { title: "Admin | locations" };
  },

  components: {
    Paginator,
    LocationsForm,
    Modal
  },

  data() {
    return {
      item: {},
      edit: false,
      form: new Form(),
      showLocationsModal: false  
    };
  },

  async created() {
    this.getLocations();
  },

  mounted() {},

  computed: {
    ...mapGetters({
      user: "auth/user",
      locations: "locations/locations",
      paginationData: "locations/pagination",
      page: "locations/page",
      loading:'locations/loading'
    })
  },

  methods: {
    async getLocations(pag = 1) {
      await this.$store.dispatch("locations/fetchLocations", {
        pag: pag
      });
    },
    async editItem(location) {
      this.edit = true
      this.$store.commit('locations/setLocation',location)
      this.showLocationsModal = true
    },
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
      await this.form.delete(`/api/locations/${id}`);
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
