<template>
        <div class="category-item-container">
            <div @click="toggle" class="category-item position-relative">
                    <span class="my-auto">
                    {{category.description}}
                    <i  :class="getIcon"></i>
                </span>
            </div>
            <transition name="fade" mode="out-in">
                <div class="sub-item-list" v-if="showChilds">
                    <div class="category-item" :key="'all'"  @click.prevent="searchByCategory(category)">
                        <span class="sub-item-description">
                           Ver todo 
                        </span>
                    </div>
                    <template >
                        <div class="category-item" v-for="(child,index) in  getChilds" :key="index"  @click.prevent="searchByCategory(child)">
                            <span class="sub-item-description">
                                {{ child.description }}
                            </span>
                        </div>  
                    </template>   
                        
                </div>
            </transition>
        </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import {gsap} from 'gsap';
    import {ScrollToPlugin} from 'gsap/ScrollToPlugin'
    import Swal from 'sweetalert2'

    export default {
        name:'category-item',
        
        props:{
            category:{
                type:Object,
                required:true,
            }
        },
        components: {
           
        },

        data: () => ({
            selectedCategory:'', 
            selectedParent:'',
            showChilds:false,       
        }),

        async created () {
          
        },

        computed: {
            ...mapGetters({
                selected: 'categories/category'
            }),
            getChilds(){
                return this.category.childrens
            },
            getIcon(){
                return this.showChilds ? 'fad fa-angle-up' : 'fad fa-angle-down'
            }

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
                var params = {}
                
                if(category.parent_id === null){
                    params.parent_id = category.id
                }else{
                    params.category = category.id
                }
                this.$store.commit('product/setFilter',
                    { 
                        type:'category', 
                        label : category.description , 
                        slug: category.slug, 
                        id : category.id ,
                        parent : category.parent_id === null
                    })

                if(this.$router.currentRoute.name !== 'website.shop'){
                    params.scroll = true
                    params.scrollTo = 'product-list'
                    this.$router.push({ name: 'website.shop', params })
                }else{
                    params.pag = 1
                    await this.$store.dispatch('product/filter', params)
                    this.scrollToCustom('product-list')
                }
                
                this.toggle()
                
            },
            toggle(category){
                this.showChilds = !this.showChilds
            },
           
            scrollToCustom(selector) {
                let wait = 0;
                // if (window.innerWidth < 992) {
                //     wait = 2;
                // }
                gsap.registerPlugin(ScrollToPlugin);
                gsap.to(window, {duration: 1, scrollTo: {y: "#" + selector, offsetY: 50}, delay: wait});
            },
           
        },
        
    }
</script>
