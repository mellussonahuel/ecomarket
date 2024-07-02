<template>
    <transition name="fade" mode="out-in">
        <tr class="fav-item">
            <td class=" shoping__cart__item">
                <router-link :to="{ name: 'website.productdetail',params : { slug: currentItem.slug} }" class="item">
                    <img :src="getImage" alt="">
                </router-link>
                <h5>{{ currentItem.title }}</h5>
            </td>
            <td class="text-center shoping__cart__price">
            $ {{getPrice}}
            </td>
            <td class="text-center shoping__cart__item__close">
                <router-link :to="{ name: 'website.productdetail',params : { slug: currentItem.slug} }" class="item">
                    <i class="fad fa-eye show-product"></i>
                </router-link>
            </td>
            <td class="text-center shoping__cart__item__close">
                <i class="far fa-times-circle  text-danger" @click="deleteConfirmation"></i>
            </td>
        </tr>
    </transition>
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
        data: () => ({
            currentItem:{}
        }),
        created(){
            this.currentItem = this.item
        },
        computed: {
            ...mapGetters({
                 
            }),
             getPrice(){
                return this.item.sale_type === 'Unidad' ? this.item.price : this.item.variants[0].price
             },
             getImage(){
                return this.item.images.length ?  '/productos/'+ this.item.images[0].name : '/img/no-image.png'
            }
                
        },
        methods: {
            deleteConfirmation(){
                var self = this
                    Swal.fire({
                    text: '¿ Querés remover este favorito ?.',
                    type: 'warning',
                    confirmButtonText: 'Ok',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        self.removeFavorite()
                    }
                })
            },
           async removeFavorite(){
                await this.$store.commit('auth/setFavorite', this.currentItem.id)
                await this.$store.dispatch('auth/setFavorites')
                this.$emit("remove");
           },
           
        }
    }
</script>
<style>