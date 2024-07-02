<template>
    <div class="row" id="dashboard-settings">
        <div class="col-md-3">
            <card :title="$t('settings')" class="settings-card">
                <ul class="nav flex-column nav-pills">
                    <li v-for="tab in tabs" :key="tab.route" class="nav-item">
                        <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
                            {{ tab.name }}
                        </router-link>
                    </li>
                </ul>
            </card>
        </div>

        <div class="col-md-9">
            <transition name="fade" mode="out-in">
                <router-view />
            </transition>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import Swal from 'sweetalert2'

    export default {
        middleware: 'admin',

        components: {

        },

        async created() {
        },

        computed: {
            ...mapGetters({
                user: 'auth/user',
            }),
            tabs () {
                return [
                    {
                        icon: 'list',
                        name: 'Categorías',
                        route: 'dashboard.settings.categories'
                    },
                    {
                        icon: 'list',
                        name: 'Newsletter',
                        route: 'dashboard.settings.newsletter'
                    },
                     {
                        icon: 'list',
                        name: 'Cupones',
                        route: 'dashboard.settings.coupons'
                    },
                     {
                        icon: 'list',
                        name: 'Localidades',
                        route: 'dashboard.settings.locations'
                    },
                     {
                        icon: 'list',
                        name: 'Exportar - importar',
                        route: 'dashboard.settings.importsExports'
                    },
                ]
            }
        },

        methods: {
        }
    }
</script>
