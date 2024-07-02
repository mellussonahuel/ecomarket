<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <card :title="$t('reset_password')">
        <form @submit.prevent="send" @keydown="form.onKeydown($event)">
         
          <!-- Email -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
            <div class="col-md-9">
              <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email">
              <has-error :form="form" field="email" />
            </div>
          </div>

          <!-- Submit Button -->
          <div class="form-group row auth-action-buttons">
            <div class="col-md-9 ml-md-auto">
              <v-button :loading="form.busy" :class="'site-btn'">
                  Enviar Enlace de Restablecimiento de Contraseña
              </v-button>
            </div>
          </div>
        </form>
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import Swal from 'sweetalert2'

export default {
  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('reset_password') }
  },

  data: () => ({
    status: '',
    form: new Form({
      email: ''
    })
  }),

  methods: {
    async send () {
      const { data } = await this.form.post('/api/password/email')

      this.status = data.status

       Swal.fire({
            type: 'success',
            text:  data.status,
            confirmButtonText: 'Ok',
        })

      this.form.reset()
    }
  }
}
</script>
