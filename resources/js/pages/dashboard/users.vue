<template>
  <div id="dashboard-users">
    <card :title="'Usuarios'">
      <div class="head">
        <small>Listado de usuarios</small>
        <button class="btn btn-action" @click="create">
          <i class="fas fa-plus"></i>
        </button>
      </div>
      <div class="row justify-content-center">
        <div class="col-6 position-relative">
          <input
            class="form-control search shadow-none"
            v-model="term"
            @input="searchByTerm"
            placeholder="Escribí para buscar"
          />
          <i v-if="term" class="remove-term text-danger fad fa-times" @click="cleanTerm"></i>
        </div>
      </div>
      <div class="product-table ">
        <div class="row mt-5">
          <div class="col-12" v-if="loading">
            <div class="load-spinner">
              <span>
                <i class="fad fa-spinner-third fa-spin"></i>
              </span>
            </div>
          </div>
          <transition name="fade" mode="out-in" v-else>
            <div class="col-12">
              <table class="table table-products">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Email</th>
                    <th scope="col">Teléfono</th>
                    <th class="text-center" scope="col">Estado</th>
                    <th class="text-center" scope="col">Es admin</th>
                    <th class="text-center" scope="col">Opciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(user, index) in users" :key="index">
                    <td class="text-center">{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td class="">{{ user.email }}</td>
                    <td class="text-center">{{ user.phone }}</td>
                    <td class="text-center">
                      <span v-if="user.active"
                        ><i class="fad fa-check text-success"></i
                      ></span>
                      <span v-else
                        ><i class="text-danger fad fa-times"></i>
                      </span>
                    </td>
                    <td class="text-center">
                      <span v-if="user.role === 'admin'"
                        ><i class="fad fa-check text-success"></i
                      ></span>
                      <span v-else
                        ><i class="text-danger fad fa-times"></i>
                      </span>
                    </td>

                    <td class="text-center">
                      
                      <!-- <button class="btn btn-delete" @click="deleteConfirmation(product.id)"><i class="fad fa-trash-alt"></i></button> -->
                      <button class="btn btn-action" @click="createSale(user.id)">
                        <i  class="fad fa-cash-register"></i>
                      </button>
                      <button class="btn btn-action" @click="editItem(user.id)">
                        <i class="fad fa-pencil"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="col-12 text-center">
                <paginator
                  :limit="3"
                  :data="paginationData"
                  @pagination-change-page="getUsers"
                ></paginator>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </card>
    <!--MODAL USERS FORM -->

    <Modal
      v-if="showUserModal"
      :show-footer="false"
      @close="showUserModal = false"
    >
      <h3 slot="header">{{ this.edit ? "Editar" : "Crear" }} usuario</h3>
      <template slot="body">
        <UserForm @close="showUserModal = false" :edit="edit" />
      </template>
    </Modal>
    <!-- END MODAL ///-->


     <!--MODAL USERS DETAILS -->

    <Modal
      v-if="showUserDetailModal"
      :show-footer="false"
      @close="showUserDetailModal = false"
    >
      <h3 slot="header">{{ 'Detalle del usuario' }} usuario</h3>
      <template slot="body">
        <UserDetails @close="showUserDetailModal = false" :edit="edit" />
      </template>
    </Modal>
    
    <!-- END MODAL ///-->

    

    <!--MODAL NEW SALE-->

    <Modal
      v-if="showSaleModal"
      :show-footer="false"
      @close="showSaleModal = false"
    >
      <h3 slot="header"> Nueva venta </h3>
      <template slot="body">
        <NewSaleForm @close="showSaleModal = false" :userid="userid" />
      </template>
    </Modal>
    <!-- END MODAL ///-->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Swal from "sweetalert2";
import UserForm from "./partials/UserForm";
import Modal from "./../../components/Modal";
import Form from "vform";
import Paginator from "laravel-vue-pagination";
import { debounce } from "debounce";
import NewSaleForm from '../dashboard/partials/NewSaleForm'
import UserDetails from './partials/UserDetails.vue';

export default {
  middleware: "admin",

  metaInfo() {
    return { title: "Admin | Usuarios" };
  },

  components: {
    UserForm,
    Modal,
    Paginator,
    NewSaleForm,
    UserDetails
  },

  data() {
    return {
      showUserModal: false,
      showSaleModal: false,
      showUserDetailModal:false,
      item: {},
      edit: false,
      form: new Form(),
      term: "",
      userid:''
    };
  },

  async created() {
    this.getUsers();
  },

  mounted() {},

  computed: {
    ...mapGetters({
      users: "users/users",
      paginationData: "users/pagination",
      page: "users/page",
      loading: "users/loading"
    }),
    getTitle() {
      return this.edit ? "Editar producto" : "Crear producto";
    }
  },

  methods: {
    async getUsers(pag = 1) {
    
      await this.$store.dispatch("users/fetchUsers", {
        pag: pag,
        term: this.term
      });

     
    },
    async filter(){
      var self = this
      await this.$store.dispatch('users/filter',{ pag : self.page , term : self.term})

    },
    async editItem(id) {
      this.edit = true;

      await this.$store.dispatch("users/showUser", id);
      this.showUserDetailModal = true;
    },
    async create() {
      this.edit = false;
      await this.$store.commit("users/setUser", null);
      this.showUserModal = true;
    },
    createSale(userid) {
      this.userid = userid
      this.showSaleModal = true;
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
      await this.form.delete(`/api/products/${id}`);
      if (this.form.successful) {
        // pull premises after insert.
        await this.getProducts();
        this.toast("Registro eliminado!.");
      } else {
        this.toast("Error!.");
      }
    },
    cleanTerm(){
      this.term = '',
      this.getUsers();
    },
    searchByTerm: debounce(function(e) {
      
      if (this.term.length >= 0) {
        this.getUsers();
      }
    }, 300)
  }
};
</script>
