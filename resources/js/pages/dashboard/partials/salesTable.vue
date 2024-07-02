<template>
  <div class="row" id="sales-table">
    <transition name="fade" mode="out-in">
      <div key="0" class="col-12" v-if="loading">
        <div class="load-spinner">
          <span>
            <i class="fad fa-spinner-third fa-spin"></i>
          </span>
        </div>
      </div>
      <div key="2" class="col-12" v-if="!loading">
        <table class="table table-sales">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th class="text-center" scope="col">Nombre</th>
              <th class="text-center" scope="col">Email</th>
              <th class="text-center" scope="col">Fecha</th>
              <th class="text-center" scope="col">Estado del pago</th>
              <th class="text-center" scope="col">Visto</th>
              <th class="text-center" scope="col">Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(sale, index) in sales" :key="index">
              <td class="text-center" scope="row">{{ sale.id }}</td>
              <td class="text-center" scope="row">{{ sale.name }}</td>
              <td class="text-center">{{ sale.email }}</td>
              <td class="text-center">
                {{ getFormattedDate(sale.created_at) }}
              </td>
              <td class="text-center">{{ sale.payment_status }}</td>
              <td class="text-center">
                <span v-html="getReadedStatus(sale.readed)"></span>
              </td>
              <td class="text-center">
                <!-- <button class="btn btn-delete" @click="deleteConfirmation(product.id)"><i class="fad fa-trash-alt"></i></button> -->
                <button class="btn btn-action" @click="editItem(index)">
                  <i class="fad fa-pencil"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="col-12 text-center">
          <paginator
            :limit="3"
            :data="paginationData"
            @pagination-change-page="getSales"
          ></paginator>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Swal from "sweetalert2";
import Form from "vform";
import Paginator from "laravel-vue-pagination";

export default {
  middleware: "admin",

  components: {
    Paginator
  },

  data() {
    return {
      term:''
    };
  },
  async created() {},

  props: {
    sales: {
      type: Array,
      required: true
    }
  },

  computed: {
    ...mapGetters({
      user: "auth/user",
      locations: "locations/locations",
      paginationData: "sales/pagination",
      loading: "sales/loading"
    })
  },

  methods: {
    getFormattedDate(date) {
      return this.moment(date).format("DD/MM/YYYY");
    },
    async editItem(key) {
      this.$emit("edit", key);
    },
    getReadedStatus(readed) {
      return readed === 1
        ? '<i class="fad fa-eye"></i>'
        : '<i class="fad fa-eye-slash"></i>';
    },

    async getSales(pag = 1) {
      var self = this;
      await this.$store.dispatch("sales/fetchSales", {
        pag: pag,
        term: self.term
      });
    }
  }
};
</script>
