<template>
  <div class="row" id="dashboard-settings">
    <div class="col-md-12">
      <card title="reportes" class="reports">
        <div class="row">
          <div class="col-12 col-md-6">
            <div class="checkout__input">
              <p>Desde</p>
              <datepicker
                v-model="dpFrom.date"
                format="dd/MM/yyyy"
                :language="es"
                :class="{
                  'is-invalid': form.errors.has('from')
                }"
                :clear-button="true"
                calendar-class="calendar"
                :bootstrap-styling="true"
              ></datepicker>
              <has-error :form="form" field="from" />
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="checkout__input">
              <p>Hasta</p>
              <datepicker
                v-model="dpTo.date"
                format="dd/MM/yyyy"
                :language="es"
                :class="{
                  'is-invalid': form.errors.has('to')
                }"
                :clear-button="true"
                calendar-class="calendar"
                :bootstrap-styling="true"
              ></datepicker>
              <has-error :form="form" field="to" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <label>
              <strong> Localidad: </strong>
            </label>
            <v-select
              key="1"
              v-model="data.location_id"
              :reduce="c => c.id"
              :label="'description'"
              :clearable="false"
              :options="locations"
            >
            </v-select>
          </div>
        </div>
         <div class="row">
          <div class="col-md-6">
            <label>
              <strong> Usuario: </strong>
            </label>
            <v-select
              key="2"
              v-model="term"
              :reduce="c => c.id"
              :clearable="true"
              :options="users"
              @input="searchByTerm"
             
            >
            <template slot="option" slot-scope="option">
              {{ option.name }} {{option.email}}
          </template>
            </v-select>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-12 mt-5">
              <button
              type="button"
              class="btn-primary"
              @click="print"
            >
              imprimir
            </button>
          </div>
        </div>
        <!-- <div class="sales-table mt-5">
          <sales-table
            :sales="sales"
            @edit="key => editItem(key)"
          ></sales-table>
        </div> -->


      </card>
    </div>
    <!--MODAL SALES-->

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
import Form from "vform";
import Datepicker from "vuejs-datepicker";
import { es } from "vuejs-datepicker/dist/locale";
import vSelect from "vue-select";
import SaleForm from "./partials/SaleForm";
import SalesTable from "./partials/salesTable";
import { debounce } from "debounce";

export default {
  middleware: "admin",

  components: {
    Datepicker,
    vSelect,
    SaleForm,
    SalesTable
  },

  data() {
    return {
      es: es,
      form: new Form({
        form: "",
        to: "",
        location_id: ""
      }),
      term:'',
      data: {
        from: "",
        to: "",
        location_id: "",
        user_id:""
      },
      dpFrom: {
        date: new Date()
      },
      dpTo: {
        date: new Date()
      },
      item: {},
      showSale: false
    };
  },
  async created() {
    this.$store.commit("sales/setSales", []);
  },

  computed: {
    ...mapGetters({
      user: "auth/user",
      users: "users/users",
      locations: "locations/locations",
      sales: "sales/sales"
    })
  },

  methods: {
    async editItem(key) {
      this.item = { ...this.sales[key] };
      this.showSale = true;
    },
    close() {
      this.showSale = false;
      //   this.getSales(this.page);
    },
    search() {},
    searchByTerm: debounce(function(e) {
      
      if (this.term.length >= 0) {
        this.filter();
      }
    }, 300),
    async filter(){
      var self = this
      await this.$store.dispatch('users/filter',{ pag : 1 , term : self.term})
    }, 
    print() {
      this.data.from = this.dpFrom.date  ?  this.moment(this.dpFrom.date).format("YYYY-MM-DD") : '';
      this.data.to = this.dpFrom.date  ?  this.moment(this.dpTo.date).format("YYYY-MM-DD") : '';
      var queryString = Object.keys(this.data).map(key => key + '=' + this.data[key]).join('&');
      window.open(`/api/reports/print/sales?${queryString}`, "_blank");
    }
  }
};
</script>
