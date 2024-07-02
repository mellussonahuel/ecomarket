<template>
  <div class="user-sale-list-item">
    <div>{{ sale.id }}</div>
    <div>{{ getFormattedDate }}</div>
    <div>{{ getPaymentType }}</div>
    <div>{{ getPaymentStatus }}</div>
    <div>${{ sale.total }}</div>
    <div>
      <button class="btn btn-action">
        <i class="fad fa-eye"></i>
      </button>
      <button class="btn btn-action" @click="printSale">
        <i class="fad fa-print"></i>
      </button>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
import { mapGetters } from "vuex";
import findIndex from "lodash/findIndex";
import Swal from "sweetalert2";
import paymentsMethods from "../../../constants/paymentsMethods";
import paymentStatus from "../../../constants/paymentStatus"
export default {
  name: "UserSaleListItem",
  scrollToTop: true,

  components: {},

  props: {
    sale: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      message: null,
      form: new Form({})
    };
  },

  computed: {
    ...mapGetters({}),
    getFormattedDate() {
      return this.moment(this.sale.created_at).format("DD/MM/YYYY");
    },
    getPaymentType(){
        
        return paymentsMethods[this.sale.payment_type]
        
    },
    getPaymentStatus(){
        return paymentStatus[this.sale.payment_status]
    }
  },

  async created() {
    
  },

  methods: {
      printSale(){
          window.open(`/api/sales/print/${this.sale.id}`, '_blank');
      }
  }
};
</script>
