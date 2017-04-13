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

                  <li class="dropdown messages">
                      <span v-if="notifications.length > 0" class="badge badge-danager animated bounceIn" id="new-messages">{{ notifications.length }}</span>
                      <button type="button" class="btn btn-default dropdown-toggle options" id="toggle-mail" data-toggle="dropdown">
                          <i class="el-icon-message"></i>
                      </button>
                      <ul class="dropdown-menu alert animated fadeInDown">
                          <li>
                              <h1>You have
                                  <strong>{{ notifications.length }}</strong> new messages</h1>
                          </li>
                          <li v-for="notification in notifications">
                              <router-link :to="'/audit/details/' + notification.data.applicat_id">
                                  <div class="profile-photo">
                                      <img :src="notification.data.avatar" width=50 heigth=50 alt="" class="img-circle">
                                  </div>
                                  <div class="message-info">
                                      <span class="sender">{{ notification.data.name }}</span>
                                      <span class="time">{{ notification.created_at }}</span>
                                      <div class="message-content">{{ notification.data.reason }}</div>
                                  </div>
                              </router-link>
                          </li>

                            <li>
                                <router-link to="/audit">
                                  Check all messages <i class="ion-chevron-right"></i>
                                </router-link>
                          </li>
                      </ul>

                    </li>

                  <span class="nav-lang" v-bind:class="{active: lang!='en'}"
                   @click="switchLang('zh_cn')">中文</span>/
                  <span class="nav-lang" v-bind:class="{active: lang=='en'}"
                   @click="switchLang('en')">En</span>

                <!--头像-->
                  <li class="user-profile-photo">
                      <img :src="user.avatar ? user.avatar : 'http://lncoa.app/images/default.png'" alt="" class="img-responsive img-circle"/>
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
                            <router-link to='/user/profile'>
                              <i class="ion-person"></i> Profile
                            </router-link>
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
    import { mapActions, mapState, mapMutations} from 'vuex';

    export default {
        data () {
            return {
                status: true,
                lang: {},
                notifications: []
            }
        },
        created() {
            axios.get('/api/notificat').then(response => {
                this.notifications = response.data;
                if(response.data.length)
                this.SET_NOTIFICAT(response.data);
            })
        },
        methods:{
            ...mapActions([
              'toggle'
            ]),
            ...mapMutations([
                'OUT_LOGIN',
                'SET_NOTIFICAT'
            ]),
            logout: function(){
                this.OUT_LOGIN();
                this.$router.push('/login');
            },
            switchLang: function(lang){
                Vue.config.lang = lang;
                this.lang = lang;
                localStorage.Language = lang;
            },
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

// settings
.user-nav ul li.dropdown.settings .dropdown-menu{
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
    // padding: 0 8px;
    border-radius: 5px;
}

.user-nav .dropdown .badge {
    background-color: #e84c3d;
    color: #fff;
}

// message
.user-nav .messages .badge {
    position: absolute;
    top: -10px;
    left: -5px;
}

.user-nav ul li.dropdown.messages .dropdown-menu {
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    -ms-border-radius: 3px;
    border-radius: 3px;
    left: -150px;
    top: 48px;
    background-color: #F6F6F6;
    border: none;
    -webkit-box-shadow: 0 0 5px rgba(0,0,0,.1);
    -moz-box-shadow: 0 0 5px rgba(0,0,0,.1);
    box-shadow: 0 0 5px rgba(0,0,0,.1);
    padding: 0;
    width: 350px;
}

.user-nav ul li.dropdown.messages .dropdown-menu:before {
    bottom: 100%;
    left: 48%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
    border-color: rgba(238,238,238,0);
    border-bottom-color: #e84c3d;
    border-width: 8px;
    margin-left: -8px;
}

.dropdown.messages .dropdown-menu>li, .dropdown.settings .dropdown-menu>li {
    display: block;
}

li.dropdown ul.dropdown-menu.alert>li h1 {
    -webkit-border-radius: 3px 3px 0 0;
    -moz-border-radius: 3px 3px 0 0;
    -ms-border-radius: 3px 3px 0 0;
    border-radius: 3px 3px 0 0;
    background-color: #e84c3d;
    margin: 0;
    font-size: 13px;
    padding: 10px;
    font-weight: 400;
    color: #fff;
}

//通知消息列
.user-nav ul li.dropdown.messages .dropdown-menu>li>a {
    margin: 10px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    -ms-border-radius: 5px;
    border-radius: 5px;
    background-color: #fcfcfc;
    color: #797979;
}

//头像
.user-nav ul li .profile-photo {
    display: inline-block;
    overflow: hidden;
    vertical-align: middle;
}

.user-nav ul li.dropdown.messages .dropdown-menu>li>a .message-info {
    vertical-align: top;
    display: inline-block;
    font-size: 11px;
    margin-left: 5px;
    width: 225px;
}

//发送者
.user-nav ul li.dropdown.messages .dropdown-menu>li>a .message-info .sender {
    font-weight: 500;
}

//时间
.user-nav ul li.dropdown.messages .dropdown-menu>li>a .message-info .time {
    font-weight: 300;
    font-size: 9px;
    color: #b2b2b2;
}

//通知内容
.user-nav ul li.dropdown.messages .dropdown-menu>li>a .message-info .message-content {
    white-space: normal;
    margin-top: 5px;
}

.user-nav ul li.dropdown.messages .dropdown-menu>li>a:hover, .user-nav ul li.dropdown.messages .dropdown-menu>li>a:hover .message-info .time {
    background: #1abc9c;
    color: #fff;
}

.user-nav ul li.dropdown.messages .dropdown-menu>li:last-child a {
    background-color: #fff;
    font-weight: 400;
    font-size: 12px;
    margin: 0;
    -webkit-border-radius: 0 0 3px 3px;
    -moz-border-radius: 0 0 3px 3px;
    -ms-border-radius: 0 0 3px 3px;
    border-radius: 0 0 3px 3px;
    text-align: right;
    padding: 10px 15px;
}

.user-nav ul li.dropdown.messages .dropdown-menu>li:last-child a:hover {
    background: #1abc9c;
    color: #fff;
}

.user-nav .dropdown .btn-default {
  padding: 3px 9px;
  background: #F6F6F6;
  border-radius: 50%;
  -moz-border-radius: 50%;
  -webkit-border-radius: 50%;
  width: 35px;
  height: 35px;
  border-color: #f6f6f6;
}

.user-nav #toggle-mail:hover {
    background: #1abc9c;
}

.user-nav .dropdown .btn-default:hover{
    color: #fff;
    background-color: #cacfd2;
    border-color: #cacfd2;
}
.user-nav .dropdown .btn-default:focus{
    color: #fff;
    background-color: #cacfd2;
    border-color: #cacfd2;
}

.user-nav .messages {
  margin: 0 15px;
}

.user-nav .profile-photo {
    margin: 0 5px;
}

.user-profile-photo{
    display:inline-block;
    overflow:hidden;
    vertical-align:middle;
    width: 40px;
    height: 40px;
}
.message-info {
    max-height: 60px;
    overflow: hidden;
}
.user-nav ul li.dropdown.messages .dropdown-menu>li>a .message-info .message-content {
    overflow: hidden;
    // white-space: nowrap;
    text-overflow: ellipsis;
    max-height: 40px;
}

.navbar-toggle {
    margin: 0;
    font-size: 20px;
    text-decoration: none;
    padding: 13px 20px 0;
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
