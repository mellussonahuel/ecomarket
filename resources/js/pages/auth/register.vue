<template>
  <div class="row" id="web-register">
    <div class="col-lg-8 m-auto">
      <card v-if="mustVerifyEmail" :title="$t('register')">
        <div class="alert alert-success" role="alert">
          {{ $t("verify_email_address") }}
        </div>
      </card>
      <card v-else :title="$t('register')">
        <form @submit.prevent="register" @keydown="form.onKeydown($event)">
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
            <label class="col-md-3 col-form-label text-md-right"
              >Dirección</label
            >
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
            <label class="col-md-3 col-form-label text-md-right"
              >Teléfono</label
            >
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
             
              <span v-if="form.errors.has('birth_date')"
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
                  'is-invalid': form.errors.has('location_id'),'location-select':true
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

          <!-- Password -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{
              $t("password")
            }}</label>
            <div class="col-md-7">
              <input
                v-model="form.password"
                :class="{ 'is-invalid': form.errors.has('password') }"
                class="form-control"
                type="password"
                name="password"
              />
              <has-error :form="form" field="password" />
            </div>
          </div>

          <!-- Password Confirmation -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{
              $t("confirm_password")
            }}</label>
            <div class="col-md-7">
              <input
                v-model="form.password_confirmation"
                :class="{
                  'is-invalid': form.errors.has('password_confirmation')
                }"
                class="form-control"
                type="password"
                name="password_confirmation"
              />
              <has-error :form="form" field="password_confirmation" />
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex">
              <!-- Submit Button -->
              <v-button :loading="form.busy" :class="'site-btn'">
                Crear usuario
              </v-button>

              <!-- GitHub Register Button -->
              <login-with-github />
            </div>
          </div>
        </form>
      </card>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import LoginWithGithub from "~/components/LoginWithGithub";
import Swal from "sweetalert2";
import vSelect from "vue-select";
import { mapGetters } from "vuex";
import Datepicker from "vuejs-datepicker";
import { es } from "vuejs-datepicker/dist/locale";
export default {
  middleware: "guest",

  components: {
    LoginWithGithub,
    Datepicker,
    vSelect
  },

  metaInfo() {
    return { title: this.$t("register") };
  },

  data: () => ({
    form: new Form({
      name: "",
      email: "",
      address: "",
      location_id: "",
      birth_date: "",
      phone: "",
      password: "",
      password_confirmation: ""
    }),
    dp: {
      date: ''
    },
    mustVerifyEmail: false,
    es: es
  }),

  computed: {
    ...mapGetters({
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

  created() {
    if (!this.locations) {
      this.fetchLocations();
    }
  },

  methods: {
    async register() {
      var self = this;
      this.dp.date === '' ? this.form.birth_date = '' : 
      this.form.birth_date = this.moment(this.dp.date).format("YYYY-MM-DD");
      // Register the user.
      const { data } = await this.form.post("/api/register");

      // Must verify email fist.
      if (data.status) {
        this.mustVerifyEmail = true;
      } else {
        Swal.fire({
          html: "Usuario creado! <br> Gracias por registrarte en Eco Market!",
          type: "success",
          confirmButtonText: "Ir a la tienda"
        }).then(function(isConfirm) {
          if (isConfirm.value) {
            self.loginAndRedirect(data);
          }
        });
      }
    },
    async loginAndRedirect(data) {
      // Log in the user.
      const {
        data: { token }
      } = await this.form.post("/api/login");

      // Save the token.
      this.$store.dispatch("auth/saveToken", { token });

      // Update the user.
      await this.$store.dispatch("auth/updateUser", { user: data });

      // Redirect home.
      this.$router.push({ name: "website.shop" });
    }
  }
};
</script>
