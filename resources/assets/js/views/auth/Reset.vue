<template lang="html">
  <section id="login-container">
      <div class="row">
          <div class="col-md-3" id="login-wrapper">
              <div class="panel panel-primary animated flipInY">
                  <div class="panel-heading">
                      <h3 class="panel-title">
                          重置密码
                      </h3>
                  </div>
                  <div class="panel-body">
                    <form @submit.prevent="validateBeforeSubmit">
                        <div class="form-group has-feedback" :class="{'has-error': emailFlags.invalid, 'has-success': emailFlags.valid}">
                            <label for="email">Email</label>
                            <input name="email" v-model="form.email" v-validate="'required|email'" type="email" class="form-control" required autofocus>
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
                        <!-- 新密码 -->
                        <div class="form-group has-feedback" :class="{'has-error': passwordFlags.invalid, 'has-success': passwordFlags.valid}">
                            <label for="password" class="label-control">{{$t('el.form.password')}}</label>
                            <input  name="password" v-validate="'required|min:6|max:16'" data-vv-delay="500" type="password" class="form-control" v-model="form.password">
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
                        <!-- 新密码END -->
                        <!-- 确认新密码 -->
                        <div class="form-group has-feedback" :class="{'has-error': passwordConfirmationFlags.invalid, 'has-success': passwordConfirmationFlags.valid}">
                            <label for="password_confirmation" class="label-control">{{ $t('el.form.confirm_password') }}</label>
                            <input name="password_confirmation" v-validate="'required|confirmed:password'" data-vv-delay="500" type="password" class="form-control" v-model="form.password_confirmation">
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
                        <Geetest ref="geetest" @validate="validate"></Geetest>
                        <button type="submit" class="btn btn-primary btn-block" :disabled= "!formDirty || validateGeetest">{{$t('el.form.reset')}}</button>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </section>
</template>

<script>
    import Geetest from '../../components/Geetest'
    import { mapFields } from 'vee-validate';
    import { stack_error } from '../../config/helper.js'

    export default {
        data() {
            return {
                form: {
                    token: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                geetest: {},
                regex: 0,
            }
        },
        created() {
          this.form.token = this.$route.params.token;
          //验证字段名称
          this.$validator.updateDictionary({
                zh_CN: {
                    attributes: {
                        email: '邮箱',
                        password: '密码',
                        password_confirmation: '确认密码'
                    }
                }
          });
        },
        computed: {
          ...mapFields({
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
            validateBeforeSubmit() {
                let vm = this;
                vm.$validator.validateAll().then(() => {
                    //验证是否正确完成验证码操作
                    if(!vm.validateGeetest) {
                        vm.submit();
                    }else {
                        toastr.warning(this.$t('el.notification.code_warning'));
                    }
                }).catch(() => {
                  toastr.error(vm.$t('el.notification.submit_data_error'))
                });
            },
            submit() {
                Object.assign(this.form,this.geetest);
                axios.post('/api/password/reset',this.form).then(response => {
                    let data = response.data[0];
                    localStorage.setItem(this.form.email + '_refresh_token', data.refresh_token);
                    localStorage.access_token = data.access_token;
                    this.$router.push('/');
                },error => {
                    vm.$refs.geetest.reset();
                    if(error.response.status == 422){
                      //表单验证有错误执行
                      if(error.response.data.error){
                        stack_error(error.response.data.error.message)
                      }else{
                        let message = [];
                        for(let key in error.response.data) {
                            for(let messagekey in error.response.data[key]) {
                                message.push(error.response.data[key]);
                            }
                        }
                      }
                    }else if(error.response.status == 403){
                      stack_error(error.response.data.error.message)
                      this.$router.push('/password_reset');
                    }else{
                      toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                    }
                })
            }
        }
    }
</script>

<style lang="css">
</style>
