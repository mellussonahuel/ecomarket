<template>
    <div id="sidebar-menu" class="sidebar">
        <div class="sidebar-backdrop" @click="closeSidebarPanel" v-if="isSidebarOpen"></div>
        <transition name="slide">
            <div v-if="isSidebarOpen"
                 class="sidebar-panel">
                <slot></slot>
            </div>
        </transition>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex'
    import Swal from 'sweetalert2'
    export default {

        mounted () {
        },

        data () {
            return {
                isPanelOpen: true
            }
        },

        components:{
           
        },

        computed: {
            ...mapGetters({
                 isSidebarOpen: 'settings/showSidebar'
            }),
        },

        methods: {
            closeSidebarPanel() {
                this.$store.commit('settings/closeSidebar')
            }
        }
    }
</script>
