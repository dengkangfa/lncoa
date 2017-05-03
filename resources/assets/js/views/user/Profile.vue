<template lang="html">
    <div class="container profile">
        <div class="row">
            <div class="col-md-2 col-md-offset-1">
                <avatar :src="user.avatar"></avatar>
            </div>
            <div class="col-md-7" style="margin-top:5px">
                <form  class="form-horizontal" @submit.prevent="update">
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
                    <div class="form-group">
                      <label for="nickName" class="col-sm-2 control-label">{{ $t('el.form.nickname') }}</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="nickName" v-model="user.nickname">
                      </div>
                    </div>
                    <!-- 联系方式 -->
                    <div class="form-group">
                        <label for="mobile" class="col-sm-2 control-label">{{ $t('el.form.mobile') }}</label>
                        <div class="col-sm-10">
                            <input v-validate="{ rules: { regex: /^1[34578][0-9]{9}$/} }"
                             type="text" class="form-control"
                              :class="{'input': true, 'is-danger': errors.has('email') }"
                               id="mobile"
                                name="mobile"
                                 v-model="user.mobile">
                        </div>
                    <!-- 描述 -->
                    <div class="form-group">
                      <label for="description" class="col-sm-2 control-label">{{ $t('el.form.description') }}</label>
                      <div class="col-sm-10">
                        <textarea class="form-control" rows="3" v-model="user.description"></textarea>
                      </div>
                    </div>
                    <button type="submit" class="btn profile-ben col-sm-10 col-sm-offset-2">{{$t('el.form.update_user_info')}}</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
  import server from '../../config/api'
  import { mapState } from 'vuex'
  import { stack_error } from '../../config/helper.js'

  export default {
    data() {
        return {
            defaultValue: {},
            userinfo: {},
        }
    },
    computed: {
        ...mapState([
            'user'
        ]),
    },
    created() {
        this.defaultValue = this.user;
    },
    methods: {
        update: function() {
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
    }

  }
</script>

<style lang="css" scoped>
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
</style>
