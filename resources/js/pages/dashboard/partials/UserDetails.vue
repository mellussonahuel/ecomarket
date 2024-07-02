<template>
  <div id="users-details">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a
          class="nav-link active"
          @click="currentTab = 'profile'"
          data-toggle="tab"
          role="tab"
          aria-controls="profile"
          aria-selected="true"
          >Datos del usuario</a
        >
      </li>
      <li class="nav-item">
        <a
          class="nav-link"
          data-toggle="tab"
          @click="currentTab = 'history'"
          role="tab"
          aria-selected="false"
          >Historial</a
        >
      </li>
    </ul>
    <div class="tab-content" id="profile-tab">
      <transition-group name="fade" mode="out-in">
        <div
          key="profile"
          v-if="currentTab === 'profile'"
          class="tab-pane fade show "
          :class="{ active: currentTab === 'profile' }"
          id="profile-tab"
          role="tabpanel"
          aria-labelledby="profile-tab"
        >
          <div class="card">
            <div class="card-body">
              <user-form :edit="true"></user-form>
            </div>
          </div>
        </div>
        <div
          key="history"
          v-if="currentTab === 'history'"
          class="tab-pane fade show "
          :class="{ active: currentTab === 'history' }"
          id="history-tab"
          role="tabpanel"
          aria-labelledby="history-tab"
        >
          <user-sales-list></user-sales-list>
        </div>
      </transition-group>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import { mapGetters } from "vuex";
import Swal from "sweetalert2";
import axios from "axios";
import UserForm from "./UserForm";
import UserSaleList from './UserSalesList'
import UserSalesList from './UserSalesList.vue';

export default {
  name: "UserDetails",
  scrollToTop: true,

  components: {
    UserForm,
    UserSalesList
  },

  data() {
    return {
      currentTab: "profile"
    };
  },

  computed: {
    ...mapGetters({})
  },

  props: {
    edit: {
      default: false,
      type: Boolean
    }
  },

  created() {},

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

      const { data } = await axios.post(`/api/coupons/generate-code`);

      if (data.CODE === "success") {
        this.form.code = data.data;
      }

      this.isGeneratingCode = false;
    },

    async update() {
      var self = this;
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
