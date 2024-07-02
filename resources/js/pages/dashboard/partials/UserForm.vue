<template>
  <div class="form" id="user-form">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <div class="fields-container">
        <!-- Name -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">{{
            $t("name")
          }}</label>
          <div class="col-md-7">
            <input
              v-model="form.name"
              :class="{ 'is-invalid': form.errors.has('name') }"
              class="form-control"
              type="text"
              name="name"
            />
            <has-error :form="form" field="name" />
          </div>
        </div>

        <!-- Email -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">{{
            $t("email")
          }}</label>
          <div class="col-md-7">
            <input
              v-model="form.email"
              :class="{ 'is-invalid': form.errors.has('email') }"
              class="form-control"
              type="email"
              name="email"
            />
            <has-error :form="form" field="email" />
          </div>
        </div>

        <!-- Address -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">Dirección</label>
          <div class="col-md-7">
            <input
              v-model="form.address"
              :class="{ 'is-invalid': form.errors.has('address') }"
              class="form-control"
              type="text"
              name="address"
            />
            <has-error :form="form" field="address" />
          </div>
        </div>

        <!-- Phone -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">Teléfono</label>
          <div class="col-md-7">
            <input
              v-model="form.phone"
              :class="{ 'is-invalid': form.errors.has('phone') }"
              class="form-control"
              type="text"
              name="phone"
            />
            <small
              >Usaremos este teléfono para coordinar la entrega de tu
              pedido.</small
            >
            <has-error :form="form" field="phone" />
          </div>
        </div>

        <!-- birth date -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right"
            >Fecha de nacimiento</label
          >
          <div class="col-md-7">
            <datepicker
              v-model="dp.date"
              format="dd/MM/yyyy"
              :language="es"
              :disabled="false"
              :bootstrap-styling="true"
            ></datepicker>

            <span
              v-if="form.errors.has('birth_date')"
              class="span-error is-invalid"
            >
              <has-error :form="form" field="birth_date" />
            </span>
          </div>
        </div>

        <!-- location -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">Ciudad</label>
          <div class="col-md-7">
            <v-select
              v-model="form.location_id"
              :reduce="l => l.code"
              :clearable="false"
              :class="{
                'is-invalid': form.errors.has('location_id'),
                'location-select': true
              }"
              :options="getLocations"
              label="label"
            />
            <has-error :form="form" field="location_id" />
            <!-- <span v-if="form.errors.has('birth_date')"
                class="span-error is-invalid"
              >
                <has-error form="form" field="birth_date" />
              </span> -->
          </div>
        </div>

        <!-- <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex">
             
              <v-button :loading="form.busy" :class="'site-btn'">
                Crear usuario
              </v-button>

             
              <login-with-github />
            </div>
          </div> -->

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
import findIndex from "lodash/findIndex";
import Swal from "sweetalert2";
import Datepicker from "vuejs-datepicker";
import { es } from "vuejs-datepicker/dist/locale";
import vSelect from "vue-select";
export default {
  name: "UserForm",
  scrollToTop: true,

  components: {
    Datepicker,
    vSelect
  },

  data() {
    return {
      form: new Form({
        name: "",
        email: "",
        address: "",
        phone: "",
        location_id:'',
        birth_date:''
      }),
      es: es,
      dp: {
        date: ""
      }
    };
  },

  computed: {
    ...mapGetters({
      user: "users/user",
      pag: "users/page",
      locations: "locations/locations"
    }),
     getLocations() {
      return this.locations
        ? this.locations.map(l => {
            return { label: l.description, code: l.id };
          })
        : [];
    }
  },

  props: {
    edit: {
      default: false,
      type: Boolean
    }
  },

  async mounted() {
    var self = this;
    this.prePopulateForm();
  },

  methods: {
    prePopulateForm() {
      if (Object.keys(this.user).length != 0) {
        for (let key in this.user) {
          if (this.has(this.form, key)) {
            this.form[key] = this.user[key];
          }
        }

        this.form.id = this.user.id;
        this.dp.date = this.user.birth_date ?  this.moment(this.user.birth_date).toString() : ''
      } else {
        this.form.reset();
      }
    },

    close() {
      this.$emit("close");
    },

    async update() {
      var self = this;
      this.dp.date === '' ? this.form.birth_date = '' : 
      this.form.birth_date = this.moment(this.dp.date).format("YYYY-MM-DD");
      try {
        if (this.edit) {

          const { data } = await this.form.patch(`/api/users/${this.form.id}`);

          if (this.form.successful) {
            // pull premises after insert.
            this.$store.commit("users/setUser", data);
            this.fetchUsers();
            this.close();
             this.toast("Usuario actualizado !.");
          }
        } else {
          const { data } = await this.form.post("/api/users");

          if (this.form.successful) {
            // pull premises after insert.
            this.fetchUsers();
            this.close();
             this.toast("Usuario actualizado !.");
          }
        }
      } catch (error) {
        this.toast("Error !.");
      }
    },
    async fetchUsers() {
      await this.$store.dispatch("users/fetchUsers", {
        pag: this.page
      });
    }
  }
};
</script>
<style>
  .vs__dropdown-toggle{
    height: 40px;
  }
</style>