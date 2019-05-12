<template>
  <div class="container" id="container">
    <product-details :selectedProduct="selectedProduct"/>
    <a-layout class="white-bg">
      <a-layout-content class="pd-top-lg pd-btm-lg pd-lr-lg">
        <a-alert v-if="err_msg!==''" :message="err_msg" type="warning" closable @close="onClose"/>
        <br>
        <br>
        <div class="d-flex justify-content-center">
          <h1>ATDW fetcher for NSW</h1>
        </div>
        <br>
        <div>
          <a-select
            defaultValue="Regions"
            style="width: 120px"
            v-model="selectedRegionId"
            class="select"
          >
            <a-select-option
              v-for="region in regions"
              v-bind:key="region.RegionId"
              :value="region.RegionId"
            >{{region.Name}}</a-select-option>
          </a-select>
          <a-select
            defaultValue="Areas"
            style="width: 120px"
            v-model="selectedAreaId"
            class="select"
          >
            <a-select-option
              v-for="area in filteredAreas"
              v-bind:key="area.AreaId"
              :value="area.AreaId"
            >{{area.Name}}</a-select-option>
          </a-select>
          <a-input-search
            placeholder="input search text"
            style="width: 200px"
            @search="onSearch"
            class="select"
          />
        </div>
        <br>

        <div>
          <p>Number of result: {{numberOfResults}}</p>
        </div>
        <br>
        <br>
        <span>
          <a-list itemLayout="vertical" size="large" :dataSource="filteredProducts">
            <div slot="footer">
              <a-spin tip="Loading..." v-if="loading">
                <div class="spin-content"></div>
              </a-spin>
              <br>
              <b>Created by Derek He</b>
            </div>
            <a-list-item slot="renderItem" slot-scope="item, index" key="item.productId">
              <img slot="extra" width="272" alt="logo" :src="item.productImage">
              <a-list-item-meta :description="item.description">
                <a slot="title" @click="viewDetails(item.productId)">{{item.productName}}</a>
              </a-list-item-meta>
              {{item.productDescription}}
            </a-list-item>
          </a-list>
        </span>
      </a-layout-content>
    </a-layout>
  </div>
</template>

<script>
import axios from "axios";
import ProductDetails from "./components/ProductDetails.vue";
import EventBus from "./eventBus.js";
export default {
  name: "App",
  components: {
    ProductDetails
  },
  data() {
    return {
      // selection data
      regions: [],
      areas: [],
      selectedRegionId: "Regions",
      selectedAreaId: "Areas",
      selectedProduct: null,
      filteredAreas: [], //filtered areas when region is selected

      // filtered data from server
      filteredProducts: [],

      // feedback data
      err_msg: "",
      loading: false,
      numberOfResults: 0,

      // api request data
      currentPage: 1,
      query: {
        st: "nsw",
        ar: "",
        rg: "",
        term: "",
        pge: 1,
        size: 10,
        term: ""
      }
    };
  },
  watch: {
    selectedRegionId: function(newRgId, oldRgId) {
      // filter areas via region id
      this.getAreasByRegionId(newRgId);
    },
    currentPage: function(newPge, oldPge) {
      // update pge in query with new page no.
      this.updateQueryPage(newPge);
      // filter products when query change
      this.filterProducts();
    }
  },

  methods: {
    onSearch: function(term) {
      this.loading = true; // show loading UI
      this.currentPage = 1; // reset current page to 1

      // prepare query data for api
      this.setQueryOnSearch(term);
      //send request to php service and set filteredProducts
      this.filterProducts();
    },
    // prepare query data for api
    setQueryOnSearch: function(term) {
      this.selectedAreaId === "Areas"
        ? (this.query.ar = "")
        : (this.query.ar = this.selectedAreaId);
      this.selectedRegionId === "Regions"
        ? (this.query.rg = "")
        : (this.query.rg = this.selectedRegionId);
      term === ("" || undefined)
        ? (this.query.term = "")
        : (this.query.term = term);
    },
    // update pge prop in query obj
    updateQueryPage: function(page) {
      this.query.pge = page;
    },

    // filter products with query data
    filterProducts: function() {
      this.loading = true;
      const self = this;
      axios({
        method: "get",
        url: self.buildQueryUrl()
      })
        .then(function(res) {
          if (res.data.success) {
            const prods = res.data.products.products;
            self.numberOfResults = res.data.products.numberOfResults;
            if (self.currentPage === 1) {
              self.filteredProducts = prods;
            } else {
              prods.forEach(function(el) {
                self.filteredProducts.push(el); // add all new products to existing filteredProducts
              });
            }
          }
          self.loading = false;
        })
        .catch(function(e) {
          self.err_msg = e;
        });
    },
    // build query url to send request
    buildQueryUrl: function() {
      var url = "/products?";
      const self = this;
      const entries = Object.entries(self.query);
      entries.forEach(function(el) {
        url += el[0] + "=" + el[1] + "&";
      });
      url = url.slice(0, -1); // remove last char '&'
      return url;
    },
    // init selection options
    initOptions: function() {
      const self = this;
      axios({
        method: "get",
        url: "/options/nsw"
      })
        .then(function(res) {
          if (res.data.success) {
            self.regions = res.data.options.regions;
            self.areas = res.data.options.areas;
          }
        })
        .catch(function(e) {
          self.err_msg = e;
        });
    },

    getAreasByRegionId: function(regionId) {
      const self = this;
      const areas = this.areas;

      self.filteredAreas = [];
      self.selectedAreaId = "Areas";
      areas.forEach(function(el) {
        if (el.DomesticRegionId == regionId) {
          self.filteredAreas.push(el);
        }
      });
    },
    // show product details when user click title
    viewDetails: function(id) {
      this.setSelectedProduct(id);
    },
    setSelectedProduct: function(id) {
      const self = this;
      const products = this.filteredProducts;
      products.forEach(function(prod) {
        if (prod.productId === id) {
          self.selectedProduct = prod;
        }
      });
    },
    onClose(e) {
      console.log(e, "I was closed.");
    }
  },
  created: function() {
    this.initOptions();
    const self = this;
    $(window).scroll(function() {
      if ($(window).scrollTop() + $(window).height() == $(document).height()) {
        self.currentPage += 1;
      }
    });
  },
  mounted() {
    const self = this;
    // when receive signal, clear seletedProduct
    EventBus.$on("CLEAR_SELECTED_PRODUCT", function() {
      self.selectedProduct = null;
    });
  }
};
</script>

<style>
.header {
  background: #fff !important;
}
.spin-content {
  border: 1px solid #91d5ff;
  background-color: #e6f7ff;
  padding: 30px;
}
</style>
