<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">

            <!--Logo only mobile-->
            <div class="logo">
                <router-link :to="{ name: 'dashboard.index' }" class="navbar-brand">
                    <img src="../../../img/logo.png" alt="">
                </router-link>
            </div>

            <div class="left-options">
                <div class="option-container">
                    <div class="icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="content">
                        View Calendar
                    </div>
                </div>

                <div class="option-container">
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="content">
                        {{ now }}
                    </div>
                </div>
            </div>
            <div class="right-options">

                <div class="notifications" @click="showNotifications =! showNotifications">
                    <div class="icon">
                        <i class="fas fa-bell"></i>
                        <div class="badge orange">
                            {{notifications.unread}}
                        </div>
                    </div>
                </div>

                <div class="user-drop-down" v-if="user" @click="showDropdown = !showDropdown">
                    <div class="image">
                        <img :src="user.photo_url" class="rounded-circle">
                    </div>
                    <div class="details">
                        <div class="name">{{ user.name }}</div>
                        <div class="role">
                            <span v-if="hasRole('Client')">Client</span>
                            <span v-if="hasRole('Merchant')">Merchant</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down"></i>

                    <div class="drop-down-list-user" v-if="showDropdown">

                        <router-link :to="{ name: 'app.home' }" class="item">
                            {{ $t('go_to_app') }}
                            <i class="fal fa-rocket-launch"></i>
                        </router-link>

                        <router-link :to="{ name: 'home' }" class="item">
                            {{ $t('return_to_website') }}
                            <i class="fal fa-hot-tub"></i>
                        </router-link>

                        <router-link :to="{ name: 'dashboard.settings.profile' }" class="item">
                            {{ $t('profile') }}
                            <i class="fal fa-user-astronaut"></i>
                        </router-link>

                        <a href="#" class="item" @click.prevent="logout">
                            {{ $t('logout') }}
                            <i class="fal fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>

            </div>


        </div>
    </nav>
</template>

<script>
    import { mapGetters } from 'vuex'
    import LocaleDropdown from '../../components/LocaleDropdown'

    export default {
        name: 'NavbarDashboard',

        components: {
            LocaleDropdown
        },

        data: () => ({
            appName: window.config.appName,
            showLogo: false,
            now: null,
            showDropdown: false,
            showNotifications: false,
        }),

        mounted () {
            setInterval(() => {
                this.now = this.moment().format('ddd, MMMM Do YYYY, h:mm:ss a')
            }, 1000)
        },

        created() {
            window.addEventListener("click", this.hide);
        },

        beforeDestroy() {
            window.removeEventListener("click", this.hide);
        },

        computed: {
            ...mapGetters({
                user: 'auth/user',
                company: 'companies/company',
                notifications: 'notifications/notifications'
            })
        },

        methods: {
            async logout () {
                // Log out the user.
                await this.$store.dispatch('auth/logout')

                // Redirect to login.
                this.$router.push({ name: 'login' })
            },

            hide (e) {
                let el = document.querySelector('.user-drop-down')
                if (!el.contains(e.target)) {
                    this.showDropdown = false
                }
            },
        },

        watch: {
            showNotifications () {
                this.$store.commit('notifications/setShowNotifications', this.showNotifications)
            }
        }
    }
</script>

