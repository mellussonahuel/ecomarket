<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="close" @click="close">
                        <i class="fal fa-times"></i>
                    </div>

                    <div class="modal-header" v-if="showHeader">
                        <slot name="header">
                            Default header
                        </slot>
                    </div>

                    <div class="modal-body" v-if="showBody">
                        <slot name="body">
                            Default body
                        </slot>
                    </div>

                    <div class="modal-footer" v-if="showFooter">
                        <slot name="footer">
                            <button class="btn-primary" @click.prevent="close">
                                Close
                            </button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
  export default {
    name: 'Modal',

    props: {
      showHeader: {
        default: true,
        type: Boolean
      },
      showBody: {
        default: true,
        type: Boolean
      },
      showFooter: {
        default: true,
        type: Boolean
      },
    },

    methods: {
      close() {
        this.$emit("close");
      }
    }
  }
</script>
