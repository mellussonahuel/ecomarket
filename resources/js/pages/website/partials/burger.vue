<template>
    <div id="burger"
         @click.prevent="toggle">
        <slot>
            <button type="button" class="burger-button" title="Menu">
               <i class="far fa-bars"></i>
            </button>
        </slot>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex'
    export default {
        data: () => ({
            isBurgerActive: false
        }),
        computed: {
            ...mapGetters({
                 isSidebarOpen: 'settings/showSidebar'
            }),
            open(){
                return this.isSidebarOpen
            }
           
           
        },
        methods: {
            toggle() {
                this.$store.commit('settings/toggleSidebar')
                this.$store.commit('categories/setShowNav',false)
                
            }
        }
    }
</script>
<style>