<template>
  <a-modal
    title="Product Details"
    :visible="visible"
    @ok="handleOk"
    :confirmLoading="confirmLoading"
    @cancel="handleCancel"
    width="80%"
    v-if="selectedProduct"
  >
    <div class="jumbotron jumbotron-fluid white-bg">
      <div class="container">
        <h3 class="display-4">{{selectedProduct.productName}}</h3>
        <p>
          <strong>Id:</strong>
          {{selectedProduct.productNumber}}
        </p>
        <p>
          <strong>Address:</strong>
          {{address}}
        </p>
        <br>
        <img :src="selectedProduct.productImage" alt="product image">
        <br>
        <br>
        <p class="lead">{{selectedProduct.productDescription}}</p>
      </div>
    </div>
  </a-modal>
</template>

<script>
import EventBus from "./../eventBus.js";
export default {
  name: "ProductDetails",
  data() {
    return {
      confirmLoading: false
    };
  },
  props: ["selectedProduct"],
  computed: {
    visible() {
      return this.selectedProduct ? true : false;
    },
    // build product address
    address() {
      return (
        this.selectedProduct.addresses[0]["address_line"] +
        " " +
        this.selectedProduct.addresses[0]["city"] +
        " " +
        this.selectedProduct.addresses[0]["state"] +
        " " +
        this.selectedProduct.addresses[0]["postcode"]
      );
    }
  },
  methods: {
    handleOk(e) {
      this.confirmLoading = false;
      this.clearSelectedProduct();
    },
    handleCancel(e) {
      this.clearSelectedProduct();
    },
    clearSelectedProduct() {
      EventBus.$emit("CLEAR_SELECTED_PRODUCT"); // emit signal to clear selected product in parent component
    }
  }
};
</script>
