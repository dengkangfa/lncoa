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
                        <form role="form">
                            <div class="form-group has-feedback"
                             :class="{'has-error' : formDanger.name.hasError,
                             'has-success' : formDanger.name.hasSuccess}"
                               id="nameGroup">
                                <label for="exampleInputName">Name</label>
                                <input type="text" v-model="form.name" class="form-control" id="exampleInputName" placeholder="Enter your name" required>
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
                                <input type="email" v-model="form.email" class="form-control" placeholder="Enter your email" required>
                                <span class="glyphicon form-control-feedback"
                                 :class="{'glyphicon-warning-sign':formDanger.email.hasError,
                                   'glyphicon-ok ' : formDanger.email.hasSuccess}"></span>
                                <span class="help-block" v-html="formDanger.email.message"></span>
                            </div>
                            <div class="form-group has-feedback"
                             :class="{'has-error' : formDanger.password.hasError,
                             'has-success' : formDanger.password.hasSuccess}">
                                <label for="exampleInputPassword">Password</label>
                                <input type="password" v-model="form.password" class="form-control" placeholder="Password" required>
                                <el-slider v-model="regex" v-if="regex" :max="3" :show-tooltip="false" :disabled="true"></el-slider>
                                <span class="glyphicon form-control-feedback"
                                 :class="{'glyphicon-warning-sign':formDanger.password.hasError,
                                   'glyphicon-ok ' : formDanger.password.hasSuccess}"></span>
                                <span class="help-block" v-html="formDanger.password.message"></span>
                            </div>
                            <div class="form-group has-feedback" id="retypePasswordGroup">
                                <label for="exampleInputRetypePassword">Retype Password</label>
                                <input type="password" v-model="form.password_confirmation" class="form-control" id="exampleInputRetypePassword" placeholder="Retype your password" required>
                                <span class="glyphicon form-control-feedback" id="retypePasswordIcon"></span>
                                <span class="help-block" id="retypePasswordMsg">{{ formDanger.password_confirmation.message }}</span>
                            </div>
                            <button class="btn btn-primary btn-block disabled" type="button" id="submit">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
  data() {
      return {
          regex: 0,
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
              password_confirmation: {}
          }
      }
  },
  watch: {
      'form.name': function (val) {
          if(this.nameCheck(val)){
            axios.get('api/user/name/check?name=' + val).then( response => {
              console.log(response);
                if( !response.data.success ) {
                    this.formDanger.name.message = response.data.errors.name;
                    this.formDanger.name.hasError = !response.data.success;
                    this.formDanger.name.hasSuccess = false;
                }else{
                    this.formDanger.name.message = '';
                    this.formDanger.name.hasError = !response.data.success;
                    this.formDanger.name.hasSuccess = true;
                }
            }, error => {
                console.log(error);
            })
          }
      },
      'form.email': function (val) {
          if(this.emailCheck(val)){
            axios.get('api/user/email/check?email=' + val).then( response => {
              console.log(response);
                if( !response.data.success ) {
                  let errors = response.data.errors.email,str = '';
                  for (let i = 0; i < errors.length; i++) {
                      if(i == 0){
                          str +=  errors[i];
                          continue;
                      }
                      str += "<br/>" + errors[i];
                  }
                    this.formDanger.email.message = str;
                    this.formDanger.email.hasError = !response.data.success;
                    this.formDanger.email.hasSuccess = false;
                }else{
                    this.formDanger.email.message = '';
                    this.formDanger.email.hasError = !response.data.success;
                    this.formDanger.email.hasSuccess = true;
                }
            }, error => {
                console.log(error);
            })
          }
      },
      'form.password': function (val) {
          this.passwordCheck();
      }
  },
  methods: {
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
