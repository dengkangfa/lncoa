<template>
    <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:;" class="navbar-toggle collapsed pull-left" @click="toggle">
                    <i class="ion-navicon-round"></i>
                </a>
            </div>
            <div class="user-nav">
                <ul v-if="!statused">

                  <span class="nav-lang" v-bind:class="{active: lang!='en'}"
                   @click="switchLang('zh_cn')">中文</span>/
                  <span class="nav-lang" v-bind:class="{active: lang=='en'}"
                   @click="switchLang('en')">En</span>
                <!--头像-->
                  <li class="profile-photo">
                      <img :src="user.avatar" alt="" class="img-responsive img-circle"/>
                  </li>
                  <li class="dropdown settings">
                      <!--文本标签下拉框-->
                      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        {{ user.name }}
                      </a>
                      <!--用户快捷选项-->
                      <ul class="dropdown-menu animated fadeInDown">
                          <!--头像选项-->
                          <li>
                              <a href="#"><i class="ion-person"></i> Profile</a>
                          </li>
                          <!--日历选项-->
                          <li>
                              <a href="#"><i class="ion-ios-calendar"></i> Calendar</a>
                          </li>
                          <!--邮件消息选项-->
                          <li>
                              <a href="#"><i class="ion-email"></i> Inbox <span class="badge badge-danager" id="user-inbox">5</span></a>
                          </li>
                          <!--登退选项-->
                          <li>
                              <a href="javascript:;" @click="logout"><i class="ion-power"></i> Logout</a>
                          </li>
                      </ul>
                  </li>
              </ul>
            </div>

            <div id="repbar" class="navbar-collapse collapse">

                <ul class="nav navbar-nav">
                    <li style="font-size: 20px;" @click="toggle">
                        <a id="left-hamburger"  href="javascript:;">
                            <i class="ion-navicon-round"></i>
                        </a>
                    </li>
                </ul>



            </div>
            <!--/.nav-collapse -->


        </div>
    <!--/.container-fluid -->
    </nav>
</template>

<script>
    import { mapActions, mapState } from 'vuex';

    export default {
        data () {
            return {
                status: true,
                lang: {},
            }
        },
        methods:{
            toggle: mapActions([
              'toggle'
            ]).toggle,
            logout: function(){
                this.$http.post('/logout').then(response =>{
                    console.log(response);
                    if(response.status == 200){
                        window.location.href="/login";
                    }
                },response => {
                    console.log(response.data);
                });
            },
            switchLang: function(lang){
                Vue.config.lang = lang;
                this.lang = lang;
            }
        },
        mounted() {
            this.user = this.$store.state.user;
            this.lang = window.Language;
        },
        computed: {
          ...mapState([
            'user'
          ]),
          statused: function() {
            console.log(this.$store.state.sidebar.opened);
            return this.status = this.$store.state.sidebar.opened
          },
        }
    }
</script>

<style lang="scss" scoped>
#repbar {
  margin-left: -15px;
}

a:hover, a:focus {
    text-decoration: none;
}

.navbar-nav {
  li {
    a {
      padding-top: 0px;
      padding-bottom: 0px;
      line-height: 60px;
    }
  }
}

.user-nav {
    float:right;
    margin-top: 8px;
    padding-right:20px;
}

.user-nav ul li{
    display:inline-block;
    vertical-align:middle;
    font-size:15px;
}

.user-nav .nav-lang {
    cursor: pointer;
    font-size: 1.1em;
    opacity: 0.8;
}

.user-nav .nav-lang.active{
    opacity: 1;
    font-weight: bold;
}


.user-nav.dropdown-toggle a:hover{
    color: #16a086;
}

.user-nav ul li .dropdown-menu{
    width: 125px;
    left: -90px;
    top: 40px;
    position: absolute;
}

.user-nav ul li .dropdown-menu:before{
    bottom: 100%;
    left: 78%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
    border-color: rgba(82, 105, 128, 0);
    border-bottom-color: #52697f;
    border-width: 8px;
    margin-left: -8px;
    top: -17px;
}

.user-nav .dropdown-menu li{
    width: 100%;
    padding: 0 8px;
    border-radius: 5px;
}

.profile-photo{
    display:inline-block;
    overflow:hidden;
    vertical-align:middle;
    width: 40px;
    height: 40px;
}

.navbar-toggle {
    margin: 0;
    font-size: 20px;
    text-decoration: none;
    padding: 13px 10px;
    border: 0;
    color: #777;
    cursor: pointer;
}
.navbar-inverse .navbar-toggle:hover {
    border: 0;
    border-radius: 0;
    background-color: #fff !important;
    text-decoration: none;
    color: #888;
}
.navbar-header {
    display: inline-block;
}
</style>
