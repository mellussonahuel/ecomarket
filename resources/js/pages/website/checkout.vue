<template>
  <div id="checkout">
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <img src="/img/finalizar.png" alt="" />
          </div>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
      <div class="container">
        <!-- <div class="row">
                <div class="col-lg-12">
                    <h6><span class="icon_tag_alt"></span> Tenés a coupón? <a href="#">Click here</a> to enter your code
                    </h6>
                </div>
            </div> -->

        <div class="checkout__form">
          <div class="row form-group">
            <div class="col-lg-12 mb-5">
              <div class="shoping__continue">
                <div class="shoping__discount">
                  <h5>Código de descuento</h5>
                  <form
                    @submit.prevent="applyCoupon"
                    @keydown="couponForm.onKeydown($event)"
                  >
                    <input
                      type="text"
                      :class="{ 'is-invalid': couponForm.errors.has('code') }"
                      v-model="couponForm.code"
                      placeholder="Ingresá tu código acá!"
                    />
                    <has-error :form="couponForm" field="code" />
                    <v-button :loading="couponForm.busy" class="site-btn">
                      APLICAR CUPÓN
                    </v-button>
                    <!-- <button type="submit" class="site-btn">Aplicar cupón</button> -->
                  </form>
                </div>
              </div>
            </div>
          </div>
          <h4>Detalles de compra</h4>
          <form action="#">
            <div class="row">
              <div class="col-lg-8 col-md-6">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="checkout__input">
                      <p>Nombre<span>*</span></p>
                      <input
                        v-model="form.name"
                        :class="{ 'is-invalid': form.errors.has('name') }"
                        type="text"
                        name="name"
                      />
                      <has-error :form="form" field="name" />
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="checkout__input">
                      <p>Email<span>*</span></p>
                      <input
                        v-model="form.email"
                        :class="{ 'is-invalid': form.errors.has('email') }"
                        type="text"
                        name="email"
                      />
                      <has-error :form="form" field="email" />
                    </div>
                  </div>
                </div>

                <div class="checkout__input position-relative">
                  <p>Dirección de entrega<span>*</span></p>
                  <input
                    placeholder="Calle y número"
                    v-model="form.delivery_address"
                    :class="{
                      'is-invalid': form.errors.has('delivery_address')
                    }"
                    class="checkout__input__add"
                    type="text"
                    name="delivery_address"
                  />
                  <has-error
                    :form="form"
                    field="delivery_address"
                    class="position-relative"
                  />
                  <input
                    type="text"
                    v-model="form.delivery_address_note"
                    placeholder="Departamento , piso , timbre (opcional)"
                  />
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="checkout__input">
                      <p>Teléfono<span>*</span></p>
                      <input
                        v-model="form.phone"
                        :class="{ 'is-invalid': form.errors.has('phone') }"
                        type="text"
                        name="phone"
                      />
                      <small
                        >Usaremos este teléfono para coordinar la entrega de tu
                        pedido.</small
                      >
                      <has-error :form="form" field="phone" />
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="location-select-label">
                      <p>Ciudad<span>*</span></p>
                      <v-select
                        v-model="form.location_id"
                        :reduce="l => l.code"
                        :clearable="false"
                        :class="'location-select'"
                        :options="getLocations"
                        label="label"
                      />
                      <!-- <p>Barrio<span></span></p>
                                        <input v-model="form.neighborhood" :class="{ 'is-invalid': form.errors.has('neighborhood') }"  type="text" name="neighborhood">
                                        <has-error :form="form" field="neighborhood" /> -->
                    </div>
                  </div>
                </div>

                <!-- <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="checkout__input">
                      <p>Fecha de entrega</p>
                      <datepicker
                        v-model="dp.date"
                        format="dd-MM-yyyy"
                        :language="es"
                        :class="{
                          'is-invalid': form.errors.has('delivery_date')
                        }"
                        calendar-class="calendar"
                        :bootstrap-styling="true"
                        :disabled-dates="dp.disabledDates"
                      ></datepicker>
                      <has-error :form="form" field="delivery_date" />
                    </div>
                  </div>
                </div> -->

                <div class="checkout__input">
                  <p>Aclaraciones del pedido<span></span></p>
                  <input
                    type="text"
                    v-model="form.order_note"
                    placeholder="Alguna aclaración sobre el pedido , o la entrega."
                  />
                </div>
              </div>
              <div class="col-lg-4 col-md-6">
                <form
                  @submit.prevent="checkMin"
                  @keydown="form.onKeydown($event)"
                >
                  <div class="checkout__order">
                    <h4>Tu pedido</h4>
                    <div class="checkout__order__products">
                      Productos <span>Total</span>
                    </div>
                    <ul>
                      <li v-for="(item, index) in cart" :key="index">
                        <div class="checkout-product-item">
                          {{ item.product_title }}
                          <span>${{ item.total.toFixed(2) }}</span>
                        </div>
                      </li>
                    </ul>

                    <transition name="fade">
                      <div v-if="form.coupon_code != ''">
                        <div class="checkout__order__products">
                          Cupón <span> % descuento</span>
                        </div>
                        <ul>
                          <li>
                            {{ form.coupon_code }}
                            <span>%{{ form.coupon_amount }}</span>
                          </li>
                        </ul>
                      </div>
                    </transition>

                    <div class="checkout__order__subtotal">
                      Subtotal <span>${{ subtotal }}</span>
                    </div>
                    <transition name="fade">
                      <div
                        class="checkout__order__discount"
                        v-if="showDiscount"
                      >
                        Descuentos
                        <span>${{ this.form.total_discount.toFixed(2) }}</span>
                      </div>
                    </transition>

                    <div class="checkout__order__total">
                      Total <span>$ {{ getTotal.toFixed(2) }}</span>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <span
                            :class="{
                              'is-invalid': form.errors.has('payment_type')
                            }"
                          ></span>
                          <has-error :form="form" field="payment_type" />
                        </div>
                      </div>
                    </div>
                    <div id="payment_type_options">
                      <div class="form-check">
                        <label class="custom-radio">
                          Pago contra entrega. <br />
                          (5% de descuento)
                          <input
                            class="form-check-input"
                            type="radio"
                            name="payment_type"
                            value="ON_DELIVERY"
                            @change="getDiscount"
                            v-model="form.payment_type"
                          />
                          <span class="checkmark"></span>
                        </label>
                      </div>

                      <!-- <div class="form-check">
                        <label class="custom-radio">
                          Mandame un link de mercado pago.
                          <input
                            class="form-check-input"
                            type="radio"
                            name="payment_type"
                            value="MP_LINK"
                            @change="getDiscount"
                            v-model="form.payment_type"
                          />
                          <span class="checkmark"></span>
                        </label>
                      </div> -->

                      <div class="form-check">
                        <label class="custom-radio">
                          Transferencia bancaria. <br />
                          (5% de descuento)
                          <input
                            class="form-check-input"
                            type="radio"
                            name="payment_type"
                            value="WIRE_TRANSFER"
                            @change="getDiscount"
                            v-model="form.payment_type"
                          />
                          <span class="checkmark"></span>
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="custom-radio">
                          Mercado pago. <br />
                          <input
                            class="form-check-input"
                            type="radio"
                            name="payment_type"
                            value="MP"
                            @change="getDiscount"
                            v-model="form.payment_type"
                          />
                          <span class="checkmark"></span>
                        </label>
                      </div>
                    </div>

                    <v-button :loading="form.busy" class="site-btn">
                      FINALIZAR COMPRA
                    </v-button>
                  </div>
                </form>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
    <!-- Checkout Section End -->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Form from "vform";
