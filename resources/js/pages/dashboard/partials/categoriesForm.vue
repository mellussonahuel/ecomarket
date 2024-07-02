<template>
    <div id="category-form" class="form">
        <form @submit.prevent="update" @keydown="form.onKeydown($event)">
        
            <div class="fields-container">

                 <!-- Load Images -->
                <!-- <div class="form-group row">
                    <div class="col-12">
                         <label> Imágenes</label>
                    </div>
                    <div class="col-md-12">
                       
                        <drag-drop :upload-url="'/api/categories/store-images'"></drag-drop>
                    </div>
                </div> -->

                  <!-- Images-->
                <!-- <div class="form-group row loaded-images" v-if="category && category.image">
                    <div class="col-12">
                         <label> Imágen</label>
                    </div>
                    <div class="col-md-12">
                        
                         
                        <img :src="`/categories/${category.image.name}`" alt="">
                        <div class="delete-image">
                            <button class="btn btn-delete" @click.prevent="deleteImageConfirmation(category.image.id)"><i class="fad fa-trash-alt"></i></button>
                        </div>   
                    </div>
                   
                </div> -->
                
                <!-- Categoria padre -->
                <div class="form-group row">
                    <div class="col-md-12">
                        <label>Categoría padre</label>
                         <v-select key="1" 
                            v-model="form.parent_id"  
                            :reduce="c => c.id"
                            :label="'description'"  
                            :options="getParents" >
                        </v-select>
                    </div>
                </div>

                 <!-- Description-->
                <div class="form-group row">
                    <div class="col-md-12">
                        <label> Descripción</label>
                        <input v-model="form.description" class="form-control" type="text" name="description">
                        <has-error :form="form" field="description"/>
                    </div>
                </div>

               <!-- Active -->
               <div class="form-check " >
                    <input class="form-check-input mt-2" type="checkbox" name="active"   v-model="form.active">
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

                        <button type="button" class="btn-primary inverse" @click="closeModal">
                            Cancelar
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</template>

<script>
    import Form from "vform"
    import {mapGetters} from 'vuex'
    import DragDrop from '../../../components/DragDrop'
    import Swal from 'sweetalert2'
    import axios from 'axios'
    import Multiselect from 'vue-multiselect'
    import vSelect from 'vue-select'

    export default {
        name: "CategoryForm",
        scrollToTop: true,

        components: {
           DragDrop,
           Multiselect,
           vSelect,
        },

        data() {
            return {
                message: null,
               
                form: new Form({
                    description: '',
                    active: 1,
                    parent_id:''
                }),
                activeCategories: null
            }
        },

        computed: {
            ...mapGetters({
                category : 'categories/category',
                categories: 'categories/categories',
                image:'categories/image',
                page:'categories/page'
            }),
            getParents(){
                return this.activeCategories ?  this.activeCategories.filter( c => c.parent_id === null && c.active === 1 ) : []
            }
        },

        props: {
            edit: {
                default: false,
                type: Boolean
            },
        
        },

        created() {
            this.prePopulateForm();
            this.getActiveCategories()
            var self = this
             eventBus.$on('images-uploaded', () => {
                self.getCategory()
                self.getCategories()
                self.form.busy  = false
                self.toast(self.edit == true ? 'Categoría actualizada.' : 'Categoría creada!.')
                self.prePopulateForm();
            })
        },

        methods: {
             
             prePopulateForm() {
              
                if (this.edit === true && Object.keys(this.category).length != 0 ) {
                    for (let key in this.category) {
                        if (this.has(this.form, key)) {
                            this.form[key] = this.category[key]
                        }
                    }
                    this.form.id = this.category.id
                }
            },

          

            closeModal() {
                this.$emit('close');
            },

    
            async update() {
                var self = this
                try {
                    if (this.edit) {
                        const {data} = await this.form.patch(`/api/categories/${this.form.id}`)
                        
                        if (this.form.successful) {
            
                            // pull premises after insert.
                           // await this.$store.commit('categories/setCategory', data)                       
                         
                            self.toast(self.edit == true ? 'Categoría actualizada.' : 'Categoría creada!.')
                            self.getCategories()
                            self.close()

                        }
                    } else {
                        const {data} = await this.form.post('/api/categories')
                        if (this.form.successful) {
                            // pull premises after insert.
                            // await this.$store.commit('categories/setCategory', data)                      
                            self.toast(self.edit == true ? 'Categoría actualizada.' : 'Categoría creada!.')
                            self.getCategories()
                            self.close() 
                            
                        }
                    }
                } catch (error) {
                    this.toast('Error !.')
                }
        
            },
            async storeImages(){
                
                this.form.busy = true
                
                eventBus.$emit('upload-images',this.category)
        
                   
            },
            async getActiveCategories(){
                 const {data} =  await axios.get(`/api/categories/get-categories`)
                 this.activeCategories = data  
            },
            close() {
                this.$emit("close");
            },
             async getCategories(){
               await this.$store.dispatch('categories/fetchAll',{
                    pag: this.page
                })
           },
            async getCategory(){
                await this.$store.dispatch('categories/getCategoryData', this.category.slug)
            },
             deleteImageConfirmation(id){
                var self = this
                  Swal.fire({
                    text: 'Eliminar imágen ?',
                    type: 'warning',
                    confirmButtonText: 'Ok',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                }).then(function (isConfirm) {
                    if (isConfirm.value) {
                        self.deleteImage(id)
                    }
                })
            },
            async deleteImage(image_id){

                var self = this
                //  const index = this.form.product_variants.findIndex(i => i.description === description);
                // this.form.product_variants.splice(index,1)
                 await axios.post('/api/categories/delete-image',{id:image_id})
                    .then(function (response) {
                        if(response){

                           self.getCategory();
                        }
                    })
                    .catch(function (error) {
                        
                    });
            },

        }

    }

</script>

<style scoped>

</style>
