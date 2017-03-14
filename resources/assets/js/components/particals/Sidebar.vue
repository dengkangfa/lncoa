<template>
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <div class="user">
                <div class="avatar">
                    <img class="img-responsive img-circle" :src="user.avatar ? user.avatar : 'http://lncoa.app/images/default.png'">
                </div>
                <div class="nickname">
                    <p>{{ user.name }}</p>
                    <p>[{{ user.role_name }}]</p>
                </div>
                <div class="buttons">
                    <a href="/">
                        <i class="ion-ios-home"></i>
                    </a>
                    <a :href="userInfo">
                        <i class="ion-person"></i>
                    </a>
                    <a href="/setting">
                        <i class="ion-ios-gear"></i>
                    </a>
                </div>
            </div>
            <el-col>
                <el-menu :router="true" theme="dark"
                  :unique-opened="true" default-active="">
                  <el-submenu :index="menu.uri" v-for="menu in tree" v-if="menu.items.length != 0" class="menu-height">
                    <template slot="title"><i :class="menu.icon"></i>{{ $t("sidebar."+menu.title) }}</template>
                      <el-menu-item :index="item.uri" v-for="item in menu.items"><i :class="item.icon"></i>{{ $t("sidebar."+item.title) }}</el-menu-item>
                  </el-submenu>
                  <el-menu-item :index="menu.uri" v-else><i :class="menu.icon"></i>{{ $t("sidebar."+menu.title) }}</el-menu-item>
                </el-menu>
            </el-col>
        </ul>
    </div>
</template>

<script>
    import server from '../../config/api'
    import {mapState} from 'vuex'
    export default {
        data () {
            return {
                tree: {},
            }
        },
        created() {
            this.$http.get(server.api.tree, {
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.access_token
                }
            }).then((response) => {
              console.log(response);
                this.tree=response.data;
            })
        },
        computed: {
            ...mapState([
                'user'
            ]),
            userInfo() {
                return '/user/' + this.user.name
            }
        },
        methods: {
         handleOpen(key, keyPath) {
           console.log(key, keyPath);
         },
         handleClose(key, keyPath) {
           console.log(key, keyPath);
         }
       }
    }
</script>

<style lang="scss" scoped>
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

.logout {
    cursor: pointer;
}
</style>
