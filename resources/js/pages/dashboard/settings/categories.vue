<template>
  <div id="dashboard-settings-categories">
    <card :title="'Categorías'" id="categories-settings">
      <div class="head">
        <small>Categorías de productos</small>
        <div>
           <button class="btn btn-action" @click="create">
          <i class="fas fa-plus"></i>
        </button>
        <button class="btn btn-action" title="ordenar" @click="sort">
          <i class="fad fa-sort-up"></i>
        </button>
        </div>
       
        
      </div>
      <div class="row mt-5">
        <div class="col-12">
          <table class="table table-categories">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Padre</th>
                <th scope="col">Descripción</th>
                <th scope="col">Estado</th>
                <th scope="col">Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(category, index) in categories" :key="index">
                <th scope="row">{{ category.id }}</th>
                <td>
                  {{ category.parent ? category.parent.description : "-" }}
                </td>
                <td>{{ category.description }}</td>
                <td>
                  <span v-if="category.active"
                    ><i class="fad fa-check"></i
                  ></span>
                  <span v-else><i class="fad fa-times"></i> </span>
                </td>
                <td>
                  <!-- <button class="btn btn-delete" @click="deleteConfirmation(category.id)"><i class="fad fa-trash-alt"></i></button> -->
                  <button class="btn btn-action" @click="editItem(category)">
                    <i class="fad fa-pencil"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="col-12">
            <paginator
              :limit="3"
              :data="paginationData"
              @pagination-change-page="getCategories"
            ></paginator>
          </div>
        </div>
      </div>
    </card>

    <!--MODAL CATEGORIES QUEUES-->

    <Modal
      v-if="showModalCategoryCreate"
      :show-footer="false"
      @close="showModalCategoryCreate = false"
    >
      <h3 slot="header">
        {{ getTitle }}
      </h3>
      <template slot="body">
        <CategoryForm @close="showModalCategoryCreate = false" :edit="edit" />
      </template>
    </Modal>
    <!-- END MODAL ///-->

     <!--MODAL SORT-->

    <Modal
      v-if="showModalSortCategories"
      :show-footer="false"
      @close="showModalSortCategories = false"
    >
      <h3 slot="header">
        Ordenar categorías
      </h3>
      <template slot="body">
        <SortCategories @close="closeSortModal" :edit="edit" />
      </template>
    </Modal>
    <!-- END MODAL ///-->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Swal from "sweetalert2";
import Form from "vform";
import Modal from "./../../../components/Modal";
import CategoryForm from "../partials/categoriesForm";
import SortCategories from "../partials/SortCategories"
import Paginator from "laravel-vue-pagination";
export default {
  middleware: "admin",

  components: {
    CategoryForm,
    Modal,
    Paginator,
    SortCategories
  },

  data() {
    return {
      showModalCategoryCreate: false,
      showModalSortCategories: false,
      edit: false,
      item: {},
      form: new Form()
    };
  },

  async created() {
    if (!this.categories.length) {
      //this.getCategories()
    }
  },

  mounted() {
    var self = this;
    eventBus.$on("images-uploaded", () => {
      self.edit = true;
    });
    this.getCategories();
  },

  computed: {
    ...mapGetters({
      categories: "categories/categories",
      paginationData: "categories/pagination"
    }),
    getTitle() {
      return this.edit ? "Editar categoría" : "Crear categoría";
    }
  },

  methods: {
    async getCategories(pag = 1) {
      await this.$store.dispatch("categories/fetchAll", {
        pag: pag
      });
    },
    closeSortModal(){
      this.showModalSortCategories = false
      this.getCategories();
    },
    async editItem(category) {
      this.edit = true;
      await this.$store.commit("categories/setCategory", category);
      this.showModalCategoryCreate = true;
    },
    async create() {
      this.edit = false;
      await this.$store.commit("categories/setCategory", null);
      this.showModalCategoryCreate = true;
    },
    sort(){
        this.showModalSortCategories = true
    },
    deleteConfirmation(id) {
      var self = this;
      Swal.fire({
        text: "Eliminar registro ?",
        type: "warning",
        confirmButtonText: "Ok",
        showCancelButton: true,
        cancelButtonText: "Cancelar"
      }).then(function(isConfirm) {
        if (isConfirm.value) {
          self.deleteItem(id);
        }
      });
    },
    async deleteItem(id) {
      await this.form.delete(`/api/categories/${id}`);
      if (this.form.successful) {
        // pull premises after insert.
        await this.getCategories();
        this.toast("Registro eliminado!.");
        this.getCategories();
      } else {
        this.toast("Error!.");
      }
    }
  }
};
</script>
