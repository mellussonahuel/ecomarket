<template>
  <button :type="nativeType" :disabled="loading" :class="{
    [`${type}`]: true,
    'btn-block': block,
    'btn-lg': large,
    'btn-loading': loading
  }" class="btn"
  >
    <slot />
  </button>
</template>

<script>
export default {
  name: 'VButton',

  props: {
    type: {
      type: String,
      default: 'btn-default'
    },

    nativeType: {
      type: String,
      default: 'submit'
    },

    loading: {
      type: Boolean,
      default: false
    },

    block: {
      type: Boolean,
      default: false
    },

    large: {
      type: Boolean,
      default: false
    }
  }
}
</script>
