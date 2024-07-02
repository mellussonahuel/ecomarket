<template>
    <div id="favorites" class="favorites">
        <!-- Breadcrumb Section Begin -->
        <!-- style="background-image: url('img/breadcrumb.jpg');" -->
    <section class="breadcrumb-section set-bg" >
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                   
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
                                    <th class="shoping__product">Producto</th>
                                    <th>Precio</th>
                                    <th>Ver</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <tr v-if="!favorites.length">
                                    <td colspan="5" class="text-center"> Aún no tenés favoritos.</td>
                                </tr>
                                <template v-else>
                                     <fav-item v-for="(item,index) in getFavorites" :key="index"
                                        @remove="removeFavorite(item.id)"
                                        :item="item"
                                    ></fav-item>   
                                </template>
                                                      
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__btns">
                        
                        <template>
                            <router-link class="primary-btn cart-btn" :to="{ name: 'website.shop' }" >
                                Seguir comprando
                            </router-link>
                        </template>
                         <!-- -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Shoping Cart Section End -->
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import Form from 'vform'
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import FavItem from './partials/favitem'
    export default {

        metaInfo () {
            return { title: 'Mis favoritos' }
        },

        components:{
            FavItem
        },

        middleware: 'auth',

        mounted () {
        },

        data () {
            return {
                form: new Form({
                    name: '',
                    email: '',
                    message:'',
                    phone: '',
                }),
                favorites:[]
            }
        },

        computed: {
          getFavorites(){
              return this.favorites
          }
        },

        created () {
            this.fetchFavorites()
        },

        methods: {
            cleanCartConfirmation(){

                var self = this
                Swal.fire({
                    text: '¿ Querés eliminar tu lista de favoritos ?.',
                    type: 'warning',
                    confirmButtonText: 'Ok',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        self.cleanFavorites()
                    }
                })

            },

           async cleanCart(){

                await this.$store.commit('auth/cleanCart')

           },
           
           async fetchFavorites(){
                
                const { data } = await axios.get(`/api/get-favorites`)
                this.favorites = data 

           },
           
           async removeFavorite(id){
                
                const { data } = await axios.get(`/api/remove-favorite/${id}`)
                await this.$store.commit('auth/removeFavorite',id)
                this.favorites = data 

            }
        },
    }
</script>
