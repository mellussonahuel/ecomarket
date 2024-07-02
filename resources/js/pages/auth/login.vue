<template>
  <div id="web-login">
    <div class="row">
      <div class="col-lg-8 m-auto">
        <card :title="$t('login')">
          <form @submit.prevent="login" @keydown="form.onKeydown($event)">
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

            <!-- Remember Me -->
            <div class="form-group row">
              <div class="col-md-3" />
              <div class="col-md-7 d-flex">
                <checkbox v-model="remember" name="remember">
                  {{ $t("remember_me") }}
                </checkbox>

                <router-link
                  :to="{ name: 'password.request' }"
                  class="small ml-auto my-auto forgot-password"
                >
                  {{ $t("forgot_password") }}
                </router-link>
              </div>
            </div>

            <div class="form-group row auth-action-buttons">
              <div class="col-12 col-md-7 offset-md-3 d-flex">
                <!-- Submit Button -->
                <v-button :loading="form.busy" :class="'site-btn'">
                  {{ $t("login") }}
                </v-button>
              </div>
              <div class="col-12 col-md-7 offset-md-3 d-flex">
                <router-link
                  :to="{ name: 'register' }"
                  :class="['site-btn']"
                  active-class="active"
                  exact
                >
                  <i class="far fa-sign-in-alt "></i> Registrarse
                </router-link>
              </div>
            </div>
          </form>
        </card>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import LoginWithGithub from "~/components/LoginWithGithub";
import { mapGetters } from "vuex";

export default {
  middleware: "guest",

  components: {
    LoginWithGithub
  },

  computed: mapGetters({
    user: "auth/user"
  }),

  metaInfo() {
    return { title: this.$t("login") };
  },

  data: () => ({
    form: new Form({
      email: "",
      password: ""
    }),
    redirectTo: null,
    remember: false
  }),

  methods: {
    async login() {
      // Submit the form.

      const { data } = await this.form.post("/api/login");

      // Save the token.
      this.$store.dispatch("auth/saveToken", {
        token: data.token,
        remember: this.remember
      });

      // Fetch the user.
      await this.$store.dispatch("auth/fetchUser");

      // set favorites
      await this.$store.dispatch("auth/setFavorites");

      console.log(this.$route.query.redirect);
      this.$route.query.redirect
        ? this.$router.push({ name: this.$route.query.redirect })
        : this.$router.push({ name: "website.home" });
    }
  }
};
</script>
