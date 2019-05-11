<template>
  <div>
    <a-pagination
      showSizeChanger
      @showSizeChange="onShowSizeChange"
      :defaultCurrent="3"
      :total="500"
    />
  </div>
</template>
<script>
export default {
  name: "pagination",
  data() {
    return {
      pageSize: 20,
      current: 4
    };
  },
  watch: {
    pageSize(val) {
      console.log("pageSize", val);
    },
    current(val) {
      console.log("current", val);
    }
  },
  methods: {
    onShowSizeChange(current, pageSize) {
      console.log(current, pageSize);
    }
  }
};
</script>
