<template>
  <div id="category-form" class="form">
    <form @submit.prevent="importList" @keydown="form.onKeydown($event)">
      <div class="fields-container">
        <!-- Description-->
        <div class="form-group row">
          <div class="col-md-12">
            <div class="custom-file">
              <input
                :class="{ 'is-invalid': form.errors.has('file') }"
                type="file"
                class="custom-file-input"
                @change="setFile"
                id="customFile"
              />
              <label class="custom-file-label" for="customFile"
                >Selecione un archivo</label
              >
              <has-error :form="form" field="file" />
            </div>
          </div>
          <div class="col-12">
            <span>{{ fileName }}</span>
          </div>
        </div>
        <div class="col-12">
          <progress max="100" v-if="uploadPercentage" :value.prop="uploadPercentage"></progress>
           <div class="" v-if="loading">
              {{ uploadPercentage + '%'}}
            </div>
        </div>
        <!-- Submit Button -->
        <div class="form-group row mt-5">
          <div class="col-md-12">
            <v-button :loading="form.busy" class="btn-primary">
              Importar
            </v-button>

            <button
              type="button"
              class="btn-primary inverse"
              @click="closeModal"
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
import axios from "axios";
import { mapGetters } from "vuex";
import Swal from "sweetalert2";
export default {
  name: "ImportForm",
  scrollToTop: true,

  components: {},

  data() {
    return {
      message: null,
      form: new Form({
        file: ""
      }),
      file: {},
      fileName: "",
      uploadPercentage: 0,
      loading:false,
    };
  },

  computed: {
    ...mapGetters({
      page: "newsletter/page"
    })
  },

  created() {},

  methods: {
    closeModal() {
      this.$emit("close");
    },
    setFile(e) {
      this.file = e.target.files[0];
      this.fileName = this.file.name;
    },
    onProgress(percent){
      console.log(percent)
    },
    async importList() {
      var self = this
      let formData = new FormData();
      formData.append("file", this.file);
      self.loading = true
      await axios
        .post("/api/products/import", formData, {
          headers: {
            Accept: "application/octet-stream",
            responseType: "blob"
          },
          onUploadProgress: function( progressEvent ) {
                this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
            }.bind(this)
        })
        .then(function() {
          Swal.fire({
          text: "Archivo importado.",
          type: "success",
          confirmButtonText: '<i class="fa fa-thumbs-up"></i>',
          showCancelButton: false
        });
        
        // self.closeModal();
        })
        .catch(function() {
          console.log("FAILURE!!");
        }).finally( () => {
                self.loading = false
        } )
    }
  }
};
</script>

<style scoped></style>
