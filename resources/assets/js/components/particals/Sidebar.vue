<template>
    <div id="sidebar-wrapper" @click="hideWrapper">
        <ul class="sidebar-nav" @click.stop="">
            <div class="user">
                <div class="avatar">
                    <img class="img-responsive img-circle" :src="user.avatar ? user.avatar : 'http://lncoa.app/images/default.png'">
                </div>
                <div class="nickname">
                    <p>{{ user.nickname ? user.nickname : user.name }}</p>
                    <span v-for="role in user.roles">
                      <el-tag type="success" v-for="data in role" :key="data.id">{{ data.display_name }}</el-tag>
                    </span>
                </div>
                <div class="buttons">
                    <a href="/">
                        <i class="ion-ios-home"></i>
                    </a>
                    <a @click="userHome">
                        <i class="ion-person"></i>
                    </a>
                    <a @click="setting">
                        <i class="ion-ios-gear"></i>
                    </a>
                </div>
            </div>
            <el-col>
                <el-menu :router="true" theme="dark"
                  :unique-opened="true" :default-active="defaultActive" :default-openeds="defaultMenu" @select="select">
                  <el-submenu :index="menu.id+''" v-for="menu in menus" :key="menu.id" v-if="menu.items.length != 0" class="menu-height">
                    <template slot="title"><i :class="menu.icon"></i>{{ $t("el.sidebar."+menu.title) }}</template>
                      <el-menu-item :index="item.uri" v-for="item in menu.items" :key="item.id"><i :class="item.icon"></i>{{ $t("el.sidebar."+item.title) }}</el-menu-item>
                  </el-submenu>
                  <el-menu-item :index="menu.uri" v-else><i :class="menu.icon"></i>{{ $t("el.sidebar."+menu.title) }}</el-menu-item>
                  <el-menu-item index="logout" v-if="show" @click="logout"><i class="ion-power"></i>{{$t('el.page.logout')}}</el-menu-item>
                </el-menu>
            </el-col>
        </ul>
    </div>
</template>

<script>
    import server from '../../config/api'
    import { mapState,mapMutations } from 'vuex'
    export default {
        data () {
            return {
                menus: {},
                defaultActive: null,
                defaultMenu: []
            }
        },
        created() {
            let vm = this;
            axios.get(server.api.menu).then((response) => {
                vm.menus=response.data;
                this.defaultMenuId(vm.menus);
            });
            let path = this.$route.path;
            //根据当前路由设置菜单默认选中项
            this.defaultActive = path.substr(0,path.indexOf('/',1)!=-1?path.indexOf('/',1):path.length);
        },
        computed: {
            ...mapState([
                'user'
            ]),
            show() {
              return !$.isEmptyObject(this.menus);
            }
        },
        watch: {
            '$route': 'currentDefaultActive'
        },
        methods: {
          ...mapMutations([
              'OUT_LOGIN',
              'TOGGLE'
          ]),
         defaultMenuId(menus) {
            //当前应打开的菜单组
            for(var menu in menus){
               for(let value in menus[menu]['items']){
                  if(menus[menu]['items'][value]['uri'] == this.defaultActive) {
                      this.defaultMenu.push(menus[menu]['id']+'');
                  }
              }
           }
        },
        currentDefaultActive() {
          let path = this.$route.path;
          //根据当前路由设置菜单默认选中项
          this.defaultActive = path.substr(0,path.indexOf('/',1)!=-1?path.indexOf('/',1):path.length);
        },
        userHome() {
            this.$router.push('/user/profile');
            this.defaultActive = '';
            this.hideWrapper();
        },
        setting() {
            this.$router.push('/user/setting');
            this.defaultActive = '';
            this.hideWrapper();
        },
        // 退出
        logout: function(){
            this.OUT_LOGIN();
            this.$router.push('/login');
        },
        // 手机情况下点击菜单隐藏左侧
        hideWrapper() {
            if(this.$store.state.isPhone) {
              this.TOGGLE();
            }
        },
        //菜单激活回调
        select() {
            this.hideWrapper();
        }
    }
  }
</script>

<style lang="scss" scoped>
  @media (min-width: 768px){
      /*凹槽宽度*/
      #sidebar-wrapper::-webkit-scrollbar{
        width:4px;
        height:4px;
      }
      /*拖动条*/
      #sidebar-wrapper::-webkit-scrollbar-thumb{
        background-color:#2f3c4e;
        border-radius:6px;
      }
      /*背景槽*/
      #sidebar-wrapper::-webkit-scrollbar-track{
        background-color:#324157;
        border-radius:6px;
      }
  }
  @media (max-width: 768px){
    #sidebar-wrapper {
      z-index: 10001;
      position: fixed;
      left: 250px;
      width: 0;
      height: 100%;
      margin-left: -250px;
      overflow-y: auto;
      background: #324157;
      -webkit-transition: all 0.5s ease;
      transition: all 0.5s ease;
    }
  }
  .sub-menu {
        cursor: pointer;
  }
  .sub-menu ul {
        list-style-type: none;
        display: none;
  }

  .sidebar-nav {
      position: absolute;
      top: 0;
      width: 250px;
      margin: 0;
      padding: 0;
      list-style: none;
      background: #324157;
      height: 100%;
  }

  .sidebar-nav li {
      text-indent: 20px;
      line-height: 40px;
  }

  .navbar {
      margin-bottom: 0;
  }

  .sidebar-nav li .user {
      display: block;
      text-align: center;
      width: 100%;
      background-color: #3d4e60;
      padding-top: 20px;
      padding-bottom: 10px;
      color: #fff;
  }

  .user {
      text-align: center;
      padding-top: 15px;
      background-color: #324b57;
  }

  .user .avatar {
      width: 80px;
      margin: 10px auto;
  }

  .nickname {
      color: #fff;
  }

  .buttons {
      height: 50px;
  }
  .buttons a {
      display: inline-block;
      font-size: 20px;
      width: 40px;
      height: 40px;
      line-height: 40px;
      margin-right: 5px;
      color: #828a9a;
  }
  .buttons a:hover {
      font-size: 30px;
      color: #fff;
  }
  .sidebar-nav li a {
      display: block;
      text-decoration: none;
      color: #999999;
  }

  .sidebar-nav li a:hover {
      text-decoration: none;
      color: #fff;
      background: rgba(255,255,255,0.2);
  }

  .sidebar-nav li .active {
      color: #fff !important;
  }

  // .sidebar-nav li a i {
  //     padding-right: 10px;
  // }

  .el-menu [class^=ion] {
      vertical-align: baseline;
      padding-right: 10px;
  }

  .el-menu [class^=el-menu] {
      height: 40px;
  }

  .sidebar-nav li a:active,
  .sidebar-nav li a:focus {
      text-decoration: none;
  }

  .active {
      background-color: #3d4e60;
      border-right: 4px solid #647f9d;
  }
  .active a {
      color: #fff !important;
  }

  .sidebar-nav > .sidebar-brand {
      height: 65px;
      font-size: 18px;
      line-height: 60px;
  }

  .sidebar-nav > .sidebar-brand a {
      color: #999999;
  }

  .sidebar-nav > .sidebar-brand a:hover {
      color: #fff;
      background: none;
  }

  .sidebar-nav-width {
      width: 233px
  }

  .logout {
      cursor: pointer;
  }
</style>
