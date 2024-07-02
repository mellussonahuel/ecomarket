<template>
    <div id="contact-home"> 
        <!-- style="background-image: url('img/contacto.png');" -->
         <section class="breadcrumb-section set-bg" >
        <div class="container">
            <div class=" banner-contact">
                <div class=" text-center">
                    <!-- <img src="/img/contact.png" alt=""> -->
                    <!-- <div class="breadcrumb__text">
                        <h2>Contactanos</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.html">Inicio</a>
                            <span>Contactanos</span>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Contact Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-6 col-sm-6 text-center">
                    <div class="contact__widget">
                        <a class="whatsapp" href="https://api.whatsapp.com/send?phone=5492235764153&text=Hola, tengo una consulta sobre " target="_blank">
                            <span class="icon_phone"><i class="fab fa-whatsapp"></i></span>
                            <label>223 5-764153</label>
                        </a>
                        
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 text-center">
                    <div class="contact__widget">
                        <span class="icon_mail_alt"><i class="fal fa-envelope"></i></span>
                        <label>hola@ecomarketonline.com.ar</label>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->


    <!-- Contact Form Begin -->
    <div class="contact-form spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact__form__title">
                        <label>
                            Tenés alguna inquietud, sugerencia o sólo palabras lindas para decirnos?
                            Sos emprendedor y querés que vendamos tus productos? 
                        </label> 
                        <h2>
                            Contactános  
                        </h2>
                    </div>
                </div>
            </div>
            <form @submit.prevent="send" @keydown="form.onKeydown($event)">
                 <div class="form-group row">
                     <div class="col-12 position-relative">
                        <input type="email" placeholder="Email" v-model="form.email" class="form-control" name="email" :class="{ 'is-invalid': form.errors.has('email') }" >
                        <has-error :form="form" field="email"/>
                    </div>
                    <div class="col-lg-6 col-md-6 position-relative">
                        <input type="text" placeholder="Nombre" v-model="form.name" class="form-control" name="name"  :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"/>
                    </div>
                    <div class="col-lg-6 col-md-6 position-relative">
                        <input type="text" placeholder="telefono" v-model="form.phone" class="form-control" name="phone"  :class="{ 'is-invalid': form.errors.has('phone') }">
                        <has-error :form="form" field="phone"/>
                    </div>
                    <div class="col-lg-12 text-center position-relative">
                        <textarea placeholder="Mensaje"
                        v-model="form.message"
                        class="form-control"  
                        name="message"
                        :class="{ 'is-invalid': form.errors.has('message') }"
                        ></textarea>

                        <has-error :form="form" field="message"/>
                        <!-- <button type="submit" class="site-btn">ENVIAR MENSAJE</button> -->
                        

                    </div>
                    <div class="col-12 text-center">
                        <v-button :loading="form.busy" class="site-btn">
                            ENVIAR MENSAJE
                        </v-button>
                    </div>
                </div>
            </form>
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