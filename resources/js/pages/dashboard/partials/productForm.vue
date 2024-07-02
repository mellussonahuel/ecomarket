<template>
  <div class="form" id="product-form">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <div class="fields-container">
        <div class="header">
          Descripción del producto
        </div>

        <!-- Images -->
        <div class="form-group row">
          <div class="col-md-12">
            <label> Imágenes</label>
            <drag-drop :upload-url="'/api/products/store-images'"></drag-drop>
          </div>
        </div>

        <!-- Title -->
        <div class="form-group row mt-5" v-if="productImages.length">
          <div class="col-md-12">
            <label> Imágenes cargadas</label>
            <div class="row images-container">
              <draggable v-model="images" @end="sortImages">
                <transition-group>
                  <div
                    class="col-6 col-sm-4 col-md-3 position-relative product-image-item"
                    v-for="(image, index) in productImages"
                    :key="index + '-primg'"
                  >
                    <img :src="`/productos/${image.name}`" alt="" />
                    <div class="delete-image">
                      <button
                        class="btn btn-delete"
                        @click.prevent="deleteImageConfirmation(image.id)"
                      >
                        <i class="fad fa-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </transition-group>
              </draggable>
            </div>
          </div>
        </div>

        <!-- Title -->
        <div class="form-group row">
          <div class="col-md-12">
            <label> Título</label>
            <input
              v-model="form.title"
              :class="{ 'is-invalid': form.errors.has('title') }"
              class="form-control"
              type="text"
              name="title"
            />
            <has-error :form="form" field="title" />
          </div>
        </div>

        <!-- Description-->
        <div class="form-group row">
          <div class="col-md-12">
            <label>Descripción</label>
            <VueTrix
              v-model="form.description"
              :class="{ 'is-invalid': form.errors.has('description') }"
              placeholder=""
            />
            <has-error :form="form" field="description" />
          </div>
        </div>

        <!-- Information -->
        <div class="form-group row">
          <div class="col-md-12">
            <label>Información</label>
            <VueTrix
              v-model="form.information"
              :class="{ 'is-invalid': form.errors.has('information') }"
              placeholder=""
            />
            <has-error :form="form" field="information" />
          </div>
        </div>

        <!-- Tipo de venta -->
        <div class="form-group row">
          <div class="col-md-12">
            <label>Tipo de venta</label>
            <multiselect
              v-model="form.sale_type"
              :options="salesTypes"
              :placeholder="'Tipo de venta'"
            ></multiselect>
          </div>
        </div>

        <div class="form-group">
          <div class="form-check ">
            <input
              class="form-check-input mt-2"
              type="checkbox"
              name="active"
              v-model="form.ask_price"
            />
            <label class="pt-1 pb-1 form-check-label">
              Consultar precio
            </label>
          </div>
        </div>

        <transition name="fade" mode="out-in">
          <!-- Price-->
          <div
            class="form-group row"
            v-if="form.sale_type === 'Unidad' && !form.ask_price"
          >
            <div class="col-md-12">
              <label> Precio</label>
              <input
                v-model="form.price"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('price') }"
                type="text"
                name="price"
              />
              <has-error :form="form" field="price" />
            </div>
          </div>
        </transition>

        <div
          id="product-variant"
          class="form-group row"
          v-if="form.sale_type === 'Peso'"
        >
          <div class="col-12 col-md-5">
            <div class="head">
              <span>Variantes</span>
              <button
                type="button"
                class="btn btn-action"
                @click.prevent="showVariantCreate = !showVariantCreate"
              >
                <i class="fas fa-plus"></i>
              </button>

              <div
                v-if="form.errors.has('product_variants')"
                style="width: 100%;margin-top: 0.25rem;font-size: 80%;color: #dc3545;"
              >
                Debes generar al menos una variante si el tipo de venta es por
                peso.
                <!-- <has-error :form="form" field="categories"/> -->
              </div>
            </div>
          </div>
          <transition name="fade" mode="out-in">
            <div class="col-12 mt-5" v-if="showVariantCreate">
              <table class="table table-categories">
                <thead>
                  <tr>
                    <th scope="col">Peso</th>
                    <th scope="col">Precio</th>
                    <th class="text-center" scope="col">Estado</th>
                    <th class="text-center" scope="col">Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <input
                        v-model="currentVariant.weight"
                        class="form-control"
                        placeholder="Peso en gramos"
                        type="text"
                        name="variantWeight"
                      />
                    </td>
                    <td>
                      <input
                        v-model="currentVariant.price"
                        class="form-control"
                        placeholder="Precio"
                        type="text"
                        name="variantPrice"
                      />
                    </td>
                    <td class="text-center">
                      <input
                        class="form-check-input "
                        type="checkbox"
                        name="variant_active"
                        v-model="currentVariant.active"
                      />
                    </td>
                    <td class="text-center">
                      <button
                        class="btn btn-delete"
                        @click.prevent="setProductVariant"
                        type="button"
                      >
                        <i class="fad fa-save"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </transition>
          <transition name="fade" mode="out-in">
            <div
              class="col-12 mt-5"
              v-if="form.product_variants && form.product_variants.length"
            >
              <table class="table table-categories">
                <thead>
                  <tr>
                    <th scope="col">Peso</th>
                    <th scope="col">Precio</th>
                    <th class="text-center" scope="col">Estado</th>
                    <th class="text-center" scope="col">Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(variant, index) in form.product_variants"
                    :key="index"
                  >
                    <td>{{ variant.weight }}</td>
                    <td>$ {{ variant.price }}</td>
                    <td class="text-center">
                      <span v-if="variant.active"
                        ><i class="fad fa-check text-success"></i
                      ></span>
                      <span v-else
                        ><i class="text-danger fad fa-times"></i>
                      </span>
                    </td>
                    <td class="text-center">
                      <button
                        class="btn btn-action"
                        @click.prevent="editProductVariant(variant)"
                        type="button"
                      >
                        <i class="fad fa-pencil"></i>
                      </button>

                      <!-- <button 
                                        class="btn btn-delete" 
                                        @click.prevent="removeProductVariant(variant.weight)" 
                                        type="button">
                                        <i class="fad fa-trash-alt"></i></button> -->
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </transition>
        </div>

        <!-- Stock -->
        <div class="form-group row">
          <div class="col-md-12">
            <label> Stock</label>
            <input
              v-model="form.stock"
              :class="{ 'is-invalid': form.errors.has('stock') }"
              class="form-control"
              type="text"
              name="stock"
            />
            <has-error :form="form" field="stock" />
          </div>
        </div>

        <!-- Min Stock-->
        <div class="form-group row">
          <div class="col-md-12">
            <label> Stock mínimo</label>
            <input
              v-model="form.min_stock"
              :class="{ 'is-invalid': form.errors.has('min_stock') }"
              class="form-control"
              type="text"
              name="min_stock"
            />
            <has-error :form="form" field="min_stock" />
          </div>
        </div>

        <!-- Categorias -->
        <div class="form-group row" v-if="categories">
          <div class="col-md-12">
            <label>Categorias</label>
            <!-- <multiselect
                            v-model="form.categories"
                            @input="setCategory"
                            :multiple="true"
                            :options="categories"
                            :close-on-select="false"
                            key="id"
                            name="categories"
                            track-by="id"
                            label="description"                         
                            :placeholder="'Categoria de producto'"
                        ></multiselect> -->
            <v-select
              key="1"
              v-model="category"
              :label="'description'"
              :options="getParents"
            >
            </v-select>

            <div
              id="aca"
              v-if="form.errors.has('categories')"
              style="width: 100%;margin-top: 0.25rem;font-size: 80%;color: #dc3545;"
            >
              Debes seleccionar una categoría
              <!-- <has-error :form="form" field="categories"/> -->
            </div>
          </div>
        </div>

        <!-- subcategorias -->
        <div class="form-group row" v-if="categories">
          <div class="col-md-12">
            <label>Sub categoría</label>
            <v-select
              key="2"
              :disabled="!subcategories.length"
              v-model="subcategory"
              :label="'description'"
              :options="subcategories"
            >
            </v-select>
          </div>
        </div>

        <div
          class="form-group categories-selected row"
          v-if="form.categories.length"
        >
          <div class="col-12">
            <span v-for="(category, index) in form.categories" :key="index">
              {{ category.description }}
              <i class="fad fa-times" @click="removeCategory(index)"></i>
            </span>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-12 text-right">
            <button
              class="btn btn-delete"
              @click.prevent="setCategory"
              type="button"
            >
              <i class="fad fa-save"></i>
            </button>
          </div>
        </div>

        <!-- Descuento -->
        <div class="form-group row">
          <div class="col-md-12">
            <label>Descuento</label>
            <input
              v-model="form.discount"
              :class="{ 'is-invalid': form.errors.has('discount') }"
              class="form-control"
              type="text"
              name="discount"
            />
            <has-error :form="form" field="discount" />
          </div>
        </div>

        <!-- Tags -->
        <div class="form-group row">
          <div class="col-md-12">
            <label>Tags</label>
            <input-tag
              class="form-control"
              :class="{ 'is-invalid': form.errors.has('tags') }"
              v-model="form.tags"
            ></input-tag>

            <has-error :form="form" field="tags" />
          </div>
        </div>

        <div class="form-check ">
          <input
            class="form-check-input mt-2"
            type="checkbox"
            name="active"
            v-model="form.active"
          />
          <label class="pt-1 pb-1 form-check-label">
            Activo
          </label>
        </div>

        <div class="form-check ">
          <input
            class="form-check-input mt-2"
            type="checkbox"
            name="active"
            v-model="form.highlighted"
          />
          <label class="pt-1 pb-1 form-check-label">
            Destacado
          </label>
        </div>

        <div class="form-check ">
          <input
            class="form-check-input mt-2"
            type="checkbox"
            name="active"
            v-model="form.is_combo"
          />
          <label class="pt-1 pb-1 form-check-label">
            Combo
          </label>
        </div>

        <div class="form-check ">
          <input
            class="form-check-input mt-2"
            type="checkbox"
            name="active"
            v-model="form.is_week_combo"
          />
          <label class="pt-1 pb-1 form-check-label">
            Combo de la semana
          </label>
        </div>

        <!-- Submit Button -->
        <div class="form-group row mt-5">
          <div class="col-md-12">
            <v-button :loading="form.busy" class="btn-primary">
              Guardar
            </v-button>

            <button type="button" class="btn-primary inverse" @click="close">
              {{ $t("cancel") }}
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
import { mapGetters } from "vuex";
import Multiselect from "vue-multiselect";
import InputTag from "vue-input-tag";
import VueTrix from "vue-trix";
import findIndex from "lodash/findIndex";
import DragDrop from "../../../components/DragDrop";
import draggable from "vuedraggable";
import Swal from "sweetalert2";
import vSelect from "vue-select";

