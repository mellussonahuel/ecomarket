<template>
    <div class="star-rating" @updated="updated">
        <star-rating
            :inline="inline"
            v-model="value"
            :active-color="'#fc5961'"
            :round-start-rating="false"
            :read-only="readOnly"
            :star-size="size"
            :rounded-corners="true"
            :show-rating="false"
            @rating-selected ="set"
        ></star-rating>
    </div>
</template>

<script>
    import Form from 'vform'
    import StarRating from 'vue-star-rating'
    export default {
        name: 'Stars',
        props: {
            'company':{
                type:Object,
                required:true
            },
            'user-id':{
                type:Number,
                required:false
            },
            'inline':{
                type:Boolean,
                default:false
            },
            readOnly:{
                type:Boolean,
                default:false
            },
            size: {
                type: Number,
                default: 20
            }

        },
        components:{
            StarRating
        },
        mounted(){
            this.value = parseFloat(this.company.company_rating)
        },
        data: function() {
            return {
                form: new Form({
                    company_id : '',
                    value : ''
                }),
                value: 0,
                temp_value: null,
                ratings: [1, 2, 3, 4, 5]
            };
        },
        methods:{
            set(rating) {
                var self = this;
          
                if (!this.disabled) {
                    this.form.company_id = this.company.id
                    this.form.value = rating
                    this.form.post('/api/companies/set-rating').then(() => {
                        self.updated()
                    });
                }
            },

            updated(){
                this.$emit("updated");
            },

        }
    }
</script>


<style scoped>

</style>
