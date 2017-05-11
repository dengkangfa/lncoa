<template lang="html">
    <div class="container profile">
        <div class="row">
            <div class="col-md-2 col-md-offset-1">
                <avatar :src="user.avatar"></avatar>
            </div>
            <div class="col-md-7" style="margin-top:5px">
                <form  class="form-horizontal" @submit.prevent="validateBeforeSubmit">
                    <!-- 用户名 -->
                    <div class="form-group">
                      <label for="name" class="col-sm-2 control-label">{{ $t('el.form.name') }}</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="name" :placeholder="user.name" disabled>
                      </div>
                    </div>
                    <!-- Email -->
                    <div class="form-group">
                      <label for="email" class="col-sm-2 control-label">{{ $t('el.form.email') }}</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" :placeholder="user.email" disabled>
                      </div>
                    </div>
                    <!-- 昵称 -->
                    <div class="form-group has-feedback" :class="{'has-error': errors.has('nickname')}">
                      <label for="nickName" class="col-sm-2 control-label">{{ $t('el.form.nickname') }}</label>
                      <div class="col-sm-10">
                        <input v-validate="{ rules: { regex: /^[\u4e00-\u9fff\w]{2,16}$/} }"
                            data-vv-delay="500"
                            type="text"
                            class="form-control"
                            name="nickname"
                            v-model="user.nickname">
                            <!-- 图标 -->
                            <span v-show="nickNameFlags.dirty || nickNameFlags.invalid" class="glyphicon form-control-feedback"
                             :class="{'glyphicon-warning-sign': nickNameFlags.invalid}">
                            </span>
                            <!-- 图标END -->
                            <!-- 错误消息 -->
                            <span v-show="errors.has('nickname')" class="help-block">
                              <strong>{{ errors.first('nickname') }}</strong>
                            </span>
                            <!-- 错误消息END -->
                      </div>
                    </div>
                    <!-- 联系方式 -->
                    <div class="form-group has-feedback" :class="{'has-error': errors.has('mobile')}">
                        <label for="mobile" class="col-sm-2 control-label">{{ $t('el.form.mobile') }}</label>
                        <div class="col-sm-10">
                            <input v-validate="{ rules: { regex: /^1[34578][0-9]{9}$/} }"
                              type="text"
                              class="form-control"
                              name="mobile"
                              v-model="user.mobile">
                           <!-- 图标 -->
                           <span v-show="mobileFlags.dirty || mobileFlags.invalid" class="glyphicon form-control-feedback"
                            :class="{'glyphicon-warning-sign': mobileFlags.invalid}">
                           </span>
                           <!-- 图标END -->
                           <!-- 错误消息 -->
                           <span v-show="errors.has('mobile')" class="help-block">
                             <strong>{{ errors.first('mobile') }}</strong>
                           </span>
                           <!-- 错误消息END -->
                        </div>
                    </div>
                    <div class="form-group" id="distpicker">
                      <label for="city" class="col-sm-2 control-label">所在城市</label>
                      <div class="col-sm-10">
                          <v-distpicker :province="user.province" :city="user.city" @selected="selected" hide-area></v-distpicker>
                      </div>
                    </div>
                    <!-- 描述 -->
                    <div class="form-group has-feedback" :class="{'has-error': errors.has('description')}">
                      <label for="description" class="col-sm-2 control-label">{{ $t('el.form.user_descript') }}</label>
                      <div class="col-sm-10">
                        <textarea v-validate="'max:255'"
                         class="form-control"
                          name="description"
                           rows="3"
                           v-model="user.description"></textarea>
                           <span v-show="errors.has('description')" class="help-block">
                             <strong>{{ errors.first('description') }}</strong>
                           </span>
                      </div>
                    </div>
                    <button type="submit" :disabled="!formDirty" class="btn profile-ben col-sm-10 col-sm-offset-2">{{$t('el.form.update_user_info')}}</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
  import server from '../../config/api'
  import VDistpicker from 'v-distpicker'
  import { mapState } from 'vuex'
  import { mapFields } from 'vee-validate';
  import { stack_error } from '../../config/helper.js'

  export default {
    data() {
      return {
        flags: false
      }
    },
    computed: {
        ...mapState([
            'user'
        ]),
        ...mapFields({
          nickNameFlags: 'nickname',
          mobileFlags: 'mobile',
          descriptionFlags: 'description'
        }),
        //表单是否有更改
        formDirty() {
          // are some fields dirty?
          return Object.keys(this.validataFields).some(key => this.validataFields[key].dirty) || this.flags;
        }
    },
    components: {
       VDistpicker
    },
    created() {
        this.$validator.updateDictionary({
            zh_CN: {
                attributes: {
                    nickname: '昵称',
                    mobile: '联系方式',
                    description: '个人简叙'
                }
        }
      })
    },
    methods: {
      //提交之前验证所有表单信息
      validateBeforeSubmit() {
          let vm = this;
          vm.$validator.validateAll().then(() => {
              vm.update();
          }).catch(() => {
              toastr.error(vm.$t('el.notification.submit_data_error'))
          });
        },
        update: function() {
            if(!this.errors.any()) {
                axios.put('/api/user/' + this.user.id, this.user).then( response => {
                  toastr.success(this.$t( 'el.notification.update_profile' ));
                }, error => {
                  if(error.response.status == 403){
                    toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                  }else{
                    stack_error(error.response.data)
                  }
                });
            }
        },
        selected(val) {
            this.user.province = val.province;
            this.user.city = val.city;
            this.flags = true;
        }
    }

  }
</script>

<style lang="css">
  .form-control, .form-control:focus, input {
      border-width: 2px;
      box-shadow: none;
      outline: 0;
  }
  .profile-ben {
      background-color:#324157;
      border-color: #324b57;
      color: #fff;
  }
  .profile-ben:hover {
      color: #fff;
      background-color: #324b57;
      border-color: #324157;
  }
  textarea {
    resize: none;
  }
  #distpicker .address select{
    color: #95a5a6 !important;
    border: 2px solid #dce4ec !important;
  }
</style>
