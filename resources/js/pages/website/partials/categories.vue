<template>
    <div id="categories">
        <div id="categories-list" class="">
            <category-item  v-for="(category,index) in getParents" :key="index"
                :category="category"
            ></category-item>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import CategoryItem from './category-item'
    import Swal from 'sweetalert2'

    export default {
        name:'categories',
        
        components: {
           CategoryItem
        },

        data: () => ({
            selectedCategory:'', 
            selectedParent:'',       
        }),

        async created () {
          
        },

        computed: {
            ...mapGetters({
                user: 'auth/user',
                categories : 'categories/categories',
                selected: 'categories/category'
            }),
            getSelectedSlug(){
                return this.selected ? this.selected.slug : ''
            },
            getParents(){
                return this.categories ?  this.categories.filter( c => c.parent_id === null && c.active === 1 ) : []
            },
            getChilds(){
                return this.childs
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

                if(this.$router.currentRoute.name !== 'website.shop'){
                    this.$router.push({ name: 'website.shop', params: { category : category.id } })
                }else{
                    await this.$store.dispatch('product/filter', {
                        category: category.id,
                        pag:1
                    })
                }
            },
            toggleChilds(category){
                this.childs = this.categories.filter( c => c.parent_id === category.parent_id )
                this.selectedParent = category.slug
            }
        }
    }
</script>
