<template>
  <div id="product-detail" v-if="product">
    <!-- Product Details Section Begin -->
    <section class="product-details spad">
      <div class="container">
        <transition name="fade" mode="out-in">
          <div key="1" class="col-12" v-if="!product && loading">
            <!--  -->
            <div class="load-spinner">
              <span>
                <i class="fad fa-spinner-third fa-spin"></i>
              </span>
            </div>
          </div>
          <div key="2" class="row" v-else>
            <div class=" col-md-6 col-lg-6">
              <div class="row">
                <div class="col-12">
                  <div
                    id="carouselProductDetail"
                    class="carousel slide"
                    data-ride="carousel"
                  >
                    <div
                      class="product__discount__percent"
                      v-if="product.discount"
                    >
                      -{{ product.discount }}%
                    </div>
                    <div class="carousel-inner" v-if="images.length">
                      <div
                        class="carousel-item"
                        :id="`item-${index}`"
                        :class="{ active: index == 0 }"
                        v-for="(image, index) in images"
                        :key="index"
                      >
                        <img
                          class="d-block w-100"
                          :src="'/productos/' + image.name"
                          alt="First slide"
                        />
                      </div>
                    </div>
                    <template v-if="images.length > 1">
                      <a
                        class="carousel-control-prev control-product-detail"
                        href="#carouselProductDetail"
                        role="button"
                        data-slide="prev"
                      >
                        <i class="fas fa-chevron-left"></i>
                      </a>
                      <a
                        class="carousel-control-next control-product-detail"
                        href="#carouselProductDetail"
                        role="button"
                        data-slide="next"
                      >
                        <i class="fas fa-chevron-right"></i>
                      </a>
                    </template>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-6 ">
              <div class="product__details__text">
                <h3>{{ product.title }}</h3>
                <!-- <div class="product__details__rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                            <span>(18 reviews)</span>
                        </div> -->
                <div v-if="product.ask_price">
                  <div class="product__details__price">
                    Consultar precio.
                     <a class="whatsapp-contact" :href="getWhatsAppRef" target="_blank"><i class="fab fa-whatsapp"></i></a>
                  </div>
                </div>        
                <div class="product__details__price" v-else>
                  <span v-if="product.sale_type === 'Unidad'">
                    <span v-if="product.discount" class="discount-variant-price"
                      >$ {{ product.price }}</span
                    >$ {{ product.price_discount }}
                  </span>

                  <span v-else>
                    <div class="selected-variant" v-if="product.discount">
                      <span v-html="getFormatedPrice()"></span>
                    </div>
                    <h5 for="">Seleccioná una presentación</h5>
                    <v-select
                      v-model="item.variant"
                      :reduce="v => v.id"
                      :clearable="false"
                      :options="getVariants"
                      label="label"
                    />
                  </span>
                </div>

                <p><span v-html="product.description"></span></p>
                 <div class="sin-stock" v-if="product.stock === 0">
                  Producto sin stock
                </div>
                <div v-if="product.stock > 0">
                  <div class="product__details__quantity">
                  <div class="quantity">
                    <div class="pro-qty">
                      <i class="fas fa-chevron-left" @click="qtyMinus"></i>
                      <input type="tel" v-model="item.qty" />
                      <i class="fas fa-chevron-right" @click="qtyPlus"></i>
                    </div>
                  </div>
                </div>
               
                <span class="add-to-cart-wraper">
                  <a href="" class="primary-btn" @click.prevent="addToCart"
                    >AGREGAR AL CARRITO</a
                  >
                  <a
                    href="#"
                    @click.prevent="setFavorite"
                    class="heart-icon"
                    :class="{ selected: isSelected }"
                    ><span class="icon_heart_alt"
                      ><span v-html="getHeartIcon"></span></span
                  ></a>
                </span>

                </div>
                
                <ul>
                  <!-- <li>
                    <b>Disponibilidad</b>
                    <span v-if="product.stock > 0">En Stock</span
                    ><span v-else class="text-danger"> Sin Stock</span>
                  </li> -->
                  <!-- <li>
                    <b>Entrega</b> <span>Entrega al siguiente dia. </span>
                  </li> -->
                  <!-- <li><meta property="og:image" content="http://127.0.0.1:8000/productos/22c3bcd3966b136f2e982a3d2fa240bdarroz yamani.png" data-vue-meta="true">
                    <b>Compartir</b>
                    <div class="share" v-if="product && !loading">
                 
                      <ShareNetwork
                        network="facebook"
                        :url="getSharedUrl"
                        :title="product.title"
                        :description="product.description"
                        :quote="product.title"
                        :hashtags="getTags"
                      >
                        <i class="fab fa-facebook-f"></i>
                      </ShareNetwork>
                     
                      <ShareNetwork
                        network="whatsapp"
                        :url="getSharedUrl"
                        :title="product.title"
                        :description="product.description"
                      >
                        <i class="fab fa-whatsapp"></i>
                      </ShareNetwork>
                    </div>
                  </li> -->
                </ul>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="product__details__tab">
                <ul class="nav nav-tabs" role="tablist">
                  <!-- <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab"
                                    aria-selected="true">Description</a>
                            </li> -->
                  <li class="nav-item">
                    <a
                      class="nav-link active"
                      data-toggle="tab"
                      href="#tabs-1"
                      role="tab"
                      aria-selected="false"
                      >Información</a
                    >
                  </li>
                  <!-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab"
                                    aria-selected="false">Reviews <span>(1)</span></a>
                            </li> -->
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tabs-1" role="tabpanel">
                    <div class="product__details__tab__desc">
                      <span v-html="product.information"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </transition>
      </div>
    </section>
    <!-- Product Details Section End -->

    <!-- Related Product Section Begin -->
    <section class="related-product" v-if="related.length">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="section-title related__product__title">
              <h2>Productos Relacionados</h2>
            </div>
          </div>
        </div>

        <div class="row">
          <product
            v-for="(product, index) in related"
            :key="index"
            :product="product"
          >
          </product>
        </div>
      </div>
    </section>
    <!-- Related Product Section End -->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Form from "vform";
