<template>
    <div class="row" id="dashboard-home">
     
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
           
        }
    }
</script>
