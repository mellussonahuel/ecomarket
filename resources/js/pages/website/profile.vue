<template>
  <div class="row" id="web-profile">
    <div class="col-lg-8 m-auto">
      <!-- <card v-if="mustVerifyEmail" :title="$t('register')">
        <div class="alert alert-success" role="alert">
          {{ $t('verify_email_address') }}
        </div>
      </card> -->
      <card  :title="'Perfil'">
        <form @submit.prevent="updateProfile" @keydown="form.onKeydown($event)">
          <!-- Name -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
            <div class="col-md-7">
              <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control" type="text" name="name">
              <has-error :form="form" field="name" />
            </div>
          </div>

           <!-- Email -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
            <div class="col-md-7">
              <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email">
              <has-error :form="form" field="email" />
            </div>
          </div>

          <!-- Address -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Dirección</label>
            <div class="col-md-7">
              <input v-model="form.address" :class="{ 'is-invalid': form.errors.has('address') }" class="form-control" type="text" name="address">
              <has-error :form="form" field="address" />
            </div>
          </div>

          <!-- Phone -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Teléfono</label>
            <div class="col-md-7">
              <input v-model="form.phone" :class="{ 'is-invalid': form.errors.has('phone') }" class="form-control" type="text" name="phone">
              <small>Usaremos este teléfono para coordinar la entrega de tu pedido.</small>
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

          <div class="form-group row auth-action-buttons">
            <div class="col-md-7 offset-md-3 d-flex">
              <!-- Submit Button -->
              <v-button :loading="form.busy" :class="'site-btn'">
                Actualizar perfil
              </v-button>

              <!-- GitHub Register Button -->
              <!-- <login-with-github /> -->
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
import { mapGetters } from 'vuex'
import Datepicker from "vuejs-datepicker";
import { es } from "vuejs-datepicker/dist/locale";
import vSelect from "vue-select";

export default {
  middleware: 'auth',

  components: {
    Datepicker,
    vSelect
  },

  metaInfo () {
    return { title: 'Perfil' }
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
        user: 'auth/user',
        locations: 'locations/locations'
    }),
    getLocations() {
      return this.locations
        ? this.locations.map(l => {
            return { label: l.description, code: l.id };
          })
        : [];
    }
},

created(){
   this.prePopulateForm()
},

  methods: {
     prePopulateForm() {         
        if (Object.keys(this.user).length != 0 ) {
            for (let key in this.user) {
                if (this.has(this.form, key)) {
                    this.form[key] = this.user[key]
                }
            }
           
            this.form.id = this.user.id
            this.dp.date = this.user.birth_date ?  this.moment(this.user.birth_date).toString() : ''
            //this.dp.date = this.user.birth_date ?  new Date(this.user.birth_date) : ''

        }else{
            
            this.form.reset()
        }
    },  
    async updateProfile(){
        // user-update
         try{
            this.dp.date === '' ? this.form.birth_date = '' : 
            this.form.birth_date = this.moment(this.dp.date).format("YYYY-MM-DD");
            const {data} = await this.form.post('/api/user-update')
            if (this.form.successful) {
              console.log(data)
                   await this.$store.dispatch('auth/updateUser', { user: data })
                    Swal.fire({
                        type: 'success',
                        text: 'Perfil actualizado !.',
                        confirmButtonText: 'Ok',
                    })
                    
                   

            }else {
            
            }

        }catch(error){
                console.error('Check your data', error)
        
        }
    }
  }
}
</script>
