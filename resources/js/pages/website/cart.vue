<template>
  <div id="cart">
    <!-- Breadcrumb Section Begin -->
    <!-- style="background-image: url('img/breadcrumb.jpg');" -->
    <section class="breadcrumb-section set-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <!-- <img src="/img/mycart.png" alt=""> -->
            <!-- <div class="breadcrumb__text">
                        <h2>Mi Pedido</h2>
                    </div> -->
          </div>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shoping Cart Section Begin -->

    <section class="shoping-cart spad">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="shoping__cart__table">
              <table>
                <thead>
                  <tr>
                    <th class="shoping__product">Productos</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Descuento</th>
                    <th>Total</th>
                    <th></th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-if="!cart.length">
                    <td colspan="5" class="text-center">
                      Aún no tenés productos en tu carrito.
                    </td>
                  </tr>
                  <template v-else-if="cart.length">
                    <cart-item
                      v-for="(item, index) in getCart"
                      :key="index"
                      :item="item"
                    ></cart-item>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-6">
            <div class="shoping__cart__btns">
              <template>
                <router-link
                  class="primary-btn cart-btn"
                  :to="{ name: 'website.shop' }"
                >
                  Seguir comprando
                </router-link>
              </template>
              <!-- -->
              <a
                href="#"
                v-if="cart.length"
                class="primary-btn cart-btn"
                @click.prevent="cleanCartConfirmation"
                >Limpiar carrito</a
              >
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="shoping__checkout">
              <h5>Total del carrito</h5>
              <ul>
                <!-- <li>Subtotal <span>${{total}}</span></li> -->
                <li>
                  Total <span>${{ getTotal }}</span>
                </li>
              </ul>

              <template>
                <router-link
                  class="primary-btn"
                  :to="{ name: 'website.checkout' }"
                >
                  TERMINAR DE COMPRAR
                </router-link>
              </template>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Shoping Cart Section End -->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Form from "vform";
import axios from "axios";
import Swal from "sweetalert2";
import CartItem from "./partials/cartItem";
export default {
  metaInfo() {
    return { title: "Mi carrito" };
  },

  components: {
    CartItem
  },

  mounted() {},

  data() {
    return {
      form: new Form({
        name: "",
        email: "",
        message: "",
        phone: ""
      })
    };
  },

  computed: {
    ...mapGetters({
      cart: "cart/cart",
      total: "cart/total"
    }),
    getTotal() {
        return this.total
    },
    getCart(){
        return this.cart
    }
  },

  created() {
    //
  },

  methods: {
    cleanCartConfirmation() {
      var self = this;
      Swal.fire({
        text: "¿ Querés remover todos los productos del carro ?.",
        type: "warning",
        confirmButtonText: "Ok",
        showCancelButton: true,
        cancelButtonText: "Cancelar"
      }).then(function(isConfirm) {
        if (isConfirm.value) {
          self.cleanCart();
        }
      });
    },
    async cleanCart() {
      await this.$store.commit("cart/cleanCart");
    },
    finishSale() {
      this.$router.push({ name: "website.checkout" });
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
