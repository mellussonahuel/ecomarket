<template>
  <div id="location-form" class="form">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <div class="fields-container">
        <!-- Description-->
        <div class="form-group row">
          <div class="col-md-12">
            <label> Description</label>
            <input
              v-model="form.description"
              class="form-control"
              type="text"
              name="description"
              :class="{ 'is-invalid': form.errors.has('description') }"
            />
            <has-error :form="form" field="description" />
          </div>
        </div>

        <!-- Description-->
        <div class="form-group row">
          <div class="col-md-12">
            <label> Mínimo de compra <small>($)</small></label>
            <input
              v-model="form.min"
              class="form-control"
              type="text"
              name="min"
              :class="{ 'is-invalid': form.errors.has('min') }"
            />
            <has-error :form="form" field="min" />
          </div>
        </div>
        <!-- Active -->
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
        <!-- Submit Button -->
        <div class="form-group row mt-5">
          <div class="col-md-12">
            <v-button :loading="form.busy" class="btn-primary">
              Guardar
            </v-button>

            <button
              type="button"
              class="btn-primary inverse"
              @click="close"
            >
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
import Swal from "sweetalert2";
import axios from "axios";

export default {
  name: "LocationsForm",
  scrollToTop: true,

  components: {},

  data() {
    return {
      message: null,

      form: new Form({
        description: "",
        min: "",
        active: 1
      })
    };
  },

  computed: {
    ...mapGetters({
      page: "locations/page",
      location: "locations/location"
    })
  },

  props: {
    edit: {
      default: false,
      type: Boolean
    }
  },

  created() {
      this.prePopulateForm()
  },

  methods: {
    close() {
      this.$emit("close");
    },

    prePopulateForm() {
      if (this.edit === true && Object.keys(this.location).length != 0) {
        for (let key in this.location) {
          if (this.has(this.form, key)) {
            this.form[key] = this.location[key];
          }
        }
        this.form.id = this.location.id;
      }
    },

    async getLocations(pag = 1) {
      await this.$store.dispatch("locations/fetchLocations", {
        pag: this.page
      });
    },

    async update() {
      var self = this;
      try {
        if (this.edit) {
          const { data } = await this.form.patch(
            `/api/locations/${this.form.id}`
          );

          if (this.form.successful) {
            // pull premises after insert.
            // await this.$store.commit('categories/setCategory', data)
            console.log('lelga')
            self.toast(
              self.edit == true ? "Locación actualizada." : "Locacion creada!."
            );
            self.getLocations();
            self.close();
          }
        } else {
          const { data } = await this.form.post("/api/locations");
          if (this.form.successful) {
            // pull premises after insert.
            // await this.$store.commit('categories/setCategory', data)
            self.toast(
              self.edit == true ? "Locación actualizada." : "Locacion creada!."
            );
            self.getLocations();
            self.close();
          }
        }
      } catch (error) {
        this.toast("Error !.");
      }
    }
  }
};
</script>

<style scoped></style>
