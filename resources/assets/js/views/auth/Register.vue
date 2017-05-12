<template lang="html">
  <section id="login-container">
        <div class="row">
            <div class="col-md-3" id="login-wrapper">
                <div class="panel panel-primary animated flipInY">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            {{$t('el.form.sign_up')}}
                        </h3>
                    </div>
                    <div class="panel-body">
                        <p>{{$t('el.form.already_a_member')}}
                            <!-- <a href="pages-login.html">

                            </a> -->
                            <router-link to="/login" exact>
                              <strong>{{$t('el.form.sign_in')}}</strong>
                            </router-link>
                        </p>
                        <form role="form" @submit.prevent="validateBeforeSubmit">
                            <div class="form-group has-feedback" :class="{'has-error': nameFlags.invalid, 'has-success': nameFlags.valid}">
                                <label for="exampleInputName">{{$t('el.form.name')}}</label>
                                <input
                                 name="name"
                                 v-validate="{  rules: {required: true, regex: /^[\u4e00-\u9fa5_a-zA-Z0-9-]{2,16}$/, name_unique:true} }" data-vv-delay="500"type="text"
                                 v-model="form.name"
                                 class="form-control"
                                 :placeholder="$t('el.form.name_placeholder')"
                                 @keyup.enter.prevent=""
                                 required>
                                 <!-- 图标 -->
                                 <span v-show="nameFlags.dirty || nameFlags.invalid" class="glyphicon form-control-feedback"
                                   :class="{'glyphicon-warning-sign': nameFlags.invalid, 'glyphicon-ok': nameFlags.valid}">
                                 </span>
                                 <!-- 图标END -->
                                 <!-- 错误消息 -->
                                 <span v-show="errors.has('name')" class="help-block">
                                   <strong>{{ errors.first('name') }}</strong>
                                 </span>
                                 <!-- 错误消息END -->
                            </div>
                            <div class="form-group has-feedback" :class="{'has-error': emailFlags.invalid, 'has-success': emailFlags.valid}">
                                <label for="exampleInputEmail">{{$t('el.form.email')}}</label>
                                <input
                                 name="email"
                                 v-validate="'required|email|email_unique'"
                                 type="email"
                                 v-model="form.email"
                                 class="form-control"
                                 :placeholder="$t('el.form.email_placeholder')"
                                 required
                                >
                                <!-- 图标 -->
                                <span v-show="emailFlags.dirty || emailFlags.invalid" class="glyphicon form-control-feedback"
                                  :class="{'glyphicon-warning-sign': emailFlags.invalid, 'glyphicon-ok': emailFlags.valid}">
                                </span>
                                <!-- 图标END -->
                                <!-- 错误消息 -->
                                <span v-show="errors.has('email')" class="help-block">
                                  <strong>{{ errors.first('email') }}</strong>
                                </span>
                                <!-- 错误消息END -->
                            </div>
                            <div class="form-group has-feedback" :class="{'has-error': passwordFlags.invalid, 'has-success': passwordFlags.valid}">
                                <label for="exampleInputPassword">{{$t('el.form.password')}}</label>
                                <input
                                 name="password"
                                 type="password"
                                 v-model="form.password"
                                 v-validate="'required|min:6|max:16|strength'"
                                 class="form-control"
                                 :placeholder="$t('el.form.password')"
                                 required>
                                <el-slider v-model="regex" v-if="regex && passwordFlags.valid" :max="3" :show-tooltip="false" :disabled="true"></el-slider>
                                 <!-- 图标 -->
                                 <span v-show="passwordFlags.dirty || passwordFlags.invalid" class="glyphicon form-control-feedback"
                                 :class="{'glyphicon-warning-sign': passwordFlags.invalid, 'glyphicon-ok': passwordFlags.valid}">
                                 </span>
                                 <!-- 图标END -->
                                 <!-- 错误消息 -->
                                 <span v-show="errors.has('password')" class="help-block">
                                   <strong>{{ errors.first('password') }}</strong>
                                 </span>
                                 <!-- 错误消息END -->
                            </div>
                            <div class="form-group has-feedback" :class="{'has-error': passwordConfirmationFlags.invalid, 'has-success': passwordConfirmationFlags.valid}">
                                <label for="password_confirmation">{{ $t('el.form.confirm_password') }}</label>
                                <input  v-validate="'required|confirmed:password'" name="password_confirmation" v-model="form.password_confirmation" type="password" class="form-control" id="password_confirmation" :placeholder="$t('el.form.confirm_password')">
                                <!-- 图标 -->
                                <span v-show="passwordConfirmationFlags.dirty || passwordConfirmationFlags.invalid" class="glyphicon form-control-feedback"
                                  :class="{'glyphicon-warning-sign': passwordConfirmationFlags.invalid, 'glyphicon-ok': passwordConfirmationFlags.valid}">
                                </span>
                                <!-- 图标END -->
                                <!-- 错误消息 -->
                                <span v-show="errors.has('password_confirmation')" class="help-block">
                                  <strong>{{ errors.first('password_confirmation') }}</strong>
                                </span>
                                <!-- 错误消息END -->
                            </div>
                            <Geetest @validate="validate"></Geetest>
                            <button type="submit" class="btn btn-primary btn-block" :disabled= "!formDirty || validateGeetest">{{$t('el.form.sign_up')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import Geetest from '../../components/Geetest'
    import { Validator, mapFields } from 'vee-validate';

    const strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
    const mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
    const enoughRegex = new RegExp("(?=.{6,}).*", "g");

    export default {
      data() {
          return {
              form: {
                  name: '',
                  email: '',
                  password: '',
                  password_confirmation: ''
              },
              geetest: {},
              regex: 0,
          }
      },
      created() {
          if(!this.$validator.rules.email_unique){
              Validator.extend('email_unique', {
                  getMessage: (field) => `该${field}已被使用.`,
                  validate: (value) => new Promise(resolve => {
                      axios.get('/api/register/email/check?email=' + value).then(response => {
                         return resolve({valid: response.data.success});
                      });
                  })
              });
          }
          if(!this.$validator.rules.name_unique){
              Validator.extend('name_unique', {
                  getMessage: (field) => `该${field}已被使用.`,
                  validate: (value) => new Promise(resolve => {
                      axios.get('/api/register/name/check?name=' + value).then(response => {
                         return resolve({valid: response.data.success});
                      });
                  })
              });
          }
          if(!this.$validator.rules.strength){
              Validator.extend('strength', {
                  getMessage: (field) => `您的${field}强度太低了.`,
                  validate: (value) => new Promise(resolve => {
                      if (strongRegex.test(this.form.password)) {
                          this.regex = 3;
                          $(".el-slider__bar").addClass('slider-strong-regex')
                          $(".el-slider__bar").removeClass('slider-medium-regex')
                          $(".el-slider__bar").removeClass('slider-general-regex')
                      } else if (mediumRegex.test(this.form.password)) {
                          this.regex = 2;
                          $(".el-slider__bar").addClass('slider-medium-regex')
                          $(".el-slider__bar").removeClass('slider-general-regex')
                          $(".el-slider__bar").removeClass('slider-strong-regex')
                      } else if (enoughRegex.test(this.form.password)) {
                          this.regex = 1;
                          $(".el-slider__bar").addClass('slider-general-regex')
                          $(".el-slider__bar").removeClass('slider-medium-regex')
                          $(".el-slider__bar").removeClass('slider-strong-regex')
                      }
                      return resolve({valid: true});
                  })
              });
          }
          //验证字段名称
          this.$validator.updateDictionary({
                zh_CN: {
                    attributes: {
                        name: '用户名',
                        email: '邮箱',
                        password: '密码',
                        password_confirmation: '确认密码'
                    }
                }
          });
      },
      computed: {
          ...mapFields({
              nameFlags: 'name',
              emailFlags: 'email',
              passwordFlags: 'password',
              passwordConfirmationFlags: 'password_confirmation',
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
      components: {
          Geetest
      },
      methods: {
        validate(val) {
            this.geetest = val;
        },
        //提交之前验证所有表单信息
        validateBeforeSubmit(event) {
            let vm = this;
            vm.$validator.validateAll().then(() => {
                //验证是否正确完成验证码操作
                if(!vm.validateGeetest) {
                    vm.register(event);
                }else {
                    toastr.warning(this.$t('el.notification.code_warning'));
                }
            }).catch(() => {
                toastr.error(vm.$t('el.notification.submit_data_error'))
            });
          },
          //注册
          register(event) {
            axios.post('/api/register',this.form).then( response => {
                let vm = this;
                if(response.data.status == 'success') {
                    localStorage.setItem(vm.form.name + '_refresh_token', response.data.refresh_token);
                    localStorage.access_token = response.data.access_token;
                    vm.$router.push('/');
                }
            }, error => {
                if(error.response.status == 422){
                  stack_error(error.response.data)
                }else{
                  toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                }
            })
          }
      },
    }
</script>

<style lang="css">

  .el-slider__runway.disabled .el-slider__bar.slider-general-regex {
      background-color: red !important;
  }
  .el-slider__runway.disabled .el-slider__bar.slider-medium-regex {
      background-color: #ffeb3b !important;
  }
  .el-slider__runway.disabled .el-slider__bar.slider-strong-regex {
      background-color: #3bff80 !important;
  }
</style>
