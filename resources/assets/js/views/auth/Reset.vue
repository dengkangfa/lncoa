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
                    <form @submit.prevent="submit">
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
                        <button type="submit" class="btn btn-primary btn-block" :disabled= "!formDirty">{{$t('el.form.reset')}}</button>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </section>
</template>

<script>
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
          }
        },
        methods: {
            submit() {
                axios.post('/api/password/reset',this.form).then(response => {
                  console.log(response);
                    let data = response.data[0];
                    localStorage.setItem(this.form.email + '_refresh_token', data.refresh_token);
                    localStorage.access_token = data.access_token;
                    this.$router.push('/');
                },error => {
                    if(error.response.status == 422){
                      //表单验证有错误执行
                      stack_error(error.response.data.error.message)
                    }else{
                      toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                    }
                    this.$router.push('/password_reset');
                })
            }
        }
    }
</script>

<style lang="css">
</style>
