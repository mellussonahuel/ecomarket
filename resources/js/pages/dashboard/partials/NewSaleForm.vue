<template>
    <div class="form" id="product-form">
        <form @submit.prevent="update" @keydown="form.onKeydown($event)">
        
            <div class="fields-container">

                <div class="header">
                    Nueva venta
                </div>


                <!-- Submit Button -->
                <div class="form-group row mt-5">
                    <div class="col-md-12">
                        <v-button :loading="form.busy" class="btn-primary">
                            Guardar
                        </v-button>

                        <button type="button" class="btn-primary inverse" @click="close">
                            {{ $t('cancel') }}
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</template>

<script>
    import Form from "vform"
    import axios from 'axios'
    import {mapGetters} from 'vuex'
    import Multiselect from 'vue-multiselect'
    import findIndex from 'lodash/findIndex'
    import Swal from 'sweetalert2'
    import vSelect from 'vue-select'
 
    export default {
        name: "NewSaleForm",
        scrollToTop: true,

        components: {
            Multiselect,
            vSelect,
        },

        data() {
            return {
                message: null,
                showVariantCreate:false,
                form: new Form({
                   
                }),
                 
            }
        },

        computed: {
            ...mapGetters({
                product: 'product/product',
                page:'product/page',
            }),

        },

        props: {
           userid:{
               type: Number,
               required: true
           }
        },

        async mounted() {
           
        },
        
        methods: {
             
            setSaleType(saletype){
                this.form.sale_type = saletype.description
            },

             close() {
                this.$emit("close");
            },
        }

    }

</script>

<style scoped lang="scss">
    .delete-image{
        position: absolute;
        right: 10px;
        top: 0px;
    }

    .images-container {
        img:hover{
            cursor: move;
        }
        span{
            display: flex;
            width: 100%;
            overflow: auto;
            padding: 15px;
        }
    }
</style>
<style>
.trix-content {
  height: 300px !important;   
    overflow-y: auto;
}
</style>