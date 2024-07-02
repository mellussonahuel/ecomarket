<template>
    <div id="latest">
         <router-link :to="{ name: 'website.productdetail',params : { slug: product.slug} }" class="latest-product__item">
            <div class="latest-product__item__pic">
                <img :src="getImage" alt="product-image">
            </div>
            <div class="latest-product__item__text">
                <h6>{{product.title}}</h6>
                <span>${{ getPrice }} </span>
            </div>
        </router-link>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import Swal from 'sweetalert2'

    export default {
        name:'latest',
        
        props:{
            product:{
                type:Object,
                required:true
            }
        },

        components: {
           
        },

        data: () => ({
                 
        }),

        async created () {
          
        },

        computed: {
            ...mapGetters({
               
            }),
            getImage(){
                return this.product.images.length ?  '/productos/'+ this.product.images[0].name : '/img/no-image.png'
            },
            getPrice(){
                return this.product.sale_type === 'Unidad' ? this.product.price : this.product.variants[0].price
             },
        },

        methods: {
           async removeCategory(){
                this.$store.commit('categories/setCategory', '')
                await this.$store.dispatch('product/filter', {}) 
            },
            async searchByCategory(category){
        
                this.$store.commit('categories/setCategory',category)
                
                this.showCategories = false
                
                this.$store.commit('categories/setShowNav',false)

                if(this.$router.currentRoute.name !== 'website.shop'){
                    this.$router.push({ name: 'website.shop', params: { category : category.id } })
                }else{
                    await this.$store.dispatch('product/filter', {
                        category: category.id,
                        pag:1
                    })
                }
            },
        }
    }
</script>
