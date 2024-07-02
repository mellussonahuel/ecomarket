<template>
  <div id="coupons-form" class="form">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <div class="fields-container">
        <!-- Categoria padre -->
        <div class="form-group row">
          <div class="col-md-12 position-relative">
            <label>Usuario <small>(Email)</small></label>
            <div class="search-user-group ">
              <input
                type="email"
                v-model="form.email"
                class="form-control shadow-none"
                :class="{ 'is-invalid': form.errors.has('email') }"
                placeholder=""
                aria-describedby="basic-addon2"
              />
              <button
                class="btn-primary inverse"
                type="button"
                @click="searchUser"
              >
                <span class="user-validated" v-html="getIcon"></span>
                <span v-html="getUserButtonStatus"></span>
              </button>
              
            </div>
          </div>
          <has-error :form="form" field="email" />
        </div>

        <!-- Description-->
        <div class="form-group row">
          <div class="col-md-12">
            <label> Monto <small>( % de descuento )</small></label>
            <input
              v-model="form.amount"
              class="form-control"
              :class="{ 'is-invalid': form.errors.has('amount') }"
              type="text"
              name="amount"
            />
            <has-error :form="form" field="amount" />
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-12 position-relative">
            <label>Código </label>
            <div class="search-user-group">
              <input
                type="text"
                v-model="form.code"
                class="form-control shadow-none"
                :class="{ 'is-invalid': form.errors.has('code') }"
                placeholder=""
                aria-describedby="basic-addon2"
              />
              <button
                class="btn-primary inverse"
                type="button"
                @click.prevent="generateCode"
              >
                <span v-html="getCodeButtonStatus"></span>
              </button>
              
            </div>
          </div>
          <has-error :form="form" field="code" />
        </div>

        <!-- Tipo -->
        <div class="form-group row">
          <div class="col-md-12">
            <label>Tipo</label>
            <v-select
              key="1"
              v-model="form.type"
              :reduce="c => c.id"
              :label="'description'"
              :class="{ 'is-invalid': form.errors.has('type') }"
              :options="cuponTypeOptions"
            >
            </v-select>
            <has-error :form="form" field="type" />
          </div>
        </div>

        <!-- Send Email -->
        <div class="form-check ">
          <input
            class="form-check-input mt-2"
            type="checkbox"
            name="all_users"
            v-model="form.all_users"
          />
          <label class="pt-1 pb-1 form-check-label">
            Todos los usuarios
          </label>
        </div>

        <!-- valid from -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-left">Válido desde</label>
          <div class="col-md-12">
            <div class="row">
              <div class="col-12 col-md-6">
                <datepicker
                  v-model="valid_from.date"
                  format="dd/MM/yyyy"
                  :language="es"
                  :disabled="false"
                  :bootstrap-styling="true"
                ></datepicker>

                <span
                  v-if="form.errors.has('valid_from')"
                  class="span-error is-invalid"
                >
                  <has-error :form="form" field="valid_from" />
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- valid from -->
        <div class="form-group row">
          <label class="col-md-12 col-form-label text-left">Válido hasta</label>
          <div class="col-md-12">
            <div class="row">
              <div class="col-12 col-md-6">
                <datepicker
                  v-model="valid_to.date"
                  format="dd/MM/yyyy"
                  :language="es"
                  :disabled="false"
                  :bootstrap-styling="true"
                ></datepicker>

                <span
                  v-if="form.errors.has('valid_to')"
                  class="span-error is-invalid"
                >
                  <has-error :form="form" field="valid_to" />
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Send Email -->
        <div class="form-check ">
          <input
            class="form-check-input mt-2"
            type="checkbox"
            name="send_email"
            v-model="form.send_email"
          />
          <label class="pt-1 pb-1 form-check-label">
            Enviar código por email al usuario
          </label>
        </div>

        <!-- Submit Button -->
        <div class="form-group row mt-5">
          <div class="col-md-12">
            <v-button :loading="form.busy" class="btn-primary">
              Guardar
            </v-button>

            <button type="button" class="btn-primary inverse" @click="close">
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import Form from "vform";
import { mapGetters } from "vuex";
import DragDrop from "../../../components/DragDrop";
import Swal from "sweetalert2";
import axios from "axios";
import Multiselect from "vue-multiselect";
import vSelect from "vue-select";
import Datepicker from "vuejs-datepicker";
import { es } from "vuejs-datepicker/dist/locale";
export default {
  name: "CouponsForm",
  scrollToTop: true,

  components: {
    DragDrop,
    Multiselect,
    vSelect,
    Datepicker
  },

  data() {
    return {
      message: null,
      es: es,
      form: new Form({
        id: "",
        user_id: "",
        amount: "",
        email: "",
        code: null,
        all_users: false,
        type: "ONE_USE",
        send_email: 1,
        valid_to:'',
        valid_from:''
      }),
      isSearchingUser: false,
      isGeneratingCode: false,
      isValidated: null,
      activeCategories: null,
      cuponTypeOptions: [
        { id: "ONE_USE", description: "Un uso" },
        { id: "UNLIMITED", description: "Ilimitado" }
      ],
      valid_to: {
        date: ""
      },
      valid_from: {
        date: ""
      }
    };
  },

  computed: {
    ...mapGetters({
      coupon: "coupons/coupon",
      page: "coupons/page"
    }),
    getIcon() {
      return this.isValidated && this.form.email
        ? '<i class="fad fa-check"></i>'
        : this.isValidated === false && this.form.email
        ? '<i class="fad fa-times"></i>'
        : "";
    },
    getUserButtonStatus() {
      return this.isSearchingUser
        ? '<i class="fad fa-spinner-third fa-spin"></i>'
        : "Buscar";
    },
    getCodeButtonStatus() {
      return this.isGeneratingCode
        ? '<i class="fad fa-spinner-third fa-spin"></i>'
        : "Generar";
    }
  },

  props: {
    edit: {
      default: false,
      type: Boolean
    }
  },

  created() {
    this.prePopulateForm();
    var self = this;
  },

  methods: {
    async searchUser() {
      this.isValidated = null;
      if (!this.form.email.length) {
        Swal.fire({
          text: "Tenés que ingresar un email!",
          type: "warning",
          confirmButtonText: "Ok"
        });
      }

      this.isSearchingUser = true;
      const { data } = await this.form.get(
        `/api/coupons/search-user/${this.form.email}`
      );

      if (this.form.successful) {
        if (this.has(data, "email")) {
          this.isValidated = true;
        } else {
          this.isValidated = false;
        }
      } else {
        this.isValidated = false;
      }

      this.isSearchingUser = false;
    },

    prePopulateForm() {
      if (this.edit === true && Object.keys(this.coupon).length != 0) {
        for (let key in this.coupon) {
          if (this.has(this.form, key)) {
            this.form[key] = this.coupon[key];
          }
        }
        this.form.id = this.coupon.id;
      }
    },

    closeModal() {
      this.$emit("close");
    },

    async generateCode() {
      this.isGeneratingCode = true;

      this.form.code 
      const { data } = await axios.post(`/api/coupons/generate-code`);

      if (data.CODE === "success") {
        this.form.code = data.data;
      }

      this.isGeneratingCode = false;
    },

    async update() {
      var self = this;

      
      this.form.valid_from = this.valid_from.date ?  this.moment(this.valid_from.date).format("YYYY-MM-DD") : ''
      this.form.valid_to = this.valid_to.date ?  this.moment(this.valid_to.date).format("YYYY-MM-DD") : ''
      
      try {
        if (this.edit) {
          const { data } = await this.form.patch(
            `/api/coupons/${this.form.id}`
          );

          if (this.form.successful) {
            self.toast(
              self.edit == true ? "Cupón actualizado." : "Cupón creado!."
            );
            self.getCoupons();
            self.close();
          }
        } else {
          const { data } = await this.form.post("/api/coupons");
          if (this.form.successful) {
            // pull premises after insert.
            // await this.$store.commit('categories/setCategory', data)
            self.toast(
              self.edit == true ? "Cupón actualizado." : "Cupón creado!."
            );
            self.getCoupons();
            self.close();
          }
        }
      } catch (error) {
        this.toast("Error !.");
      }
    },

    close() {
      this.$emit("close");
    },

    async getCoupons(pag = 1) {
      this.loading = true;
      await this.$store.dispatch("coupons/fetchCoupons", {
        pag: pag
      });
      this.loading = false;
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
        .post("/api/categories/delete-image", { id: image_id })
        .then(function(response) {
          if (response) {
            self.getCategory();
          }
        })
        .catch(function(error) {});
    }
  }
};
</script>

<style scoped></style>
