<template lang="html">
  <section id="login-container">
      <div class="row">
          <div class="col-md-3" id="login-wrapper">
              <div class="panel panel-primary animated flipInY">
                  <div class="panel-heading">
                      <h3 class="panel-title">
                          {{$t('el.form.sign_in')}}
                      </h3>
                  </div>
                  <div class="panel-body">
                      <p>{{$t('el.form.login_placeholder')}}</p>
                      <form class="form-horizontal" @submit.prevent="validateBeforeSubmit" method="post" role="form">
                          <div class="form-group email has-feedback"
                            :class="{'has-error' : state == 'error' || usernameFlags.invalid}">
                              <div class="col-md-12 ">
                                  <input name="username" type="text" id="username" v-validate="{  rules: {required: true, regex: /^[\u4e00-\u9fa5_a-zA-Z0-9-]{2,16}|[a-z]([a-z0-9]*[-_]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i} }" class="form-control" :placeholder="$t('el.form.username_placeholder')" v-model="username" required autofocus>
                                  <i class="ion-android-person"></i>
                                  <span v-show="state == 'error'" class="help-block">
                                      <strong >{{ message }}</strong>
                                  </span>
                                  <!-- 错误消息 -->
                                  <span v-show="errors.has('username')" class="help-block">
                                    <strong>{{ errors.first('username') }}</strong>
                                  </span>
                                  <!-- 错误消息END -->
                              </div>
                          </div>
                          <div class="form-group has-feedback" :class="{'has-error': passwordFlags.invalid}" style="margin-bottom: 0px;">
                              <div class="col-md-12">
                                  <input name="password" type="password" class="form-control" id="password" v-validate="'required|min:5|max:16'" :placeholder="$t('el.form.password')" v-model="password" required>
                                  <i class="ion-locked"></i>
                                  <span v-show="PasswordError" class="help-block">
                                      <strong v-for="errorItem in PasswordError">{{errorItem}}</strong>
                                  </span>
                                  <!-- 错误消息 -->
                                  <span v-show="errors.has('password')" class="help-block">
                                    <strong>{{ errors.first('password') }}</strong>
                                  </span>
                                  <!-- 错误消息END -->
                                  <router-link to='/password_reset'>
                                      {{$t('el.form.forgot_your_password')}}
                                  </router-link>
                              </div>
                          </div>
                          <div class="checkbox row" style="padding-bottom: 5px;">
                            <label>
                              <input type="checkbox" v-model="remember"> 记住我
                            </label>
                            <p class="remember" v-show="remember">不是自己的电脑上不要勾选此项</p>
                          </div>
                            <Geetest @validate="validate"></Geetest>
                          <div class="form-group">
                              <div class="col-md-12">
                                  <button type="submit"
                                   v-loading.fullscreen.lock="fullscreenLoading"
                                   :disabled="!formDirty || validateGeetest"
                                   class="btn btn-primary btn-block"
                                   name="button">
                                     {{$t('el.form.sign_in')}}
                                   </button>
                                  <hr />
                                  <router-link to="/register" class="btn btn-default btn-block" exact>{{$t('el.form.not_a_member')}}</router-link>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>

  </section>
</template>

