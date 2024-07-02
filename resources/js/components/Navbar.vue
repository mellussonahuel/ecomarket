<template>
  <header>
    <sidebar>
      <div class="row">
        <div class="col-12 close">
          <i class="fas fa-times" @click.prevent="toggle"></i>
        </div>
        <div class="col-12">
          <template>
            <router-link @click="toggle" :to="{ name: 'website.home' }">
              <img class="sidebar-logo" src="/img/eco-logo-up.png" alt="" />
            </router-link>
          </template>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="header__cart">
            <ul>
              <li>
                <template>
                  <router-link
                    @click="toggle"
                    :to="{ name: 'website.favorites' }"
                  >
                    <i class="far fa-heart"></i>
                    <span> {{ favorites.length }} </span>
                  </router-link>
                </template>
              </li>
              <li>
                <template>
                  <router-link @click="toggle" :to="{ name: 'website.cart' }">
                    <i class="far fa-shopping-cart"></i>
                    <span>{{ count }}</span>
                  </router-link>
                </template>
              </li>
            </ul>
            <div class="header__cart__price">
              Total: <span>${{ total }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="row sidebar-auth">
        <div class="col-12">
          <div class="header__top__right__auth">
            <template v-if="!user">
              <div class="row">
                <div class="col user-login">
                  <router-link
                    @click="toggle"
                    :to="{ name: 'login' }"
                    active-class="active"
                    exact
                  >
                    <i class="far fa-user "></i> Iniciar sesión
                  </router-link>
                </div>
                <div class="col user-register">
                  <router-link
                    @click="toggle"
                    :to="{ name: 'register' }"
                    active-class="active"
                    exact
                  >
                    <i class="far fa-sign-in-alt "></i> Registrarse
                  </router-link>
                </div>
              </div>
            </template>
            <template v-else>
              <template v-if="isAdmin">
                <router-link
                  @click="toggle"
                  :to="{ name: 'dashboard.index' }"
                  class="pl-2"
                  active-class="active"
                  exact
                >
                  <i class="fas fa-cogs"></i>
                </router-link>
              </template>
              <template>
                <router-link
                  @click="toggle"
                  :to="{ name: 'website.profile' }"
                  class="pl-2"
                  active-class="active"
                  exact
                >
                  <i class="fas fa-user"></i>
                </router-link>
              </template>
              <a href="#" @click.prevent="logout">
                <i class="far fa-sign-out"></i> Salir</a
              >
            </template>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 search-phone">
          <div class="d-flex justify-content-center">
            <span class="icon my-auto">
              <i class="fab fa-whatsapp"></i>
            </span>
            <span class="number my-auto">
              <h5>223 5764153</h5>
            </span>
          </div>
        </div>
      </div>
      <div class="row">
        <nav class="pages">
          <ul class="list-unstyled">
            <li class="active">
              <template>
                <router-link
                  @click="toggle"
                  :to="{ name: 'website.home' }"
                  active-class="active"
                  exact
                >
                  Inicio
                </router-link>
              </template>
            </li>
            <li>
              <template>
                <router-link
                  @click="toggle"
                  :to="{ name: 'website.shop',  params: { page: 1 } }"
                  active-class="active"
                  exact
                >
                  Tienda
                </router-link>
              </template>
            </li>

            <!-- <li>
              <template>
                <router-link
                  :to="{ name: 'website.combos', params: { combos: true } }"
                  active-class="active"
                  exact
                >
                  Combos
                </router-link>
              </template>
            </li> -->
            <li>
              <template>
                <router-link
                  @click.native="showPromotions"
                  active-class="active"
                  exact
                  :to="{
                    name: 'website.promos',
                    params: { discount: true ,  page: 1 }
                  }"
                >
                  Combos y promos
                </router-link>
              </template>
            </li>
            <li>
              <template>
                <router-link
                  @click="toggle"
                  :to="{ name: 'website.how.to' }"
                  active-class="active"
                  exact
                >
                  Cómo comprar
                </router-link>
              </template>
            </li>
            <li>
              <template>
                <router-link
                  @click="toggle"
                  :to="{ name: 'website.contact' }"
                  active-class="active"
                  exact
                >
                  Contacto
                </router-link>
              </template>
            </li>
          </ul>
        </nav>
      </div>
      <div class="row">
        <div class="col-12 sidebar-social">
          <a
            href="https://www.facebook.com/Ecomarketonline-100529298492797"
            target="_blank"
            ><i class="fab fa-facebook-f"></i
          ></a>
          <a href="https://www.instagram.com/ecomarketonline_/" target="_blank"
            ><i class="fab fa-instagram"></i
          ></a>
        </div>
      </div>
      <div class="row">
        <div class="col-12 sidebar-contact">
          <ul class="list-unstyled">
            <li><i class="fa fa-envelope"></i>hola@ecomarketonline.com.ar</li>
            <li>
              Envíos gratis para pedidos superiores a <strong>$650</strong>
            </li>
          </ul>
        </div>
      </div>
    </sidebar>

    <!-- Header Section Begin -->
    <header class="header">
      <div class="header__top">
        <div class="container">
          <div class="row">
            <div class="col-lg-8 col-md-8">
              <div class="header__top__left">
                <ul>
                  <li>
                    <i class="fa fa-envelope"></i>hola@ecomarketonline.com.ar
                  </li>
                  <li>
                    Envíos gratis para pedidos superiores a
                    <strong>$650</strong>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-lg-4 col-md-4">
              <div class="header__top__right">
                <div class="header__top__right__social">
                  <template>
                    <router-link
                      :to="{ name: 'website.contact' }"
                      active-class="active"
                      exact
                    >
                      <i class="fal fa-envelope"></i>
                    </router-link>
                  </template>
                  <a
                    href="https://api.whatsapp.com/send?phone=5492235764153&text=Hola, tengo una consulta sobre "
                    target="_blank"
                  >
                    <!-- whatsapp://send?phone=5492235764153&text=example -->

                    <i class="fab fa-whatsapp"></i>
                  </a>

                  <a
                    href="https://www.facebook.com/Ecomarketonline-100529298492797"
                    target="_blank"
                    ><i class="fab fa-facebook-f"></i
                  ></a>
                  <a
                    href="https://www.instagram.com/ecomarketonline_/"
                    target="_blank"
                    ><i class="fab fa-instagram"></i
                  ></a>
                </div>

                <div class="header__top__right__auth">
                  <template v-if="!user">
                    <router-link
                      @click="toggle"
                      :to="{ name: 'login' }"
                      active-class="active"
                      exact
                    >
                      <i class="fa fa-user"></i> Iniciar sesión
                    </router-link>
                    <router-link
                      @click="toggle"
                      :to="{ name: 'register' }"
                      active-class="active"
                      exact
                    >
                      <i class="far fa-sign-in-alt "></i> Registrarse
                    </router-link>
                  </template>
                  <template v-else>
                    <template>
                      <router-link
                        @click="toggle"
                        :to="{ name: 'website.profile' }"
                        class="pl-2"
                        active-class="active"
                        exact
                      >
                        <i class="fas fa-user"></i>
                      </router-link>
                    </template>
                    <a href="#" @click.prevent="logout">
                      <i class="far fa-sign-out"></i> Salir</a
                    >
                  </template>
                  <template v-if="isAdmin">
                    <router-link
                      @click="toggle"
                      :to="{ name: 'dashboard.index' }"
                      class="pl-2"
                      active-class="active"
                      exact
                    >
                      <i class="fas fa-cogs"></i>
                    </router-link>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-lg-3">
            <div class="header__logo">
              <template>
                <router-link :to="{ name: 'website.home' }" class="">
                  <img src="/img/eco-logo-up.png" alt="" />
                </router-link>
              </template>
            </div>
          </div>
          <div class="col-lg-6">
            <nav class="header__menu">
              <ul>
                <!-- <li>
                  <template>
                    <router-link
                      :to="{ name: 'website.home' }"
                      active-class="active"
                      exact
                    >
                      Iniciooooo
                    </router-link>
                  </template>
                </li> -->
                <li>
                  <template>
                    <router-link
                      :to="{ name: 'website.shop',params: { page: 1 } }"
                      active-class="active"
                      exact
                    >
                      Tienda
                    </router-link>
                  </template>
                </li>

                <!-- <li>
                  <template>
                    <router-link
                      :to="{ name: 'website.combos', params: { combos: true } }"
                      active-class="active"
                      exact
                    >
                      Combos
                    </router-link>
                  </template>
                </li> -->

                <li>
                  <template>
                    <router-link
                      @click.native="showPromotions"
                      active-class="active"
                      exact
                      :to="{
                        name: 'website.promos',
                        params: { discount: true ,page:1 }
                      }"
                    >
                      Combos y promos
                    </router-link>
                  </template>
                </li>
                <li>
                  <template>
                    <router-link
                      :to="{ name: 'website.how.to' }"
                      active-class="active"
                      exact
                    >
                      Cómo comprar
                    </router-link>
                  </template>
                </li>
                <!-- <li><a href="#">Pages</a>
                                <ul class="header__menu__dropdown">
                                    <li><a href="./shop-details.html">Shop Details</a></li>
                                    <li><a href="./shoping-cart.html">Shoping Cart</a></li>
                                    <li><a href="./checkout.html">Check Out</a></li>
                                   <li><a href="./blog-details.html">Blog Details</a></li> 
                                </ul>
                            </li> -->
                <!-- <li><a href="./blog.html">Blog</a></li> -->
                <!-- <li>
                  <template>
                    <router-link
                      :to="{ name: 'website.contact' }"
                      active-class="active"
                      exact
                    >
                      Contacto
                    </router-link>
                  </template>
                </li> -->
              </ul>
            </nav>
          </div>
          <div class="col-lg-3">
            <div class="header__cart">
              <ul>
                <li>
                  <template>
                    <router-link :to="{ name: 'website.favorites' }">
                      <i class="far fa-heart"></i>
                      <span> {{ favorites.length }} </span>
                    </router-link>
                  </template>
                </li>
                <li>
                  <template>
                    <router-link :to="{ name: 'website.cart' }">
                      <i class="far fa-shopping-cart"></i>
                      <span>{{ count }}</span>
                    </router-link>
                  </template>
                </li>
              </ul>
              <div class="header__cart__price">
                Total: <span>${{ total }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="humberger__open">
          <burger></burger>
        </div>
      </div>
    </header>
    <!-- Header Section End -->

    <!-- Hero Section Begin -->
    <section class="hero">
      <div class="container">
        <div class="row">
          <div class="col-lg-3">
            <div
              id="nav-categories"
              class=" hero__categories position-relative"
            >
              <div
                class="hero__categories__all"
                @click="
                  $store.commit('categories/setShowNav', !showNavCategories)
                "
              >
                <i class="fas fa-bars"></i>
                <span>CATEGORÍAS</span>
              </div>
              <transition name="fade">
                <categories v-if="showNavCategories"></categories>
              </transition>
            </div>
          </div>
      
          <div class="col-lg-9">
            <div class="hero__search">
              <div class="hero__search__form">
                <form action="#">
                  <div class="row">
                    <div class="col-12 col-sm-9 col-lg-9 search-input">
                      <v-select
                        ref="select"
                        key="1"
                        v-model="term"
                        :reduce="p => p.slug"
                        :label="'title'"
                        :close-on-select="true"
                        :clear-search-on-select="false"
                        placeholder="¿Qué estás buscando?"
                        :options="options"
                        @input="showProduct"
                        :filterable="false"
                        @search="searchByTerm"
                      >
                        <template #spinner="{ loading }">
                          <div
                            style="border-left-color: rgba(88,151,251,0.71)"
                            class="vs__spinner"
                          >
                            <i class="fad fa-spinner-third fa-spin"></i>
                          </div>
                        </template>
                        <template
                          #option="{ title, images ,slug , categories, }"
                        >
                          <!-- <router-link :to="{ name: 'website.productdetail',params : { slug: slug} }" class="item"> -->
                          <!-- <em v-if="slug === 'show-all'">
                            <p class="show-all" @click.prevent="showAll">
                              {{ title }}
                            </p>
                          </em> -->
                          <em class="d-flex search-item">
                            <img
                              class="search-product-img my-auto"
                              :src="getImage(images)"
                            />
                            <p>
                              {{ title }} <br /><span>{{
                                categories.length
                                  ? categories[0].description
                                  : ""
                              }}</span>
                            </p>
                          </em>
                          <!-- </router-link> -->
                        </template>
                        <template v-slot:no-options="{ search, loading }">
                          <template v-if="loading">
                            No tenemos productos para tu búsqueda
                            <em>{{ term }}</em
                            >.
                          </template>
                          <em class="write-to-search" v-else
                            >Escribí para buscar tu producto.</em
                          >
                        </template>
                        <template #list-footer v-if="options.length">
                          <li class="show-all" @click="showAll">
                            Ver todo
                          </li>
                        </template>
                      </v-select>
                    </div>
                    <div class="col-12 col-sm-3 col-lg-3 search-btn">
                      <button
                        type="button"
                        class="site-btn"
                        @click.prevent="showAll"
                      >
                        BUSCAR
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- <div class="row">
                <div class="col-12 d-none d-lg-block ">
                        <div class="hero__search__phone">
                        <div class="hero__search__phone__icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="hero__search__phone__text">
                            <h5>223 5764153</h5>
                        </div>
                    </div>
                </div>
            </div> -->
      </div>
    </section>
    <!-- Hero Section End -->
  </header>
</template>

<script>
import { mapGetters } from "vuex";
import LocaleDropdown from "./LocaleDropdown";
import Multiselect from "vue-multiselect";
import Sidebar from "../pages/website/partials/sidebar";
import Burger from "../pages/website/partials/burger";
import Categories from "../pages/website/partials/categories";
import vSelect from "vue-select";
import debounce from "lodash/debounce";
import axios from "axios";
import { gsap } from "gsap";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";
import Form from "vform";

export default {
  components: {
    LocaleDropdown,
    Multiselect,
    Categories,
    Sidebar,
    Burger,
    vSelect
  },

  data: () => ({
    appName: window.config.appName,
    showCategories: false,
    searchResult: [],
    term: "",
    showResult: false,
    showSidebar: false,
    loading: false,
    options: [],
    form: new Form({
      term: ""
    })
  }),

  mounted() {
    
  },

  computed: {
    ...mapGetters({
      user: "auth/user",
      cart: "cart/cart",
      categories: "categories/categories",
      showNavCategories: "categories/showNav",
      isSidebarOpen: "settings/showSidebar",
      total: "cart/total",
      count: "cart/count",
      favorites: "auth/favorites",
      filters: "product/filters"
    }),
    isAdmin() {
      return this.user && this.user.role === "admin";
    },
    isSidebarOpen() {
      return this.showSidebar;
    },
    getSearchedResults() {
      return this.searchResult.map(p => ({ code: p.slug, label: p.title }));
    }
  },

  methods: {
    async logout() {
      // Log out the user.
      await this.$store.dispatch("auth/logout");

      // Redirect to login.
      this.$router.push({ name: "login" });
    },

    getImage(images) {
      return images.length
        ? "/productos/" + images[0].name
        : "/img/no-image.png";
    },

    async showAll() {
      
      var self = this;
      let term = self.term
      console.log(term)
      this.$refs.select.open = false
      await this.$store.commit("product/setFilter", {
        type: "term",
        label: term,
        slug: "term",
        id: self.term.length + 999,
        parent: null
      });

      await this.$store.dispatch("product/filter", { pag: 1 });
      this.scrollToCustom("product-list");
      if (this.$router.currentRoute.name !== "website.shop") {
        this.$router.push({
          name: "website.shop",
          params: { term: term ,page:'1'}
        });
        return;
      }
    },

    searchByTerm: debounce(function(search, loading) {
      var self = this;
      self.term = search;
      if (search.length > 2) {
        axios
          .get(`/api/products/search-by-term/${search}`)
          .then(function(response) {
            self.options = response.data;
          });
      }
    }, 300),

    showProduct(slug) {
     
        this.term = "";
        self.options = [];

        if (this.$router.currentRoute.name !== "website.productdetail") {
          this.$router.push({
            name: "website.productdetail",
            params: { slug: slug }
          });
        } else {
          eventBus.$emit("show-product", slug);
        }
      
    },

    async searchProducts(search, loading) {
      self.options = [];
      if (search.length > 2) {
        var self = this;
        var form = new FormData();
        loading = true;
        form.append("term", this.term);
        form.append("perPage", 4);
        await axios
          .post(`/api/products/filter`, form)
          .then(function(response) {
            self.searchResult = response.data.data;
          })
          .catch(function(error) {});

        self.loading = false;
      }
    },

    async showPromotions(e) {
      e.preventDefault();
      if (this.$router.currentRoute.name !== "website.promos") {
        this.$router.push({
          name: "website.promos",
          params: { discount: true, page:1 }
        });
      } else {
        // si esta el filtro promociones solo escrolleo
        if (
          this.filters.filter(f => {
            return f.slug === "sale-discount";
          }).length
        ) {
          await this.$store.dispatch("product/filter", { pag: 1 });
          this.scrollToCustom("product-list");
          return;
        }

        this.$store.commit("product/setFilter", {
          type: "sale",
          label: "Promos",
          slug: "sale-discount",
          id: -1,
          parent: null
        });
        await this.$store.dispatch("product/filter", { pag: 1 });
        this.scrollToCustom("product-list");
      }
    },

    scrollToCustom(selector) {
      let wait = 0;
      // if (window.innerWidth < 992) {
      //   this.menuChange();
      //   wait = 2;
      // }
      gsap.registerPlugin(ScrollToPlugin);
      gsap.to(window, {
        duration: 1,
        scrollTo: { y: "#" + selector, offsetY: 50 },
        delay: wait
      });
    },

    search() {
      this.showResult = !this.showResult;
    },
    toggle() {
      this.$store.commit("settings/toggleSidebar");
    }
  },
  watch: {
    $route(to, from) {
      this.$store.commit("settings/closeSidebar");
    },
   
  }
};
</script>

<style scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -0.375rem 0;
}
</style>

<style>
.vs__spinner {
  border-left-color: rgba(88, 151, 251, 0.71);
  position: absolute;
  right: 5px;
}
.vs__actions{
  cursor: pointer;
}
</style>
