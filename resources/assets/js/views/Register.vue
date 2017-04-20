<template lang="html">
  <section id="login-container">
        <div class="row">
            <div class="col-md-3" id="login-wrapper">
                <div class="panel panel-primary animated flipInY">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Sign Up
                        </h3>
                    </div>
                    <div class="panel-body">
                        <p>Already a member?
                            <!-- <a href="pages-login.html">

                            </a> -->
                            <router-link to="/login" exact>
                              <strong>Sign In</strong>
                            </router-link>
                        </p>
                        <form role="form" @submit.prevent="onsumber" onsubmit="return false;">
                            <div class="form-group has-feedback"
                             :class="{'has-error' : formDanger.name.hasError,
                             'has-success' : formDanger.name.hasSuccess}"
                               id="nameGroup">
                                <label for="exampleInputName">Name</label>
                                <input type="text"
                                 v-model="form.name"
                                 class="form-control"
                                 placeholder="Enter your name"
                                 v-focus
                                 @keyup.enter.prevent=""
                                 required>
                                <span class="glyphicon form-control-feedback"
                                 :class="{'glyphicon-warning-sign':formDanger.name.hasError,
                                   'glyphicon-ok ' : formDanger.name.hasSuccess}"
                                  id="nameIcon"></span>
                                <span class="help-block" id="nameMsg">{{ formDanger.name.message }}</span>
                            </div>
                            <div class="form-group has-feedback"
                             :class="{'has-error' : formDanger.email.hasError,
                             'has-success' : formDanger.email.hasSuccess}">
                                <label for="exampleInputEmail">Email</label>
                                <input type="email"
                                 v-model="form.email"
                                 class="form-control"
                                 placeholder="Enter your email"
                                 required
                                >
                                <span class="glyphicon form-control-feedback"
                                 :class="{'glyphicon-warning-sign':formDanger.email.hasError,
                                   'glyphicon-ok ' : formDanger.email.hasSuccess}"></span>
                                <span class="help-block" v-html="formDanger.email.message"></span>
                            </div>
                            <div class="form-group has-feedback"
                             :class="{'has-error' : formDanger.password.hasError,
                             'has-success' : formDanger.password.hasSuccess}">
                                <label for="exampleInputPassword">Password</label>
                                <input type="password"
                                 v-model="form.password"
                                 class="form-control"
                                 placeholder="Password"
                                 required>
                                <el-slider v-model="regex" v-if="regex" :max="3" :show-tooltip="false" :disabled="true"></el-slider>
                                <span class="glyphicon form-control-feedback"
                                 :class="{'glyphicon-warning-sign':formDanger.password.hasError,
                                   'glyphicon-ok ' : formDanger.password.hasSuccess}"></span>
                                <span class="help-block" v-html="formDanger.password.message"></span>
                            </div>
                            <div class="form-group has-feedback"
                             :class="{'has-error' : formDanger.password_confirmation.hasError,
                             'has-success' : formDanger.password_confirmation.hasSuccess}">
                                <label for="exampleInputRetypePassword">Retype Password</label>
                                <input type="password"
                                 v-model="form.password_confirmation"
                                  class="form-control"
                                   id="exampleInputRetypePassword"
                                    placeholder="Retype your password"
                                     required>
                                <span class="glyphicon form-control-feedback"
                                 :class="{'glyphicon-warning-sign':formDanger.password_confirmation.hasError,
                                   'glyphicon-ok ' : formDanger.password_confirmation.hasSuccess}"></span>
                                <span class="help-block" v-html="formDanger.password_confirmation.message"></span>
                            </div>
                            <input type="hidden">
                            <button class="btn btn-primary btn-block" :class="{disabled: status}" type="submit" id="submit">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import server from '../config/api'
