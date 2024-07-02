<template>
    <div id="ships">

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
                           <p>
                               Realizamos envíos sin cargo en toda la ciudad con un mínimo de compra de $900.- 
                               (el importe que se toma en cuenta es el subtotal previo los descuentos). <br><br>

                                Las entregas se realizan los días Lunes, Miercoles y Viernes entre las 17 y las 20 hs. 
                                <br>El envío se podrá realizar en el mismo día si el pedido ingresa antes de las 12 hs); 
                                caso contrario pasan al día siguiente (excepto en el caso de los sábados y domingos que se realizan el día lunes o los 
                                feriados que se trasladan al siguiente día hábil).<br><br>

                                Te enviaremos un whatsapp con una franja horaria estimada para el 
                                arribo de tu pedido (los horarios se pueden ver ligeramente alterados 
                                por cuestiones de tráfico, demora en las entregas u otros imprevistos). 
                                <br><br>Te llamaremos en el caso que no nos atiendas. 
                           </p>
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

        created () {
            
            if(this.$router.currentRoute.params.scroll){
                console.log('tambien entra')
                this.scrollToCustom(this.$router.currentRoute.params.scrollTo)
            }
        },

        components:{
            
        },

        data () {
            return {
                
            }
        },

        computed: {
            
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
