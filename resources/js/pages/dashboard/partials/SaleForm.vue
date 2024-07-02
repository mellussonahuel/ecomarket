<template>
  <div class="form" id="sale-form">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <div class="fields-container">
        <div class="header mb-5">
          Descripción de la venta
        </div>
        <hr>
        <!-- Date -->
        <div class="form-group row">
          <div class="col-md-12">
            <label><strong>Fecha :</strong> {{ sale.sale_date }}</label>
          </div>
        </div>

        <!-- Client name -->
        <div class="form-group row">
          <div class="col-md-12">
            <label><strong> Cliente: </strong>{{ sale.user.name }}</label>
          </div>
        </div>

        <!-- Client email -->
        <div class="form-group row">
          <div class="col-md-12">
            <label><strong> Email: </strong>{{ sale.user.email }}</label>
          </div>
        </div>

        <!-- Client phone -->
        <div class="form-group row">
          <div class="col-md-12">
            <label><strong> Teléfono:</strong> {{ sale.user.phone }}</label>
          </div>
        </div>

        <!-- Client delivery address -->
        <div class="form-group row">
          <div class="col-md-12">
            <label
              ><strong> Dirección de entrega:</strong>
              {{ sale.delivery_address }}</label
            >
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-6">
            <label>
              <strong> Localidad: </strong>
            </label>
            <v-select
              key="1"
              v-model="form.location_id"
              :reduce="c => c.id"
              :label="'description'"
              :clearable="false"
              :options="locations"
            >
            </v-select>
             
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-left">Producto</th>
                  <th class="text-center">Cantidad</th>
                  <th class="text-center">Descuento</th>
                  <th class="text-center">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in sale.items" :key="index">
                  <td class="text-left">
                    {{
                      item.variant_id
                        ? item.variant_description
                        : item.description
                    }}
                  </td>
                  <td class="text-center">
                    {{ item.qty }}
                  </td>
                  <td class="text-center">%{{ item.discount }}</td>
                  <td class="text-center">${{ item.total }}</td>
                </tr>
                 <tr class="total">
                  <td class="purchase-total-description text-right" colspan="3">
                    <strong>Total desscuentos</strong>
                  </td>
                  <td>
                    <strong>${{ sale.total_discount }}</strong>
                  </td>
                </tr>
                <tr class="total">
                  <td class="purchase-total-description text-right" colspan="3">
                    <strong>Total de tu compra</strong>
                  </td>
                  <td>
                    <strong>${{ sale.total }}</strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Client email -->
        <div class="form-group row">
          <div class="col-md-12">
            <label> <strong> Nota: </strong>{{ sale.order_note }}</label>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-6">
            <label>
              <strong> Tipo de pago: </strong>
            </label>
            <v-select
              key="1"
              v-model="form.payment_type"
              :reduce="c => c.value"
              :label="'label'"
              :clearable="false"
              :options="getPaymentsMethods"
            >
            </v-select>
             
          </div>
        </div>

        <!-- Client email -->
        <div class="form-group row">
          <div class="col-md-6">
            <label> <strong> Estado del pago: </strong></label>
            <v-select
              key="2"
              v-model="form.payment_status"
              :reduce="c => c.value"
              :label="'label'"
              :clearable="false"
              :options="getPaymentsStatus"
            >
            </v-select>

            <span :class="{ 'is-invalid': form.errors.has('payment_status') }"></span>
            <has-error :form="form" field="payment_status" />
         
          </div>
        </div>

        
    

        <!-- Submit Button -->
        <div class="form-group row mt-5">
          <div class="col-md-12">
            <v-button :loading="form.busy" class="btn-primary">
              Guardar
            </v-button>

            <button type="button" class="btn-primary inverse" @click="close">
              {{ "Cerrar" }}
            </button>
            <button class="btn btn-action" @click.prevent="printSale">
              <i class="fad fa-print"></i>
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
import { mapGetters } from "vuex";
import Multiselect from "vue-multiselect";
import findIndex from "lodash/findIndex";
import PaymentMethods from "../../../constants/paymentsMethods";
import PaymentStatus from "../../../constants/paymentStatus";
import Swal from "sweetalert2";
import vSelect from "vue-select";

export default {
  name: "SaleForm",
  scrollToTop: true,

  components: {
    Multiselect,
    vSelect
  },

  props: {
    sale: {
      type: Object,
      required: true
    },
    edit: {
      default: false,
      type: Boolean
    }
  },

  data() {
    return {
      message: null,
      showVariantCreate: false,
      form: new Form({
        delivery_address: "",
        delivery_date: "",
        email: "",
        id: "",
        items: [],
        name: "",
        order_note: "",
        payment_status: "",
        payment_type: "",
        phone: "",
        state: "",
        total: "",
        updated_at: "",
        user: {},
        user_id: "",
        location_id:"",
      }),
      paymentStatus: [],
      paymentMethods:[],
    };
  },

  computed: {
    ...mapGetters({
      page: "sales/page",
      locations : "locations/locations"
    }),
    getPaymentsStatus(){
        return this.paymentStatus
    },
    getPaymentsMethods(){
        return this.paymentMethods
    }
  },

  async created() {
    this.locations.length ? null : this.getLocations()
   
    const objectArrayPS = Object.entries(PaymentStatus);

    objectArrayPS.forEach(([key, val]) => {
        this.paymentStatus.push({ label:val, value:key})
    });

    const objectArrayPM = Object.entries(PaymentMethods);

    objectArrayPM.forEach(([key, val]) => {
        this.paymentMethods.push({ label:val, value:key})
    });


    var self = this;
    this.prePopulateForm();
    if (this.sale.readed === 0) {
      this.setReaded();
    }
  },

  methods: {
    async getLocations(pag = 1) {
      await this.$store.dispatch("locations/fetchLocations", {
        pag: this.page,
        active: true
      });
    },
    prePopulateForm() {
      if (Object.keys(this.sale).length != 0) {
        for (let key in this.sale) {
          if (this.has(this.form, key)) {
            this.form[key] = this.sale[key];
          }
        }
        this.form.items = this.sale.items;
        this.form.user = this.sale.user;
      } else {
        this.form.reset();
      }
    },

    setSaleType(saletype) {
      this.form.sale_type = saletype.description;
    },

    async update() {
       
      var self = this;
      await this.form.post("/api/sales/change-payment-status");
      if (this.form.successful) {
        this.toast("Actualizado!.");
        await this.$store.dispatch("sales/fetchSales", {
          pag: self.page
        });
      } else {
        this.toast("Error!.");
      }
    },

     printSale(){
          window.open(`/api/sales/print/${this.sale.id}`, '_blank');
          return 
      },

    close() {
      this.$emit("close");
    },
    async setReaded() {
      await axios.get(`/api/sales/set-readed/${this.sale.id}`);
      await this.$store.dispatch("sales/fetchUnread");
    }
  }
};
</script>

<style scoped lang="scss">
.delete-image {
  position: absolute;
  right: 10px;
  top: 0px;
}

.images-container {
  img:hover {
    cursor: move;
  }
  span {
    display: flex;
    width: 100%;
    overflow: auto;
    padding: 15px;
  }
}
</style>
<style>
.trix-content {
  height: 300px !important;
  overflow-y: auto;
}
</style>