<script>
  import Geetest from '../../components/Geetest'
  import server from '../../config/api'
  import { mapMutations } from 'vuex'
  import { mapFields } from 'vee-validate';

  export default {
      name : 'login',
      data(){
          return {
              username : null,
              password : null,
              emailError: null,
              PasswordError: null,
              state: '',
              message: '',
              remember: false,
              geetest: {},
              fullscreenLoading: false
          }
      },
      components: {
          Geetest
      },
      computed: {
        ...mapFields({
            usernameFlags: 'username',
            passwordFlags: 'password',
        }),
        //表单是否有更改
        formDirty() {
            // are some fields dirty?
            return Object.keys(this.validataFields).some(key => this.validataFields[key].dirty);
        },
        validateGeetest() {
            return $.isEmptyObject(this.geetest);
        }
      },
      methods:{
          ...mapMutations([
              'SET_ACCESS_TOKEN',
              'LOGIN',
              'SET_USER'
          ]),
          validate(val) {
              this.geetest = val;
          },
          //提交之前验证所有表单信息
          validateBeforeSubmit(event) {
              let vm = this;
              vm.$validator.validateAll().then(() => {
                  //验证是否正确完成验证码操作
                  if(!vm.validateGeetest) {
                      vm.onLogin(event);
                  }else {
                      toastr.warning(this.$t('el.notification.code_warning'));
                  }
              }).catch(() => {
                toastr.error(vm.$t('el.notification.submit_data_error'))
              });
          },
          onLogin(){
              var vm = this;
              vm.$loading()
              let data = {};
              if(!this.geetest){
                  return;
              }
              //如果存在refresh_token则重新刷新令牌，反之重新获取令牌
              let refresh_token = localStorage.getItem(vm.username + '_refresh_token');
              //如果存在refresh_token，则刷新令牌，反之请求令牌
              if(refresh_token) {
                  //刷新令牌参数
                  data = {
                        'grant_type': 'refresh_token',
                        'username' : vm.username,
                        'password' : vm.password,
                        'client_id': server.client.client_id,
                        'client_secret': server.client.client_secret,
                        'refresh_token': refresh_token
                    }
              }else{
                  //请求令牌参数
                  data = {
                      'grant_type': 'password',
                      'username' : vm.username,
                      'password' : vm.password,
                      'client_id': server.client.client_id,
                      'client_secret': server.client.client_secret,
                  }
              }
              Object.assign(data,this.geetest);
              //请求登录
              axios.post(server.api.login, data).then( response => {
                  vm.message = '';
                  vm.state = 'success';
                  //判断是否需要记住该账号，需要则将令牌存储在localStorage中，反之只
                  //存储在sessionStorage中
                  if(vm.remember) {
                    //将用于刷新的令牌存储进localstorage
                    localStorage.setItem(vm.username + '_refresh_token', response.data.refresh_token)
                    localStorage.setItem('access_token', response.data.access_token)
                  }else{
                    sessionStorage.setItem('access_token', response.data.access_token)
                  }
                  //将用于验证身份的令牌存储进vuex
                  vm.SET_ACCESS_TOKEN(response.data.access_token);
                  // vm.LOGIN();
                  axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.access_token;
                  vm.SET_USER(response.data);
                  // vm.$router.go(-1);
                  vm.$loading().close();
                  vm.$router.push('/');
              }, error => {
                  vm.$loading().close();
                  if(error.response.status == 403){
                    vm.message = error.response.data.message;
                    vm.state = error.response.data.status;
                  }else{
                    toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                  }
              })
          }
      }
  }
</script>

<style lang="css">
#login-container{
  width:100%;
  height:100%;
  position:absolute;
  -webkit-transition:all .3s ease-in-out;
  -moz-transition:all .3s ease-in-out;
  // -o-transition:all .3s ease-in-out;
  transition:all .3s ease-in-out
}

#login-wrapper{
    margin:100px auto;
    float:none;
}

#login-wrapper .form-control{
  color:#717171;
  outline:0;
  height:18px;
  padding:6px 11px;
  line-height:18px;
  font-size:13px;
  background-color:#fafafa;
  min-height:36px;
  filter:none!important;
  -webkit-box-shadow:none!important;
  -moz-box-shadow:none!important;
  -webkit-border-radius:3px;
  -moz-border-radius:3px;
  -ms-border-radius:3px;
  border-radius:3px;
  -webkit-transition:all .2s linear;
  transition:all .2s linear;
}

#login-wrapper .form-control:focus{
    border-color:#7c7c7c;
}

#login-wrapper #username, #login-wrapper #password {
    padding-left: 32px;
}

#login-wrapper .form-group i {
    position: absolute;
    left: 27px;
    top: 5px;
}

#login-wrapper .remember {
  float: right;
  opacity: .6;
  margin-bottom: 0;
}

#login-wrapper .help-block {
    margin-bottom: 0;
}

#login-wrapper .form-horizontal .checkbox {
    padding-top: 0;
}

#geetest-captcha .geetest_holder.geetest_wind {
    min-width: 220px;
}

#login-wrapper .form-group.has-feedback.has-error {
    margin-bottom: 0;
}

@media (max-width: 768px){
  #login-wrapper {
      width: 90%;
      margin-top: 80px;
  }
}

</style>
