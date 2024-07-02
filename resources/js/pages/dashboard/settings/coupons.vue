<template>
    <div id="dashboard-newsletter">
            <card :title="'Cupones'" id="">
                <div class="head">
                    <small>Listado de cupones</small>
                    <button class="btn btn-action" @click="create"><i class="fas fa-plus"></i></button>
                </div>
              
                <div class="newsletter-table">
                    <div class="row mt-5">
                        <div class="col-12 text-center "  v-if="loading">
                             <div class="load-spinner" >
                                <span>
                                    <i class="fad fa-spinner-third fa-spin"></i>
                                </span>
                            </div>
                        </div>
                        <transition name="fade" mode="out-in">
                             <div class="col-12" v-if="!loading">
                                <table class="table table-sales">
                                    <thead>
                                        <tr>
                                        <th class="text-center" scope="col">Usuario</th>
                                        <th class="text-center" scope="col">Códico</th>
                                        <th class="text-center" scope="col">Tipo</th>
                                        <th class="text-center" scope="col">%</th>
                                        <th class="text-center" scope="col">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(coupon,index) in coupons" :key="index">
                                        <td class="text-center" scope="row">{{coupon.email}}</td>
                                        <td class="text-center" scope="row">{{ coupon.code }}</td>
                                        <td class="text-center" scope="row">{{ coupon.type === 'ONE_USE' ? 'Un uso' : 'Ilimitado' }}</td>
                                        <td class="text-center" scope="row">{{ coupon.amount }}</td>
                                        <td class="text-center"> 
                                            <button class="btn btn-delete" @click="deleteConfirmation(coupon.id)"><i class="fad fa-trash-alt"></i></button>
                                             <button class="btn btn-action" @click="editItem(coupon)"><i class="fad fa-pencil"></i></button>
                                        </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <paginator :limit="3" :data="paginationData" @pagination-change-page="getCoupons" ></paginator>
                            </div>
                        </transition> 
                       
                    </div>
                </div>
            </card>
            <!--MODAL COUPON-->
           
            <Modal v-if="showModalCoupon" :show-footer="false" @close="showModalCoupon = false">
                <h3 slot="header">
                    {{ getTitle }}
                </h3>
                <template slot="body">
                    <CouponForm @close="showModalCoupon = false" :edit="edit"/>
                </template>
            </Modal>
            <!-- END MODAL ///-->
    </div>
</template>

<script>
    
    import { mapGetters } from 'vuex'
    import Swal from 'sweetalert2'
    import Form from "vform"
    import Modal from './../../../components/Modal'
    import Paginator from 'laravel-vue-pagination'
    import CouponForm from '../partials/CouponForm'
    
    export default {

        middleware: 'admin',

         metaInfo () {
            return { title: 'Admin | coupons' }
        },
        
        components: {
            CouponForm,
            Paginator,
            Modal
        },

         data () {
            return {
                
                item: {},
                edit:false,
                form: new Form(),
                loading:false,
                showModalCoupon:false
            }
        },


         async created () {
              this.getCoupons()
        },

        mounted(){
           
        },

        computed: {
            ...mapGetters({
                user: 'auth/user',
                coupons: 'coupons/coupons',
                paginationData : 'coupons/pagination',
                page:'coupons/page',
            }),
            
            getTitle(){
                return this.edit ? 'Editar cupón' : 'Crear cupón' 
            }
           
        },

        methods: {
            async getCoupons(pag = 1){
                
               this.loading = true
               await this.$store.dispatch('coupons/fetchCoupons', {
                    pag: pag
                })
                this.loading = false
           },
            async editItem(slug){
           },
            getFormattedDate(date){
               return  this.moment(date).format('DD/MM/YYYY')
            },
            async create(){
                
            },
            async editItem(coupon){
                this.edit = true
                await this.$store.commit('coupons/setCoupon', coupon)
                this.showModalCoupon = true
           },
            async create(){
                this.edit = false
                await this.$store.commit('coupons/setCoupon', null)
                this.showModalCoupon = true
            },
            deleteConfirmation(id){
                 var self = this
                  Swal.fire({
                    text: 'Eliminar registro ?',
                    type: 'warning',
                    confirmButtonText: 'Ok',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        self.deleteItem(id)
                    }
                })
            },
            async deleteItem(id){
               await this.form.delete(`/api/coupons/${id}`)
                if (this.form.successful) {
                    // pull premises after insert.
                    this.getCoupons()
                    this.toast('Registro eliminado!.')
                    
                }else{
                    this.toast('Error!.')
                }
            },
        }
    }
</script>