export default {
  name: "ProductForm",
  scrollToTop: true,

  components: {
    Multiselect,
    InputTag,
    VueTrix,
    DragDrop,
    draggable,
    vSelect
  },

  data() {
    return {
      message: null,
      showVariantCreate: false,
      form: new Form({
        title: "",
        description: "",
        information: "",
        price: "",
        ask_price: 0,
        sale_type: "Peso",
        stock: 0,
        min_stock: 0,
        categories: [],
        discount: 0,
        tags: [],
        product_variants: [],
        active: 1,
        is_combo: 0,
        is_week_combo: 0,
        highlighted: 0,
        images: []
      }),
      deleteImageForm: new Form({
        id: ""
      }),
      variantWeight: "",
      variantPrice: "",
      salesTypes: ["Peso", "Unidad"],
      currentVariant: {},
      category: null,
      subcategory: null,
      activeCategories: null,
      subcategories:[],
    };
  },

  computed: {
    ...mapGetters({
      categories: "categories/categories",
      product: "product/product",
      productImages: "product/images",
      page: "product/page"
    }),
    images: {
      get() {
        return this.productImages;
      },
      set(value) {
        this.$store.commit("product/setImages", value);
      }
    },
    getParents() {
      return this.categories.filter(c => c.parent_id === null);
    },
    getChilds() {
      return this.category
        ? this.categories.filter(c => c.parent_id === this.category.id)
        : [];
    }
  },

  props: {
    edit: {
      default: false,
      type: Boolean
    }
  },

  async created() {
    var self = this;
    this.prePopulateForm();
    //this.getActiveCategories();
    eventBus.$on("images-uploaded", () => {
      //self.getProduct()
      self.getProducts();
      self.form.busy = false;
      self.toast(
        self.edit == true ? "Product actualizado." : "Producto creado!."
      );
      //self.prePopulateForm();
      self.close();
    });
  },

  methods: {
    prePopulateForm() {
      if (this.edit === true && Object.keys(this.product).length != 0) {
        for (let key in this.product) {
          if (this.has(this.form, key)) {
            this.form[key] = this.product[key];
          }
        }
        this.form.product_variants = this.product.variants;
        this.form.id = this.product.id;
      } else {
        this.form.reset();
      }
    },

    setSaleType(saletype) {
      this.form.sale_type = saletype.description;
    },

    setProductVariant() {
      if (this.currentVariant.id) {
        const index = this.form.product_variants.findIndex(
          i => i.id === this.currentVariant.id
        );
        this.form.product_variants[index] = this.currentVariant;
      } else {
        this.currentVariant.id =
          this.form.product_variants.length + 999999999999999999999999999999;
        this.form.product_variants.push(this.currentVariant);
      }

      this.currentVariant = Object.assign({});
      this.currentVariant.active = 1;
    },
    editProductVariant(variant) {
      this.currentVariant = Object.assign(variant);
      this.showVariantCreate = true;
    },
    removeProductVariant(description) {
      const index = this.form.product_variants.findIndex(
        i => i.description === description
      );
      this.form.product_variants.splice(index, 1);
    },

    close() {
      this.$emit("close");
    },

    setCategory() {
      // this.form.subcategory_id === null ? this.form.parent_id : this.form.subcategory_id

      this.form.categories.push(
        this.subcategory ? this.subcategory : this.category
      );
      this.category = null;
      this.subcategory = null;
    },

    removeCategory(index) {
      this.form.categories.splice(index, 1);
    },

    async update() {
      var self = this;
      this.form.files = this.productImages;

      try {
        if (this.edit) {
          const { data } = await this.form.patch(
            `/api/products/${this.form.id}`
          );

          if (this.form.successful) {
            // pull premises after insert.
            this.$store.commit("product/setProduct", data);
            self.storeImages();
          }
        } else {
          const { data } = await this.form.post("/api/products");

          if (this.form.successful) {
            // pull premises after insert.
            this.$store.commit("product/setProduct", data);
            self.storeImages();
          }
        }
      } catch (error) {
        this.toast("Error !.");
      }
    },
    async storeImages() {
      this.form.busy = true;

      eventBus.$emit("upload-images", this.product);
    },

    async getProducts() {
      await this.$store.dispatch("product/fetchProducts", {
        pag: this.page
      });
    },
    async getProduct() {
      await this.$store.dispatch("product/showProduct", this.product.slug);
      this.prePopulateForm();
    },

    deleteImageConfirmation(id) {
      var self = this;
      Swal.fire({
        text: "Eliminar imágen ?",
        type: "warning",
        confirmButtonText: "Ok",
        showCancelButton: true,
        cancelButtonText: "Cancelar"
      }).then(function(isConfirm) {
        if (isConfirm.value) {
          self.deleteImage(id);
        }
      });
    },
    async deleteImage(image_id) {
      var self = this;
      //  const index = this.form.product_variants.findIndex(i => i.description === description);
      // this.form.product_variants.splice(index,1)
      await axios
        .post("/api/products/delete-image", { id: image_id })
        .then(function(response) {
          if (response.data) {
            self.getProduct();
          }
        })
        .catch(function(error) {});
    },

    async sortImages() {
      var form = new Form();
      form.images = this.images;
      form.post("/api/products/sort-images");
    }
  },
  watch: {
    category: function() {
    this.subcategories.length = 0
     this.subcategories =  this.category
        ? this.categories.filter(c => c.parent_id === this.category.id)
        : [];
    }
  }
  
};
</script>

<style scoped lang="scss">
.delete-image {
  position: absolute;
  right: 10px;
  top: 0px;
}

.images-container {
  img:hover {
    cursor: move;
  }
  span {
    display: flex;
    width: 100%;
    overflow: auto;
    padding: 15px;
  }
}
</style>
<style>
.trix-content {
  height: 300px !important;
  overflow-y: auto;
}
</style>
