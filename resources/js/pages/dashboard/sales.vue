<template>
  <div id="dashboard-sales">
    <card :title="'Ventas'" id="">
      <div class="head">
        <small>Ventas</small>
      </div>
      <div class="row mt-5">
        <div class="col-12 col-sm-6">
          <input
            class="form-control search shadow-none"
            v-model="term"
            @input="searchByTerm"
            placeholder="Escribí para buscar"
          />
        </div>
      </div>
      <div class="sales-table mt-5">
        <sales-table :sales="sales" @edit=" key => editItem(key)"></sales-table>
      </div>
    </card>
    <!--MODAL CREATE PRODUCTS-->

    <Modal v-if="showSale" :show-footer="false" @close="showSale = false">
      <h3 slot="header">
        Venta
      </h3>
      <template slot="body">
        <SaleForm :sale="item" @close="close" />
      </template>
    </Modal>
    <!-- END MODAL ///-->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Swal from "sweetalert2";
import SaleForm from "./partials/SaleForm";
import Modal from "./../../components/Modal";
import Form from "vform";
import Paginator from "laravel-vue-pagination";
import { debounce } from "debounce";

import SalesTable from "./partials/salesTable.vue";

export default {
  middleware: "admin",

  metaInfo() {
    return { title: "Ventas" };
  },

  components: {
    SaleForm,
    Modal,
    Paginator,
    SalesTable
  },

  data() {
    return {
      showSale: false,
      item: {},
      edit: false,
      form: new Form(),
      term: ""
    };
  },

  async created() {
    this.getSales();
  },

  mounted() {},

  computed: {
    ...mapGetters({
      user: "auth/user",
      sales: "sales/sales",
      page: "sales/page",
      paginationData: "sales/pagination"
    })
  },

  methods: {
    close() {
      this.showSale = false;
      this.getSales(this.page);
    },
    async getSales(pag = 1) {
      var self = this;
      
      await this.$store.dispatch("sales/fetchSales", {
        pag: pag,
        term: self.term
      });
     
    },
    getReadedStatus(readed) {
      return readed === 1
        ? '<i class="fad fa-eye"></i>'
        : '<i class="fad fa-eye-slash"></i>';
    },
    async editItem(key) {
      this.item = { ...this.sales[key] };
      this.showSale = true;
    },
    getFormattedDate(date) {
      return this.moment(date).format("DD/MM/YYYY");
    },
    searchByTerm: debounce(function(e) {
      if (this.term.length >= 0) {
        this.getSales();
      }
    }, 300)
  }
};
</script>
