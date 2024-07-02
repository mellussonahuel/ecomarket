<template>
  <div class="form" id="user-sale-list">
    <div class="col-12 mt-5 ">
        <div class="header">
            <div>#</div>
            <div>Fecha</div>
            <div>Tipo de pago</div>
            <div>Estado del pago</div>
            <div>Total</div>
            <div>Opciones</div>
        </div>
     
        <div class="items-container" v-if="sales.length">
          <transition-group name="fade" mode="out-in" >
          <user-sales-list-item
            v-for="(sale, index) in sales"
            :key="index + '-' + sale.id"
            :sale="sale"
          ></user-sales-list-item>
          </transition-group>
        </div>
      
    </div>
    <div class="col-12 text-center">
      <paginator
        :limit="3"
        :data="paginationData"
        @pagination-change-page="fetchSales"
      ></paginator>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
import { mapGetters } from "vuex";
import findIndex from "lodash/findIndex";
import Swal from "sweetalert2";
import UserSalesListItem from "./UserSalesListItem.vue";
import Paginator from "laravel-vue-pagination";
export default {
  name: "UserSaleList",
  scrollToTop: true,

  components: {
    UserSalesListItem,
    Paginator
  },

  props: {},

  data() {
    return {
      message: null,
      form: new Form({})
    };
  },

  computed: {
    ...mapGetters({
      sales: "sales/sales",
      paginationData: "sales/pagination",
      page: "sales/page",
      user: "users/user"
    })
  },

  async created() {
    this.fetchSales();
  
  },

  methods: {
    async fetchSales(page = 1) {
      await this.$store.dispatch("sales/fetchByUser", {
        user: this.user.id,
        pag: page
      });
    },
    
  }
};
</script>
