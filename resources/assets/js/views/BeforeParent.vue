<template>
  <div class="before-parent">
    <h5 class="local">
      <span class="lang" @click="switchLang('zh_cn')" v-bind:class="{active: lang!='en'}">中文</span>
      <span>/</span>
      <span class="lang" @click="switchLang('en')" v-bind:class="{active: lang=='en'}">En</span>
    </h5>
    <router-view></router-view>
  </div>
</template>

<script>
    export default {
        data () {
            return {
                lang: {},
            }
        },
        mounted() {
            this.lang = window.Language;
        },
        methods: {
            switchLang: function(lang){
                Vue.config.lang = lang;
                this.lang = lang;
                localStorage.Language = lang;
            },
        }
    }
</script>

<style lang="css">
    body {
      color: #5D5F63;
      background: #293949;
      font-family: 'Open Sans',sans-serif;
      padding: 0;
      margin: 0;
      text-rendering: optimizeLegibility;
      -webkit-font-smoothing: antialiased;
    }
    .before-parent .local {
        color: #fff;
        text-align: right;
        top: 25px;
        right: 50px;
        position: absolute;
        z-index: 1;
    }
    .before-parent .lang {
        cursor: pointer;
        opacity: .6;
    }
    .before-parent .active {
        font-weight: 700;
        opacity: 1;
    }
</style>
