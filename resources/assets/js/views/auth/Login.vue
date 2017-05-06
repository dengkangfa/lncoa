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
                      <form class="form-horizontal" @submit.prevent="onLogin" method="post" role="form">
                          <div class="form-group email has-feedback"
                            :class="{'has-error' : state == 'error'}">
                              <div class="col-md-12 ">
                                  <input type="text" id="username" class="form-control" :placeholder="$t('el.form.username_placeholder')" v-model="username" required autofocus>
                                  <i class="ion-android-person"></i>
                                  <span v-show="state == 'error'" class="help-block">
                                      <strong >{{ message }}</strong>
                                  </span>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-md-12">
                                  <input type="password" class="form-control" id="password" :placeholder="$t('el.form.password')" v-model="password" required>
                                  <i class="ion-locked"></i>
                                  <span v-show="PasswordError" class="help-block">
                                      <strong v-for="errorItem in PasswordError">{{errorItem}}</strong>
                                  </span>
                                  <router-link to='/password_reset'>
                                      {{$t('el.form.forgot_your_password')}}
                                  </router-link>
                                  <!-- <a href="javascript:void(0)" class="help-block"></a> -->
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="col-md-12">
                                  <!-- <a href="../reviewingSystem2/Application/Home/View/index/index.html" class="btn btn-primary btn-block">Sign in</a> -->
                                  <button type="submit" class="btn btn-primary btn-block" name="button">{{$t('el.form.sign_in')}}</button>
                                  <hr />
                                  <!-- <a href="pages-sign-up.html" class="btn btn-default btn-block"></a> -->
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
  import server from '../../config/api'
  import { mapMutations } from 'vuex'

  export default {
      name : 'login',
      data(){
          return {
              username : null,
              password : null,
              emailError: null,
              PasswordError: null,
              state: '',
              message: ''
          }
      },
      methods:{
          ...mapMutations([
              'SET_ACCESS_TOKEN',
              'LOGIN',
              'SET_USER'
          ]),
          onLogin:function(){
              var vm = this;
              let data = {};
              //如果存在refresh_token则重新刷新令牌，反之重新获取令牌
              let refresh_token = localStorage.getItem(vm.username + '_refresh_token');
              if(refresh_token) {
                  data = {
                        'grant_type': 'refresh_token',
                        'username' : vm.username,
                        'password' : vm.password,
                        'client_id': server.client.client_id,
                        'client_secret': server.client.client_secret,
                        'refresh_token': refresh_token
                    }
              }else{
                  data = {
                      'grant_type': 'password',
                      'username' : vm.username,
                      'password' : vm.password,
                      'client_id': server.client.client_id,
                      'client_secret': server.client.client_secret,
                  }
              }
              axios.post(server.api.login, data).then( response => {
                  vm.message = '';
                  vm.state = 'success';
                  //将用于刷新的令牌存储进localstorage
                  localStorage.setItem(vm.username + '_refresh_token', response.data.refresh_token)
                  // localStorage.refresh_token = response.data.refresh_token;
                  //将用于验证身份的令牌存储进vuex
                  vm.SET_ACCESS_TOKEN(response.data.access_token);
                  localStorage.access_token = vm.$store.state.access_token;
                  vm.LOGIN();
                  axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.access_token;
                  axios.get(server.api.user + '?include=roles', {
                      headers: {
                          'Authorization': 'Bearer ' + vm.$store.state.access_token
                      }
                  }).then( response => {
                      vm.SET_USER(response.data.data);
                      // this.$router.go(-1);
                      vm.$router.push('/');
                  }, error => {
                      console.log(error.response);
                  })
              }, (response) => {
                  vm.message = response.response.data.message;
                  vm.state = response.response.data.status;
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

.email .help-block{
    margin-bottom: -10px;
}

@media (max-width: 768px){
  #login-wrapper {
      width: 90%;
      margin-top: 80px;
  }
}

</style>
