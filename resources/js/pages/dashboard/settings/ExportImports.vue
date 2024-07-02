<template>
  <div class="row" id="dashboard-settings">
    <div class="col-md-12">
      <card title="Exportar - Importar" class="exports">
       
        <div class="row mt-5">
          <div class="col-12 mt-5">
              <button
              type="button"
              class="btn-primary"
              @click="exportProducts"
            >
              Exportar
            </button>
          </div>
           <div class="col-12 mt-5">
              <button
              type="button"
              class="btn-primary"
              @click="showImportModal = true"
            >
              Importar
            </button>
          </div>
        </div>
      </card>
    </div>
    <!--MODAL IMPORTS PRODUCTS-->

    <Modal
      v-if="showImportModal"
      :show-footer="false"
      @close="showImportModal = false"
    >
      <h3 slot="header">Importar</h3>
      <template slot="body">
        <ImportForm @close="showImportModal = false" />
      </template>
    </Modal>
    <!-- END MODAL ///-->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Swal from "sweetalert2";
import Form from "vform";
import { debounce } from "debounce";
import ImportForm from "../partials/ImportForm.vue"
import Modal from "../../../components/Modal.vue"

export default {
  middleware: "admin",

  components: {
    Modal,
    ImportForm
  },

  data() {
    return {
      showImportModal:false,
      form: new Form({
        form: "",
        to: "",
        location_id: ""
      }),
      term:'',
    };
  },
  async created() {
    this.$store.commit("sales/setSales", []);
  },

  computed: {
    // ...mapGetters({
    //   user: "auth/user",
    //   users: "users/users",
    //   locations: "locations/locations",
    //   sales: "sales/sales"
    // })
  },

  methods: {
  
    close() {
      this.showSale = false;
      //   this.getSales(this.page);
    },
   
    exportProducts() {
    //   this.data.from = this.dpFrom.date  ?  this.moment(this.dpFrom.date).format("YYYY-MM-DD") : '';
    //   this.data.to = this.dpFrom.date  ?  this.moment(this.dpTo.date).format("YYYY-MM-DD") : '';
    //   var queryString = Object.keys(this.data).map(key => key + '=' + this.data[key]).join('&');
    //   window.open(`/api/products/print/sales?${queryString}`, "_blank");
     window.open(`/api/products/export`, "_blank");
    },

  }
};
</script>
