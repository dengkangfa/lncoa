<template lang="html">
  <el-tabs value="reset_password">
   <el-tab-pane :label="$t('el.page.reset_password')" name="reset_password">
     <span slot="label"><i class="ion-locked"></i> {{$t('el.page.reset_password')}}</span>
      <form class="col-sm-4 col-sm-offset-4" @submit.prevent="validateBeforeSubmit">
          <!-- 旧密码 -->
          <div class="form-group has-feedback" :class="{'has-error': oldPasswordFlags.invalid, 'has-success': oldPasswordFlags.valid}">
              <label for="old_password" class="label-control">{{$t('el.form.old_password')}}</label>
              <input v-validate="'required'" type="password" class="form-control" name="old_password" v-model="user.old_password">
              <!-- 图标 -->
               <span v-show="oldPasswordFlags.dirty || oldPasswordFlags.invalid" class="glyphicon form-control-feedback"
                :class="{'glyphicon-warning-sign': oldPasswordFlags.invalid, 'glyphicon-ok': oldPasswordFlags.valid}">
               </span>
              <!-- 图标END -->
              <!-- 错误消息 -->
              <span v-show="errors.has('old_password')" class="help-block">
                <strong>{{ errors.first('old_password') }}</strong>
              </span>
              <!-- 错误消息END -->
          </div>
          <!-- 旧密码END -->
          <!-- 新密码 -->
          <div class="form-group has-feedback" :class="{'has-error': PasswordFlags.invalid, 'has-success': PasswordFlags.valid}">
              <label for="password" class="label-control">{{$t('el.form.new_password')}}</label>
              <input  name="password" v-validate="'required|min:6|max:16'" data-vv-delay="500" type="password" class="form-control" v-model="user.password">
              <!-- 图标 -->
              <span v-show="PasswordFlags.dirty || PasswordFlags.invalid" class="glyphicon form-control-feedback"
               :class="{'glyphicon-warning-sign': PasswordFlags.invalid, 'glyphicon-ok': PasswordFlags.valid}">
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
              <label for="password_confirmation" class="label-control">{{$t('el.form.new_password_confirmation')}}</label>
              <input v-validate="'required|confirmed:password'" data-vv-delay="500" type="password" class="form-control" name="password_confirmation" v-model="user.password_confirmation">
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
          <!-- 确认新密码END -->
          <button type="submit" class="btn btn-info" :disabled="!formDirty" name="button">{{$t('el.form.edit')}}</button>
      </form>
   </el-tab-pane>
   <el-tab-pane>
        <span slot="label"><i class="ion-android-notifications"></i> {{$t('el.page.notification')}}</span>
        <div class="col-md-12">
            <div class="panel panel-default panel-md">
                <div class="panel-body">
                    <h2 style="font-size: 30px;">
                      <i class="ion-gear-b"></i>  {{ $t('el.page.email_notification') }}
                    </h2>
                    <hr/>
                    <form @submit.prevent="submit">
                        <div class="form-group">
                            <label class="col-sm-3 control-label text-right" for="email_nitificat">{{ $t('el.form.open_email_notification') }}</label>
                            <div class="col-sm-9">
                              <el-switch
                                name="email_nitificat"
                                id="email_nitificat"
                                v-model="email_notify_enabled"
                                on-color="#13ce66"
                                off-color="#ff4949">
                              </el-switch>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="">
                                <button type="submit" class="btn btn-primary btn-lg" name="button">{{ $t('el.form.apply') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   </el-tab-pane>
   <el-tab-pane name="my_type" id="type" v-if="show_type">
      <span slot="label"><i class="ion-clipboard"></i> {{$t('el.page.my_type')}}</span>
      <div class="row" v-for="(type, index) in types">
        <div class="col-md-offset-4 col-md-4 col-sm-12">
          <!-- 类型名称 -->
          <h3>{{ index }}</h3>
          <!-- 管理该类型的角色组 -->
          <template v-for="(roles, priority) in type">
              <el-steps :space="space">
                <el-step v-for="(role, index) in roles" :class="{'bg' : index == priority}" :key="role.id" :title="role.display_name"></el-step>
              </el-steps>
          </template>
        </div>
      </div>
   </el-tab-pane>
  </el-tabs>
</template>

<script>
    import { stack_error } from '../../config/helper.js'
    import { mapFields } from 'vee-validate';
    import { mapState } from 'vuex'

    export default {
        data() {
            return {
                user: {
                  'old_password': '',
                  'password': '',
                  'password_confirmation': ''
                },
                types: [],
                space: 200,
                show_type: false,
                email_notify_enabled: true,
            }
        },
        created() {
          let vm = this;
          vm.email_notify_enabled = vm.$store.state.user.email_notify_enabled;
          //验证字段名称
          vm.$validator.updateDictionary({
                zh_CN: {
                    attributes: {
                        old_password: '旧密码',
                        password: '新密码',
                        password_confirmation: '确定新密码'
                    }
                }
          });
          vm.space = vm.$store.state.isPhone ? 75 : 200
          for (let i = 0; i < vm.permissions.length; i++) {
              if(vm.permissions[i].name == 'show-type') {
                  vm.show_type = true;
                  axios.get('/api/type/me').then( response => {
                      vm.types = response.data;
                  });
                  continue;
              }
          }
        },
        computed: {
          ...mapFields({
            oldPasswordFlags: 'old_password',
            PasswordFlags: 'password',
            passwordConfirmationFlags: 'password_confirmation'
          }),
          //表单是否有更改
          formDirty() {
            // are some fields dirty?
            return Object.keys(this.validataFields).some(key => this.validataFields[key].dirty);
          },
          ...mapState([
              'permissions'
          ]),
        },
        methods: {
          //提交之前验证所有表单信息
          validateBeforeSubmit() {
              let vm = this;
              vm.$validator.validateAll().then(() => {
                  vm.updatePwd();
              }).catch(() => {
                  toastr.error(vm.$t('el.notification.submit_data_error'))
              });
            },
            //调用api接口修改密码
            updatePwd() {
                if(!this.errors.any()) {
                    axios.put('/api/user/changepwd', this.user).then( response => {
                        toastr.success(this.$t( 'el.notification.update_password' ));
                        this.$router.push('/');
                    }, error => {
                        if(error.response.status == 422){
                          //表单验证有错误执行
                          stack_error(error.response.data.error.message)
                        }else{
                          toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                        }
                    })
                }
            },
            submit() {
                axios.put('/api/setting/notification',{'email_notify_enabled' : this.email_notify_enabled}).then( response => {
                    console.log(response);
                })
            }
        }
    }
</script>

<style lang="css" >
  .bg > div:first-child {
      background: #20a0ff !important;
      border-color: #20a0ff !important;
  }

  @media (min-width: 768px) {
      .text-right{
          text-align: right;
      }
  }

</style>
