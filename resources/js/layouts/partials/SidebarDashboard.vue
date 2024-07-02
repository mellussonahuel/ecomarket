<template>
  <div>
    <!-- <div class="row">
      <div class="col-12 mb-5">
        <div class="burger">
          <button
            class="burger-button-menu btn-primary inverse"
            @click="showBurgerMenu = !showBurgerMenu"
          >
            <i class="fad fa-bars"></i>
          </button>
          <ul v-if="showBurgerMenu">
            <li class="item">
              <router-link
                :to="{ name: 'dashboard.index' }"
                class="item-container"
                active-class="active"
                exact
              >
                <i class="fas fa-th-large"></i>
              </router-link>
            </li>
            <li class="item">
              <router-link
                :to="{ name: 'dashboard.products' }"
                class="item-container"
                active-class="active"
                exact
              >
                <i class="fal fa-wheat"></i>
              </router-link>
            </li>
            <li class="item">
              <router-link
                :to="{ name: 'dashboard.sales' }"
                class="item-container"
                active-class="active"
                exact
              >
                <i class="fad fa-cash-register"
                  ><span class="sale-badge">{{ getUnread }}</span></i
                >
              </router-link>
            </li>
            <li class="item">
              <router-link
                :to="{ name: 'dashboard.users' }"
                class="item-container"
                active-class="active"
                exact
              >
                <i class="fad fa-users"></i>
              </router-link>
            </li>
            <li class="item">
              <router-link
                :to="{ name: 'dashboard.reports' }"
                class="item-container"
                active-class="active"
                exact
              >
                <i class="fad fa-list-ul"></i>
              </router-link>
            </li>
            <li class="item">
              <router-link
                :to="{ name: 'dashboard.settings' }"
                class="item-container"
                active-class="active"
                exact
              >
                <i class="fas fa-cog"></i>
              </router-link>
            </li>
          </ul>
        </div>
      </div>
    </div> -->

    <div class="sidebar" :class="isViewport('sm-down') ? 'closed' : ''">
      <div class="left">
        <div class="first-block">
          <i class="fas fa-times" @click.prevent="logout"></i>
        </div>

        <div class="items">
          <div class="item">
            <router-link
              :to="{ name: 'dashboard.index' }"
              class="item-container"
              active-class="active"
              exact
            >
              <i class="fas fa-th-large"></i>
            </router-link>
          </div>
          <div class="item">
            <router-link
              :to="{ name: 'dashboard.products' }"
              class="item-container"
              active-class="active"
              exact
            >
              <i class="fal fa-wheat"></i>
            </router-link>
          </div>
          <div class="item">
            <router-link
              :to="{ name: 'dashboard.sales' }"
              class="item-container"
              active-class="active"
              exact
            >
              <i class="fad fa-cash-register"
                ><span class="sale-badge">{{ getUnread }}</span></i
              >
            </router-link>
          </div>
          <div class="item">
            <router-link
              :to="{ name: 'dashboard.users' }"
              class="item-container"
              active-class="active"
              exact
            >
              <i class="fad fa-users"></i>
            </router-link>
          </div>
          <div class="item">
            <router-link
              :to="{ name: 'dashboard.reports' }"
              class="item-container"
              active-class="active"
              exact
            >
              <i class="fad fa-list-ul"></i>
            </router-link>
          </div>
          <div class="item">
            <router-link
              :to="{ name: 'dashboard.settings' }"
              class="item-container"
              active-class="active"
              exact
            >
              <i class="fas fa-cog"></i>
            </router-link>
          </div>
          <!--<div class="item">
                    <router-link :to="{ name: 'dashboard.manager' }" class="item-container" active-class="active" exact>
                        <i class="fas fa-tasks"></i>
                    </router-link>
                </div>
                <div class="item">
                    <router-link :to="{ name: 'dashboard.reports' }" class="item-container" active-class="active" exact>
                        <i class="fas fa-chart-pie-alt"></i>
                    </router-link>
                </div>
                <div class="item">
                    <router-link :to="{ name: 'dashboard.settings' }" class="item-container" :class="isSettings ? 'active' : ''">
                        <i class="fas fa-cog"></i>
                    </router-link>
                </div> -->
        </div>
      </div>
      <div class="right">
        <div class="first-block">
          <div class="logo">
            <router-link :to="{ name: 'dashboard.index' }" class="navbar-brand">
              <img src="/img/eco-logo-up.png" alt="" class="mt-5" />
            </router-link>
          </div>
        </div>
        <div class="items">
          <router-link
            :to="{ name: 'dashboard.index' }"
            class="item"
            active-class="active"
            exact
          >
            {{ $t("dashboard") }}
          </router-link>
          <router-link
            :to="{ name: 'dashboard.products' }"
            class="item"
            active-class="active"
            exact
          >
            Productos
          </router-link>
          <router-link
            :to="{ name: 'dashboard.sales' }"
            class="item"
            active-class="active"
            exact
          >
            Ventas
          </router-link>
          <router-link
            :to="{ name: 'dashboard.users' }"
            class="item"
            active-class="active"
            exact
          >
            Usuarios
          </router-link>
          <router-link
            :to="{ name: 'dashboard.reports' }"
            class="item"
            active-class="active"
            exact
          >
            Reportes
          </router-link>
          <router-link
            :to="{ name: 'dashboard.settings' }"
            class="item"
            active-class="active"
            exact
          >
            Configuración
          </router-link>
          <!--<router-link :to="{ name: 'dashboard.manager' }" class="item" active-class="active" exact>
                    {{ $t('manager') }}
                </router-link>
                <router-link :to="{ name: 'dashboard.reports' }" class="item" active-class="active" exact>
                    {{ $t('reports') }}
                </router-link>
                <router-link :to="{ name: 'dashboard.settings' }" class="item" :class="isSettings ? 'active' : ''">
                    {{ $t('settings') }}
                </router-link> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "SidebarDashboard",

  components: {},

  data: () => ({
    appName: window.config.appName,
    currentPath: window.location.pathname,
    showBurgerMenu: false
  }),

  computed: {
    ...mapGetters({
      user: "auth/user",
      unread: "sales/unread"
    }),
    isSettings() {
      return this.currentPath.indexOf("settings") !== -1;
    },
    getUnread() {
      return this.unread;
    }
  },

  methods: {
    updateUnread() {
      // this.$store.dispatch('chat/getUnreadCount', {
      //     resource_id: this.company.id,
      //     type: 'company'
      // })
    },
    readQueueNotification() {
      // this.$store.commit('queues/setNotificationQueueCount', 0)
    },
    async logout() {
      // Log out the user.
      await this.$store.dispatch("auth/logout");

      // Redirect to login.
      this.$router.push({ name: "website.home" });
    }
  },

  watch: {
    $route() {
      this.currentPath = window.location.pathname;
    }
  }
};
</script>

<style scoped></style>
