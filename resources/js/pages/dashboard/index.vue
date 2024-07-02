<template>
    <div class="row" id="admin-area">
        <div class="col-md-12" v-if="!isIndex">
            <transition name="fade" mode="out-in">
                <router-view/>
            </transition>
        </div>

        <div class="col-md-12" v-else>
            
            <Home/>
        </div>
    </div>
</template>

<script>
    import Home from './home.vue'
    import { mapGetters } from 'vuex'
    import Swal from 'sweetalert2'
    

    export default {
        layout: 'DashboardLayout',
        middleware: 'admin',
        components: {
            Home
        },

        async created () {
            this.fetchUnread()
            setInterval(() => {
                    this.fetchUnread()
                }, 300000)
        },

        computed: {
            ...mapGetters({
                user: 'auth/user',
            }),
            isIndex () {
               
                return this.$route.name === 'dashboard.index'
            },
        },

        methods: {
           async fetchUnread(){
               await this.$store.dispatch('sales/fetchUnread')
           }
        }
    }
</script>
