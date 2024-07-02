<template>
         <tr id="cart-item" v-if="item">
            <td class="shoping__cart__item">
                <img :src="getImage" alt="">
                <h5>{{ item.product_title }}</h5>
            </td>
            <td class="shoping__cart__price">
                
                ${{getPrice}}
            </td>
            <td class="shoping__cart__quantity">
                <div class="quantity">
                    <div class="pro-qty">
                        <i class="fas fa-chevron-left" @click.prevent="qtyMinus" ></i>
                        <input type="tel" v-model="currentItem.qty">
                        <i class="fas fa-chevron-right" @click.prevent="qtyPlus"></i>
                    </div>
                </div>
            </td>
            <td class="shoping__cart__price ">
                    %{{item.product_discount}}
            </td>
            <td class="shoping__cart__total" v-if="item">
                ${{ currentItem.total }}
            </td>
            <td class="shoping__cart__item__close">
                <i class="far fa-times-circle text-danger" @click="deleteConfirmation"></i>
            </td>
        </tr>
    
</template>
<script>
    import { mapGetters } from 'vuex'
    import Swal from 'sweetalert2'
    export default {
        props:{
            item:{
                type:Object,
                required:true,
            }
        },
        data(){
             return {
                currentItem:{},
                total:0,
                
            }
        }
           
        ,
        mounted(){
            this.currentItem = Object.assign({},this.item)
        },
        computed: {
             getPrice(){

                 if(this.item.sale_type === 'Unidad' ){

                    return this.item.product_price

                 }else{

                   
                    return this.item.variant_price

                 }
             },
             getTotal(){
                 return this.total
             },
             getImage(){
                return   '/productos/'+ this.item.product_image
            }
                
        },
        methods: {
            qtyPlus(){
               this.currentItem.qty++
               this.updateCart()
           },
           qtyMinus(){
               if(this.currentItem.qty > 1 )
                this.currentItem.qty--
                this.updateCart()
           },
           async updateCart(){
            
                this.total = this.getItemTotal(
                    this.currentItem.sale_type,
                    this.currentItem.product_price,
                    this.currentItem.qty,
                    this.currentItem.product_discount,
                    this.currentItem.variant_price
                )
                this.currentItem.total = this.total
                await this.$store.commit('cart/addItem', this.currentItem)
                this.confirmMsg()
           },

            confirmMsg(){
                Swal.fire({
                    toast:true,
                    position: 'top-end',
                    text:'Producto actualizado !.',
                    showConfirmButton: false,
                    timer: 3000,
                })
           },
            deleteConfirmation(){
                var self = this
                    Swal.fire({
                    text: '¿ Querés remover este producto del carro ?.',
                    type: 'warning',
                    confirmButtonText: 'Ok',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        self.removeFromCart()
                    }
                })
            },
            removeFromCart(){
                 this.$store.commit('cart/removeItem', this.currentItem.id)
           },
           
        },
        watch: {
            item: function (val) {
                this.currentItem = val
            },
            
        }
        
    }
</script>
<style>