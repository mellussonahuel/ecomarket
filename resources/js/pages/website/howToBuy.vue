<template>
  <div id="how-to">
    <section class="breadcrumb-section set-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center"></div>
        </div>
      </div>
    </section>
    <div class="container">
      <div class="row mt-5">
        <div class="col-12 col-md-6 easy-buy">
        
                Comprar en EcoMarket es fácil y práctico! <br /><br />
        
          <p data-number="1" class="how-to-buy-step">
            Buscá los productos que necesitás por categoría o en la barra de búsqueda,seleccioná la presentación que querés y agregalo al carrito.
          </p>
        </div>
        <div class="col-12 col-md-6">
          <span class="search-bar"></span>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-12 col-md-6 click-on-product">
          <p data-number="2" class="how-to-buy-step">
            Una vez que agregaste todo lo que querés, hacé click en el carrito para revisar tu pedido y elegir el medio de pago*. En el caso de que elijas efectivo contra entrega o transferencia, te aparecerá el total con el descuento del 5%.
          </p>
        </div>
        <div class="col-12 col-md-6">
          <span class="how-to-cart"></span>
          <!-- add-to-cart -->
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 check-cart">
          <p data-number="3" class="how-to-buy-step">
            Registrate: ingresá todos tus datos, nombre y apellido completo, dirección para la entrega del pedido, dirección de mail y teléfono con whatsapp.
          </p>
        </div>
        <div class="col-12 col-md-6 ">
          <span class="how-to-register"></span>
        </div>
      </div>
      <div class="row">
        <div class="col-12 mt-5">
          <p data-number="4" class="how-to-buy-step">
            Una vez finalizado el pedido recibirás un mail con el detalle de tu compra. <br /><br />
          </p>
        </div>
        <!-- <div class="col-12">
          <span class="how-to-register"></span>
        </div> -->
      </div>

      <div class="row">
        <div class="col-12 register-and-finish">
          Listo! Ahora nos toca a nosotr@s, ya estamos preparando tu pedido para que lo
          recibas sin haberte movido de tu hogar!
        </div>
        <div class="col-12"></div>
      </div>

      <div class="row">
        <div class="col-12 thanks">
          Gracias por elegir ECOMARKET!
        </div>
      </div>
    </div>

    <!-- <p>

            *Si tenés un cupón de descuento, este es el momento de ingresarlo!

            *Elegí el día para tu entrega. Las entregas se realizan de lunes a viernes entre las 15 y las 19 hs y los sábados de 9 a 12 hs. Te enviaremos un whatsapp con una franja horaria estimada para el arribo de tu pedido (los horarios se pueden ver ligeramente alterados por cuestiones de tráfico, demora en las entregas u otros imprevistos).

            *Registrate: ingresá todos tus datos, nombre y apellido completo, dirección para la entrega del pedido, dirección de mail y teléfono con whatsapp.

            *Una vez finalizado el pedido recibirás un mail con el detalle de tu compra. En el caso de que hayas seleccionado la opción de abonar con Mercado Pago, recibirás un mail adicional con el link para realizar el pago.

            *Ahora nos toca a nosotr@s, ya estamos preparando tu pedido para que lo recibas sin haberte movido de tu hogar!

            Gracias por elegir ECOMARKET!
        </p> -->
  </div>
</template>

<script>
import { gsap } from "gsap";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";
export default {
  metaInfo() {
    return {
      title: "Como comprar"
    };
  },

  props: {},

  components: {},

  data() {
    return {};
  },

  computed: {},

  created() {
    if (this.$router.currentRoute.params.scroll) {
      this.scrollToCustom(this.$router.currentRoute.params.scrollTo);
    }
  },

  methods: {
    scrollToCustom(selector = "product-list") {
      let wait = 0;
      if (window.innerWidth < 992) {
        this.menuChange();
        wait = 2;
      }
      gsap.registerPlugin(ScrollToPlugin);
      gsap.to(window, {
        duration: 1,
        scrollTo: { y: "#" + selector, offsetY: 50 },
        delay: wait
      });
    }
  }
};
</script>