import axios from "axios";
import Swal from "sweetalert2";
import Sidebar from "./partials/sidebar";
import Product from "./partials/product";
import { VueAgile } from "vue-agile";
import Multiselect from "vue-multiselect";
import vSelect from "vue-select";
import Cookies from "js-cookie";

export default {
  metaInfo() {
    return {
      title: "Detalle del producto",
      meta: [
        // Facebook OpenGraph
        {
          property: "og:title",
          content: this.product ? this.product.title : ""
        },
        { property: "og:site_name", content: window.location.origin },
        { property: "og:type", content: "website" },
        {
          property: "og:image",
          content:
            this.product && this.product.images
              ? window.location.origin +
                "/productos/" +
                this.product.images[0].name
              : "/img/no-image.png"
        },
        {
          property: "og:description",
          content: this.product ? this.product.description : ""
        }
      ]
    };
  },

  props: {},

  components: {
    Sidebar,
    Product,
    agile: VueAgile,
    Multiselect,
    vSelect
  },

  data() {
    return {
      item: {
        qty: 1,
        variant: null,
        id: "",
        product_id: "",
        product_title: "",
        product_discount: "",
        product_image: "",
        product_price_discount: "",
        product_price: "",
        sale_type: "",
        variant_description: "",
        variant_price: "",
        total: "",
        variant_weigth:""
      },

      slug: this.$route.params.slug,
      options: {
        dots: false,
        fade: true,
        navButtons: false
      }
    };
  },

  computed: {
    ...mapGetters({
      product: "product/product",
      images: "product/images",
      related: "product/related",
      listpage: "product/page",
      cart: "cart/cart",
      favorites: "auth/favorites",
      loading: "product/loading"
    }),
    getHeartIcon() {
      return this.favorites.includes(this.product.id)
        ? '<i class="fad fa-heart"></i>'
        : '<i class="far fa-heart"></i>';
    },
    isSelected() {
      return this.favorites.includes(this.product.id);
    },
    getVariants() {
      if (!this.product.variants) {
        return;
      }
      return this.product.variants.map(function(v) {
        const val = v.weight / 1000;
        const msg = val >= 1 ? val + " Kg." : v.weight + " Gr.";
        v.code = v.id;
        v.label = `${msg}  $ ${v.price}`;
        return v;
      });
    },

    getSharedUrl() {
      return (
        window.location.origin +
        this.$router.resolve({
          name: "website.productdetail",
          params: { slug: this.product.slug }
        }).href
      );
    },
    getTags() {
      return this.products ? this.product.tags.join() : "";
    },
    getWhatsAppRef(){
      return "https://api.whatsapp.com/send?phone=5492235764153&text=Hola, tengo una consulta sobre " + this.product.title
    }
    
  },

  created() {
    var self = this;

    eventBus.$on("show-product", slug => {
      self.slug = slug;
      self.getProduct();
    });

    this.getProduct();
  },

  methods: {
    async getProduct() {
      await this.$store.dispatch("product/showProduct", this.slug);
      if (this.product.sale_type === "Peso") {
        this.item.variant = this.product.variants[0].id;
      }
    },
    getVariantDescription(weight) {
      const val = weight / 1000;
      return val >= 1 ? val + " Kg." : weight + " Gr.";
    },
    qtyPlus() {
      this.item.qty++;
    },
    qtyMinus() {
      if (this.item.qty > 0) this.item.qty--;
    },
    getFormatedPrice() {
      const variant = this.product.variants.find(
        v => v.id === this.item.variant
      );
      return `<span class="discount-variant-price">$ ${
        variant.price
      } </span> $ ${(
        variant.price -
        (this.product.discount / 100) * variant.price
      ).toFixed(2)}`;
    },
    async addToCart() {

      if(this.product.sale_type === 'Peso' && !this.item.variant){
         Swal.fire({
            html: "¡Debes seleccionar una producto! <br>",
            type: "warning",
            confirmButtonText: "Ok!",
          })
          return
      }

      var self = this;
      this.item.id = this.product.id;
      this.item.product_id = this.product.id;
      this.item.product_title = this.product.title;
      this.item.product_discount = this.product.discount;
      this.item.product_image = this.product.images.length
        ? this.product.images[0].name
        :  '/img/no-image.png';
      this.item.product_price_discount = this.product.price_discount;
      this.item.product_price = this.product.price;
      this.item.sale_type = this.product.sale_type;

      if(this.item.variant){
        let variant = this.product.variants.find(v => v.id === this.item.variant)
        this.item.variant_description = this.product.title + ' ' + variant.label
        this.item.variant_price = variant.price
        this.item.variant_weigth = variant.weight
      }
      
      this.item.total = this.getItemTotal(this.item.sale_type,this.item.product_price,this.item.qty,this.item.product_discount,this.item.variant_price);
    
      await this.$store.commit("cart/addItem", this.item);

      Swal.fire({
        html: "¡El producto ha sido agregado a tu carrito! <br>",
        type: "success",
        confirmButtonText: "Finalizar compra",
        showCancelButton: true,
        cancelButtonText: "Seguir comprando"
      }).then( (isConfirm) =>  {
        if (isConfirm.value) {
          self.$router.push({ name: "website.cart" });
        } else {
          self.$router.push({
            name: "website.shop",
            params: { page: this.listpage }
          });
        }
      });
    },
    confirmMsg() {
      Swal.fire({
        toast: true,
        position: "top-end",
        text: "Producto agregado !.",
        showConfirmButton: false,
        timer: 3000
      });
    },

    async setFavorite() {
      await this.$store.commit("auth/setFavorite", this.product.id);
      await this.$store.dispatch("auth/setFavorites");
    }
  }
};
</script>
<style lang="scss" scoped>
.invalid-feedback {
  position: absolute;
  bottom: 5px;
}
</style>
<style>
.swal2-container {
  z-index: 10000;
}
</style>
