// import axios from 'axios'
// import Cookies from 'js-cookie'
import store from './../store/index'
import Swal from 'sweetalert2'

const helpers = {

    data () {
        return {
            toaster:{}
        }
    },
    created: function () {
        this.toaster = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: true,
            timer: 3000,
            timerProgressBar: true,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })
    },
    methods: {
        /**
         * Get Boolean, True/False if viewport given
         * @param viewportSize
         * @returns {boolean}
         */
        toast(text){
           

              this.toaster.fire({
                icon: 'success',
                title: text
              })
        },

        getItemTotal(sale_type, price ,qty ,discount = 0, variant_price = null){
          console.log(sale_type)
          console.log(price)
          console.log(qty)
          console.log(discount)
          console.log(variant_price)

               
//       Peso
// helpers.js?8b4b:43 null
// helpers.js?8b4b:44 0
// helpers.js?8b4b:45 13.00
// helpers.js?8b4b:46 null
            
          if( sale_type === 'Unidad'){
                var total = price * qty 
                if(discount > 0){
                    total = total - ( discount / 100 ) * total
                }
                return  total
            }else{
                   
                var total = variant_price * qty 
                console.log('el total es')
                console.log(total)
                if(discount > 0){
                    total = total - ( discount / 100 ) * total
                }
           
                return total
            }
            
        },

      

        isViewport (viewportSize) {
            const width = Math.max(
                document.documentElement.clientWidth,
                window.innerWidth || 0
            )
            console.log('LOG', viewportSize)
            if (width > 0 && width <= 480 && viewportSize === 'xs') return true
            if (width > 480 && width <= 768 && viewportSize === 'sm') return true
            if (width > 768 && width <= 992 && viewportSize === 'md') return true
            if (width > 992 && width <= 1200 && viewportSize === 'lg') return true
            if (width > 1200 && viewportSize === 'xl') return true
            // small than sm but bigger than 0
            if (width > 0 && width <= 768 && viewportSize === 'sm-down') return true
            // small than md but bigger than 0
            if (width > 0 && width <= 992 && viewportSize === 'md-down') return true
            // small than lg but bigger than 0
            if (width > 0 && width <= 1200 && viewportSize === 'lg-down') return true
            // default, returns false.
            return false
        },
        /**
         * User logged in
         * @returns {boolean}
         */
        isUserLoggedIn () {
            if (store.getters['auth/check']) {
                return true
            }
            return false
        },

        /**
         * Is undefined
         * @param value
         * @returns {boolean}
         */
        isUndefined (value) {
            return typeof value === 'undefined'
        },

        /**
         * Is string
         * @param value
         * @returns {boolean}
         */
        isString (value) {
            return typeof value === 'string'
        },

        /**
         * Is boolean
         * @param value
         * @returns {boolean}
         */
        isBoolean (value) {
            return typeof value === 'boolean'
        },

        /**
         * is Object
         * @param value
         * @returns {boolean}
         */
        isObject (value) {
            return typeof value === 'object'
        },

        /**
         * Object Has Key
         * @param object
         * @param key
         * @returns {boolean|boolean}
         */
        has (object, key) {
            return this.isObject(object) && object.hasOwnProperty(key)
        },

        /**
         * Get
         * @param object
         * @param key
         * @param defaultValue
         * @returns {*}
         */
        get (object, key, defaultValue) {
            return this.has(object, key) ? object[key] : defaultValue
        },

        /**
         * Translate elements helper.
         * @param x
         * @param y
         * @returns {string}
         */
        translate (x, y) {
            return `translate(${x}, ${y})`
        },

        /**
         * Stringify Value PX
         * @param value
         * @returns {string}
         */
        px (value) {
            return `${value}px`
        },

        turnStatus() {
            return {
                in_queue: 1,
                processing: 2,
                processed: 3,
                canceled: 4
            }
        }
    }

}

export default helpers
