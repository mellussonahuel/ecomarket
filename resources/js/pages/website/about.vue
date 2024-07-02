<template>
    <div id="about-us"> 
        <!-- style="background-image: url('img/contacto.png');" -->
        <section class="breadcrumb-section set-bg" >
            <div class="container">
                <div class="row banner-contact">
                    <div class="col-lg-12 text-center">
                        <img src="/img/about.png" alt="">
                    </div>
                </div>
            </div>
        </section>
        <!-- Breadcrumb Section End -->

        <!-- Contact Form Begin -->
        <div class="about-us spad">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8">
                         <p>
                            Queremos ser el punto de partida para que recorramos juntos el camino
                            del consumo consciente.<br>
                            En ECOMARKET vas a encontrar todo lo que buscas en una tienda
                            saludable pero desde la comodidad de tu casa.
                            Vas a poder elegir tus productos conociendo qué contienen y cómo
                            incorporarlos en tu vida diaria.<br><i class="fas fa-leaf-heart"></i>
                        </p>
                    </div>  
                </div>
               
            </div>
        </div>
        <!-- Contact Form End -->
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import Form from 'vform'
    import axios from 'axios'
    import Swal from 'sweetalert2'
    export default {

        metaInfo () {
            return { title: 'Contacto' }
        },

        mounted () {
        },

        data () {
            return {
                form: new Form({
                    name: '',
                    email: '',
                    message:'',
                    phone: '',
                })
            }
        },

        computed: {
            ...mapGetters({
               
            }),
        },

        created () {
            //
        },

        methods: {
            async send(){
                try{
                     await this.form.post('/api/contact/send')
                    if (this.form.successful) {
                            Swal.fire({
                                type: 'success',
                                text: 'Mensaje enviado !.',
                                confirmButtonText: 'Ok',
                            })
                            this.form.reset()
                    }else {
                  
                    }

                }catch(error){
                     console.error('Check your data', error)
                
                }
        
            },
        },
    }
</script>
<style lang="scss" scoped>
    .invalid-feedback{
        position: absolute;
        bottom: 5px;
    }
</style>