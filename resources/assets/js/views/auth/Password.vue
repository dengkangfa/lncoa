<template lang="html">
  <section id="login-container">
      <div class="row">
          <div class="col-md-3" id="login-wrapper">
              <div class="panel panel-primary animated flipInY">
                  <div class="panel-heading">
                      <h3 class="panel-title">
                          {{$t('el.page.reset_password')}}
                      </h3>
                  </div>
                  <div class="panel-body">
                    <form @submit.prevent="submit">
                        <div class="form-group has-feedback" :class="{'has-error': errors.has('email')}">
                          <label class="control-label">{{$t('el.form.email')}}</label>
                          <input name="email" v-model="email" v-validate="'required|email|exist'" type="email"  class="form-control" :placeholder="$t('el.form.email_placeholder')">
                          <!-- 图标 -->
                          <span v-show="errors.has('email')" class="glyphicon form-control-feedback"
                            :class="{'glyphicon-warning-sign': errors.has('email')}">
                          </span>
                          <!-- 图标END -->
                          <!-- 错误消息 -->
                          <span v-show="errors.has('email')" class="help-block">
                            <strong>{{ errors.first('email') }}</strong>
                          </span>
                          <!-- 错误消息END -->
                        </div>
                        <div class="form-group">
                            <div>
                              <button type="submit" class="btn btn-primary col-md-12" :disabled="!formDirty || disabled" name="button">
                                {{ok ? $t('el.form.send_reset_password_email_ok') : $t('el.form.send_reset_password_email')}}
                              </button>
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
    import { Validator, mapFields } from 'vee-validate';
    export default {
        data() {
            return {
                ok: false,
                disabled: false,
                email: ''
            }
        },
        created() {
            if(!this.$validator.rules.exist){
                Validator.extend('exist', {
                    getMessage: (field) => `该${field}不存在.`,
                    validate: (value) => new Promise(resolve => {
                        axios.get('/api/register/email/check?email=' + value).then(response => {
                           return resolve({valid: !response.data.success});
                        });
                    })
                });
            }
            //验证字段名称
            this.$validator.updateDictionary({
                  zh_CN: {
                      attributes: {
                          email: '邮箱',
                      }
                  }
            });
        },
        methods: {
            submit() {
                //禁用提交按钮，反之重复提交
                this.disabled = true;
                //发送重置密码邮件
                axios.post('/api/password/email',{'email': this.email}).then( response => {
                    this.ok = true;
                }, error => {
                    if(error.response.status == 422){
                      this.disabled = false;
                      stack_error(error.response.data)
                    }else{
                      toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                    }
                })
            }
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
    }
</script>

<style lang="css">
</style>