import axios from "axios";
import Swal from "sweetalert2";
import Datepicker from "vuejs-datepicker";
import { es } from "vuejs-datepicker/dist/locale";
import PaymentMethods from "../../constants/paymentsMethods";
import vSelect from "vue-select";

export default {
  middleware: "auth",

  metaInfo() {
    return { title: this.$t("settings") };
  },

  mounted() {},

  data() {
    return {
      form: new Form({
        name: "",
        email: "",
        message: "",
        neighborhood: "",
        phone: "",
        location_id: "",
        delivery_address: "",
        delivery_address_note: "",
        payment_type: "",
        order_note: "",
        delivery_date: "",
        cart: [],
        coupon_code: "",
        coupon_amount: 0,
        total_discount: 0
      }),
      couponForm: new Form({
        code: ""
      }),

      subtotal: 0,
      total: 0,
      discount: 0,
      dp: {
        date: this.moment("MMMM Do YYYY, h:mm:ss a"),
        disabledDates: {
          days: [0, 2, 4],
          to: new Date() // Disable all dates up to specific date
        }
      },
      es: es
    };
  },

  components: {
    Datepicker,
    vSelect
  },

  computed: {
    ...mapGetters({
      cart: "cart/cart",
      user: "auth/user",
      locations: "locations/locations"
    }),
    showDiscount() {
      if (
        this.form.total_discount > 0
        // this.form.payment_type === "on_delivery" ||
        // this.form.payment_type === "wire_transfer"
      ) {
        return true;
      }
      return false;
    },
    getTotal() {
      return this.subtotal - this.form.total_discount;
    },
    getLocations() {
      return this.locations
        ? this.locations.map(l => {
            return { label: l.description, code: l.id };
          })
        : [];
    }
  },

  created() {
    this.prePopulateForm();
    this.getSubTotal();

    // this block belongs to the date picker component
    // var currentDate = new Date(this.moment().year(),this.moment().month(),this.moment().day());
    // console.log(currentDate)
    // console.log(this.moment().format('DD-MM-YYYY'))
    // this.dp.date = currentDate;

    // if (currentDate.getDay() === 0) {
    //   this.dp.date = new Date(
    //     currentDate.setTime(currentDate.getTime() + 1 * 86400000)
    //   );
    // } else {
    //   this.dp.date =
    //     currentDate.getHours() <= 12 || currentDate.getDay() === 0
    //       ? currentDate
    //       : currentDate.setDate(currentDate.getDate() + 1);
    // }

    this.locations.length ? null : this.fetchLocations();
    //this.form.delivery_date = this.dp.date;
    this.form.location_id = this.user.location_id ? this.user.location_id : "";
  },

  methods: {
    async fetchLocations(pag = 1) {
      await this.$store.dispatch("locations/fetchLocations", {
        pag: pag,
        active: true
      });
    },
    async fetchLocations(pag = 1) {
      await this.$store.dispatch("locations/fetchLocations", {
        pag: pag,
        active: true
      });
    },
    prePopulateForm() {
      if (!this.user) this.$router.push({ name: "login" });
      if (Object.keys(this.user).length != 0) {
        for (let key in this.user) {
          if (this.has(this.form, key)) {
            this.form[key] = this.user[key];
          }
        }
        this.form.delivery_address = this.user.address;
      } else {
        this.form.reset();
      }
    },
    getSubTotal() {
      this.subtotal = this.cart
        .reduce((a, b) => a + (b["total"] || 0), 0)
        .toFixed(2);
    },
    getDiscount() {
      let discount = 0;
      if (this.form.coupon_amount > 0) {
        discount =
          (this.form.coupon_amount / 100) *
          this.cart.reduce((a, b) => a + (b["total"] || 0), 0).toFixed(2);
      }

      if (
        this.form.payment_type === "ON_DELIVERY" ||
        this.form.payment_type === "WIRE_TRANSFER"
      ) {
        discount =
          (5 / 100) *
            this.cart.reduce((a, b) => a + (b["total"] || 0), 0).toFixed(2) +
          discount;
      }

      this.form.total_discount = discount;
    },
    async applyCoupon() {
      var self = this;

      const { data } = await this.couponForm.get(
        `/api/coupons/validate?code=${self.couponForm.code}`
      );

      if (this.couponForm.successful) {
        if (data.status === false) {
          Swal.fire({
            text: data.msg,
            type: "error",
            confirmButtonText: "Aceptar"
          });
          return;
        }

        this.form.coupon_code = data.coupon.code;
        this.form.coupon_amount = data.coupon.amount;
        this.getDiscount();
        Swal.fire({
          toast: true,
          position: "top-end",
          text: "Cupón aplicado !.",
          showConfirmButton: false,
          timer: 3000
        });
      }
    },
    checkMin() {
      var self = this;

      if (!this.form.location_id) {
        Swal.fire({
          text: "Tenes que seleccionar tu localidad !.",
          type: "warning",
          confirmButtonText: "Ok!",
          showCancelButton: false,
          cancelButtonText: "Cancelar"
        });
        return;
      }

      if (!this.user) {
        var self = this;
        Swal.fire({
          text: "Tenes que iniciar sesión antes de finalizar la compra !.",
          type: "warning",
          confirmButtonText: "",
          showCancelButton: true,
          cancelButtonText: "Cancelar"
        });
        return;
      }

      let location = this.locations.find(l => {
        return l.id === this.form.location_id;
      });

      if (this.subtotal < location.min) {
        Swal.fire({
          html:
            "¡Tu compra no supera el mínimo de $" +
            location.min +
            "! <br>" +
            "podés seguir comprando o finalizar la compra <br>" +
            "(deberás abonar el faltante hasta completar los $" +
            location.min +
            ") <br>" +
            'más información sobre el proceso de compra en <br> <a class="how-to-link" href="#">¿ Cómo comprar ?</a>',
          type: "warning",
          confirmButtonText: "Terminar compra",
          showCancelButton: true,
          cancelButtonText: "Seguir comprando"
        }).then(function(isConfirm) {
          if (isConfirm.value) {
            self.finishSale();
          }
        });
      } else {
        this.finishSale();
      }
    },
    async finishSale() {
      var self = this;
      this.form.cart = this.cart;
      //this.form.delivery_date = this.moment(this.dp.date).format("YYYY-MM-DD");

      let payment_type = this.form.payment_type;
      const { data } = await this.form.post("/api/sales");

      if (this.form.successful) {
        if (!data.status) {
          
          let msj = '<ul style="list-style: none;">';
          data.products.map(item => {
            msj +=
              "<li> <strong>producto:<strong>" +
              item.product.title +
              " <strong>stock del producto : </strong>" +
              item.product.stock +
              "<strong> pedido: </strong>" +
              item.cantidad_item +
              " </li>";
          });
          msj += "</ul>";
        
          Swal.fire({
            title: "Alguno/s de los productos no tiene stock suficiente!",
            type: "error",
            html: msj,
            confirmButtonText: '<i class="fa fa-thumbs-up"></i>',
            showCancelButton: false
          }).then(function(isConfirm) {
            if (isConfirm.value) {
              self.$router.push({ name: "website.cart" });
            }
          });
          return;
        }
        if (data.status === true && payment_type === "MP") {
          window.location.href = `/api/payments/generar-pago/${data.hash}`;
          return;
        }
        this.$store.commit("cart/cleanCart");
        var self = this;
        Swal.fire({
          text: "Gracias por tu compra!.",
          type: "success",
          confirmButtonText: '<i class="fa fa-thumbs-up"></i>',
          showCancelButton: false
        }).then(function(isConfirm) {
          if (isConfirm.value) {
            self.$router.push({ name: "website.home" });
          }
        });
      } else {
        this.toast("Error!.");
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.invalid-feedback {
  position: absolute;
  bottom: 5px;
}
</style>