import { mapMutations } from 'vuex'
export default {
  data() {
      return {
          regex: 0,
          status: true,
          form: {
              name: '',
              email: '',
              password: '',
              password_confirmation: ''
          },
          formDanger: {
              name: {
                message: '',
                hasError: false,
                hasSuccess: false,
               },
              email: {
                message: '',
                hasError: false,
                hasSuccess: false,
              },
              password: {
                message: '',
                hasError: false,
                hasSuccess: false,
              },
              password_confirmation: {
                message: '',
                hasError: false,
                hasSuccess: false,
              }
          }
      }
  },
  watch: {
      'form.name': function (val) {
          if(this.nameCheck(val)){
              this.uniqueCheck('api/register/name/check?name=' + val,this.formDanger.name);
          }
          this.check();
      },
      'form.email': function (val) {
          if(this.emailCheck(val)){
              this.uniqueCheck('api/register/email/check?email=' + val,this.formDanger.email);
          }
          this.check();
      },
      'form.password': function (val) {
          this.passwordCheck();
          this.check();
      },
      'form.password_confirmation': function (val) {
          this.retypePasswordCheck();
          this.check();
      }
  },
  directives: {
      focus: {
          inserted: function (el) {
              el.focus();
          },
      }
  },
  methods: {
      ...mapMutations([
          'SET_ACCESS_TOKEN',
          'LOGIN',
          'SET_USER'
      ]),
      onsumber() {
          axios.post('api/register',this.form).then( response => {
              console.log(response);
              let vm = this;
              toastr.success('等待管理员激活账号！')
              // axios.post(server.api.login, {
              //     'grant_type': 'password',
              //     'email' : vm.form.email,
              //     'password' : vm.form.password,
              //     'client_id': server.client.client_id,
              //     'client_secret': server.client.client_secret
              // }).then( response => {
              //     vm.SET_ACCESS_TOKEN(response.data.access_token);
              //     vm.LOGIN();
              //     localStorage.access_token = vm.$store.state.access_token;
              //     axios.get(server.api.user + '?include=roles', {
              //         headers: {
              //             'Authorization': 'Bearer ' + vm.$store.state.access_token
              //         }
              //     }).then( response => {
              //         vm.SET_USER(response.data.data);
              //         this.$router.go('/');
              //     })
              // }, (response) => {
              //   console.log(response.data);
              // })
          }, error => {
              console.log(error.response);
          })
      },
      uniqueCheck(uri,obj) {
        //验证唯一性
        axios.get(uri).then( response => {
            if( !response.data.success ) {
              let errors = [],str = '';
              for (var i in response.data.errors) {
                  errors = response.data.errors[i]
              }
              for (let i = 0; i < errors.length; i++) {
                  if(i == 0){
                      str +=  errors[i];
                      continue;
                  }
                  str += "<br/>" + errors[i];
              }
                obj.message = str;
                obj.hasError = !response.data.success;
                obj.hasSuccess = false;
            }else{
                obj.message = '';
                obj.hasError = !response.data.success;
                obj.hasSuccess = true;
            }
          }, error => {
              toastr.error(error.response.status + ' : ' + error.response.statusText)
          })
      },
      nameCheck() {
        let name = this.form.name;//获取用户输入的用户名值
        let checkName = /^[\u4E00-\u9FA5A-Za-z0-9]{2,16}$/;//用户名正则表达式[中文、字母、数字]3位到9位之间
        if (checkName.test(name)) {
            return true;
        } else {
            this.formDanger.name.message = "您输入的用户名格式不正确";
            this.formDanger.name.hasError = true;
            return false;
        }
      },
      emailCheck() {
        let email = this.form.email;//获取用户输入的用户名值
        let checkEmail= /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;//邮箱正则表达式
        if (checkEmail.test(email)) {
            return true;
        } else {
            this.formDanger.email.message = "您输入的Email格式不正确";
            this.formDanger.email.hasError = true;
            return false;
        }
      },
      passwordCheck() {
          let password = this.form.password;
          this.retypePasswordCheck();
          let strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
          let mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
          let enoughRegex = new RegExp("(?=.{6,}).*", "g");
          if (false == enoughRegex.test(password)) {
              this.formDanger.password.message = '您的密码强度太低了';
              this.formDanger.password.hasError = true;
              this.formDanger.password.hasSuccess = false;
              this.regex = 0;
          } else if (strongRegex.test(password)) {
              this.formDanger.password.message = '';
              this.formDanger.password.hasError = false;
              this.formDanger.password.hasSuccess = true;
              this.regex = 3;
              $(".el-slider__bar").addClass('slider-strong-regex')
              $(".el-slider__bar").removeClass('slider-medium-regex')
              $(".el-slider__bar").removeClass('slider-general-regex')
          } else if (mediumRegex.test(password)) {
              this.formDanger.password.message = '';
              this.formDanger.password.hasError = false;
              this.formDanger.password.hasSuccess = true;
              this.regex = 2;
              $(".el-slider__bar").addClass('slider-medium-regex')
              $(".el-slider__bar").removeClass('slider-general-regex')
              $(".el-slider__bar").removeClass('slider-strong-regex')
          } else {
              this.formDanger.password.message = '';
              this.formDanger.password.hasError = false;
              this.formDanger.password.hasSuccess = true;
              this.regex = 1;
              $(".el-slider__bar").addClass('slider-general-regex')
              $(".el-slider__bar").removeClass('slider-medium-regex')
              $(".el-slider__bar").removeClass('slider-strong-regex')
          }
      },
      retypePasswordCheck() {
          if(this.form.password != '' &&
              this.form.password_confirmation != '' &&
              this.form.password_confirmation != this.form.password
          ) {
            this.formDanger.password_confirmation.message = '两次密码输入不一致';
            this.formDanger.password_confirmation.hasError = true;
            this.formDanger.password_confirmation.hasSuccess = false;
            return ;
          }

          this.formDanger.password_confirmation.message = '';
          this.formDanger.password_confirmation.hasError = false;
          if(this.form.password_confirmation != ''){
            this.formDanger.password_confirmation.hasSuccess = true;
          }
      },
      check(){
        //当所有条件满足时，使注册按钮成可点击状态
        //当用户名，邮箱，密码，重复密码输入框中的数值都符合要求时
        if(this.formDanger.name.hasSuccess&&
            this.formDanger.email.hasSuccess&&
              this.formDanger.password.hasSuccess&&
                this.formDanger.password_confirmation.hasSuccess){
                  this.status = false;
                  return
                }
        this.status = true;
    }
  }
}
</script>

<style lang="css">
  .el-slider__bar{
    background-color: #f73500 !important;
  }
  .slider-general-regex {
    background-color: #f73500 !important;
  }
  .slider-medium-regex {
    background-color: #ff810f !important;
  }
  .slider-strong-regex {
    background-color: #6ba100 !important;
  }
</style>
