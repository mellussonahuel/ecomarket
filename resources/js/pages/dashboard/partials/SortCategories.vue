<template>
  <div id="order-categories" class="form">
    <draggable v-model="sortedCategories" @end="sortCategories" :forceFallback="true">
      <transition-group>
        <div
          class="col-12 position-relative "
          v-for="(category, index) in categories"
          :key="index + '-primg'"
        >
         <div class="item">
          <i class="fad fa-expand-arrows-alt"></i> {{ category.description }}
         </div>
        </div>
      </transition-group>
    </draggable>
  </div>
</template>

<script>
import Form from "vform";
import { mapGetters } from "vuex";
import Swal from "sweetalert2";
import axios from "axios";
import draggable from "vuedraggable";

export default {
  name: "SortCategories",
  scrollToTop: true,

  components: {
    draggable
  },

  data() {
    return {
      message: null,

      form: new Form({
        email: ""
      }),
      
      
    };
  },

  computed: {
    ...mapGetters({
      categories: "categories/categories"
    }),

    sortedCategories: {
          get() {
              return this.categories
          },
          set(value) {
              this.$store.commit('categories/setCategories', value)
          }
      },
  },

  created() {},

  methods: {
    closeModal() {
      this.$emit("close");
    },
    async sortCategories() {
      var form = new Form();
      form.categories = this.sortedCategories;
      form.post("/api/categories/sort");
    }
  }
};
</script>

<style scoped></style>
