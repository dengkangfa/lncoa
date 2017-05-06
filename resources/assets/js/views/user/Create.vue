<template>
    <vue-form :title="$t('el.form.create_user')">
        <div slot="buttons">
            <router-link to="/users" class="btn btn-default" exact>{{ $t('el.form.back') }}</router-link>
        </div>
        <div slot="content">
            <div class="row">
                <form class="form col-md-4 col-md-offset-4" role="form" @submit.prevent="validateBeforeSubmit">
                    <div class="form-group has-feedback" :class="{'has-error': nameFlags.invalid, 'has-success': nameFlags.valid}">
                        <label for="name">{{ $t('el.form.name') }}</label>
                        <input name="name"  data-vv-delay="500" type="text" class="form-control" id="name" :placeholder="$t('el.form.name')">
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
                        <label for="email">{{ $t('el.form.email') }}</label>
                        <input name="email" v-validate="'required|email|email_unique'" data-vv-delay="1" type="email" class="form-control" id="email" :placeholder="$t('el.form.email')">
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
                        <label for="password">{{ $t('el.form.password') }}</label>
                        <input name="password" v-validate="'required|min:6|max:16'" data-vv-delay="500" type="password" class="form-control" id="password" :placeholder="$t('el.form.password')">
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
                        <input  v-validate="'required|confirmed:password'" name="password_confirmation" type="password" class="form-control" id="password_confirmation" :placeholder="$t('el.form.confirm_password')">
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
                    <div class="form-group">
                        <button type="submit" :disabled="!formDirty" class="btn btn-primary">{{ $t('el.form.create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </vue-form>
</template>

<script>
    import { stack_error } from '../../config/helper.js'
    import { Validator, mapFields, ErrorBag } from 'vee-validate';

    export default {
        created() {
            Validator.extend('email_unique', {
                getMessage: (field) => `该${field}已被使用.`,
                validate: (value) => new Promise(resolve => {
                    axios.get('/api/register/email/check?email=' + value).then(response => {
                       return resolve({valid: response.data.success});
                    });
                })
            });
            Validator.extend('name_unique', {
                getMessage: (field) => `该${field}已被使用.`,
                validate: (value) => new Promise(resolve => {
                    axios.get('/api/register/name/check?name=' + value).then(response => {
                       return resolve({valid: response.data.success});
                    });
                })
            });
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
            }
        },
        methods: {
          //提交之前验证所有表单信息
          validateBeforeSubmit(event) {
              let vm = this;
              vm.$validator.validateAll().then(() => {
                  vm.create(event);
              }).catch(() => {
                toastr.error(vm.$t('el.notification.submit_data_error'))
              });
            },
            create(event) {
                //表单数据对象
                const formData = new FormData(event.target)
                const bag = new ErrorBag();

                axios.post('/api/user', formData).then( response => {
                        toastr.success(this.$t('el.notification.create_user'));

                        this.$router.push('/users')
                    }, error => {
                        if(error.response.status == 422){
                          console.log(error.response);
                          stack_error(error.response.data)
                        }else{
                          toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                        }
                    })
            }
        },

    }
</script>
