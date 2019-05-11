<template>
  <div class="container">
    <a-layout>
      <a-layout-content class="pd-top-lg pd-btm-lg pd-lr-lg">
        <div class="d-flex justify-content-center">
          <h1>ATDW fetcher</h1>
        </div>
        <span v-show="areas">
          <a-list itemLayout="vertical" size="large" :dataSource="listData">
            <div slot="footer">
              <b>ant design vue</b> footer part
            </div>
            <a-list-item slot="renderItem" slot-scope="item, index" key="item.title">
              <template slot="actions" v-for="{type, text} in actions">
                <span :key="type">
                  <a-icon :type="type" style="margin-right: 8px"/>
                  {{text}}iu
                </span>
              </template>
              <img
                slot="extra"
                width="272"
                alt="logo"
                src="https://gw.alipayobjects.com/zos/rmsportal/mqaQswcyDLcXyDKnZfES.png"
              >
              <a-list-item-meta :description="item.description">
                <a slot="title" :href="item.href">{{item.title}}</a>
                <a-avatar slot="avatar" :src="item.avatar"/>
              </a-list-item-meta>
              {{item.content}}
            </a-list-item>
          </a-list>
          <div class="d-flex justify-content-center">
            <a-pagination
              showSizeChanger
              @showSizeChange="onShowSizeChange"
              :defaultCurrent="3"
              :total="500"
            />
          </div>
        </span>
      </a-layout-content>
    </a-layout>
  </div>
</template>

<script>
import axios from "axios";
const listData = [];
for (let i = 0; i < 10; i++) {
  listData.push({
    href: "https://vue.ant.design/",
    title: `ant design vue part ${i}`,
    avatar: "https://zos.alipayobjects.com/rmsportal/ODTLcjxAfvqbxHnVXCYX.png",
    description:
      "Ant Design, a design language for background applications, is refined by Ant UED Team.",
    content:
      "We supply a series of design principles, practical patterns and high quality design resources (Sketch and Axure), to help people create their product prototypes beautifully and efficiently."
  });
}
export default {
  name: "App",
  data() {
    return {
      regions: [],
      areas: null,
      listData,
      pageSize: 20,
      current: 4,
      actions: [
        { type: "star-o", text: "156" },
        { type: "like-o", text: "156" },
        { type: "message", text: "2" }
      ]
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
  },

  created: function() {
    console.log("created");
  }
};
</script>

<style>
.header {
  background: #fff !important;
}
</style>
