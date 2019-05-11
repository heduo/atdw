<template>
  <v-app>
    <p>Vue app</p>
    <pagination/>
  </v-app>
</template>

<script>
import axios from "axios";
import pagination from "./components/Pagination.vue";

export default {
  components: {
    pagination
  }
};
</script>
