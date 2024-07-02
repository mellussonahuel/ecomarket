<template>
    <div id="payments-options">

         <section class="breadcrumb-section set-bg" >
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        
                    </div>
                </div>
            </div>
        </section>
        <div class="container">
            <!-- <div  class="row">
                <div class="col-11"> -->
                    <div class="row options-list">
                        <div class="col-11  options">
                           <ul>
                               <li>
                                    Efectivo contra entrega en domicilio (5% de descuento sobre el total de tu compra).
                               </li>
                               <li>
                                    Transferencia bancaria (5% de descuento sobre el total de tu compra): al seleccionar esta opción, recibirás un mail con el detalle de la cuenta para que puedas realizar tu pago. Te pediremos que nos envíes el comprobante emitido por el banco para verificar la correcta acreditación.
                               </li>
                               <li>
                                    Tarjeta de débito o crédito a través de link de Mercado Pago: al seleccionar esta opción, recibirás un mail con el link de Mercado Pago para que puedas realizar la transacción de manera segura.
                               </li>
                               <li>
                                   Próximamente <img src="/img/mp.png" alt="mp-logo">
                               </li>
                               <li>
                                    Podés ver la forma de envíos
                                    <router-link :to="{ name: 'website.ships' }">
                                       Aquí
                                    </router-link>
                               </li>
                           </ul>
                        </div>
                    <!-- </div>
                </div> -->
            </div>
        </div>    
    </div>
</template>

<script>
    import {gsap} from 'gsap';
    import {ScrollToPlugin} from 'gsap/ScrollToPlugin'     
    export default {


        metaInfo() {
             return {
                title: 'Como comprar',
                }
        },

        props:{
            
        },

        components:{
            
        },

        data () {
            return {
                
            }
        },

        computed: {
            
        },

        created () {
          if(this.$router.currentRoute.params.scroll){
                this.scrollToCustom(this.$router.currentRoute.params.scrollTo)
            }
        },

        methods: {
           scrollToCustom(selector = 'product-list') {
                let wait = 0;
                if (window.innerWidth < 992) {
                    this.menuChange();
                    wait = 2;
                }
                gsap.registerPlugin(ScrollToPlugin);
                gsap.to(window, {duration: 1, scrollTo: {y: "#" + selector, offsetY: 50}, delay: wait});
            },
        },
    }
</script>
