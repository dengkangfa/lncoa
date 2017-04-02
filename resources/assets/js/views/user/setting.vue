<template lang="html">
  <el-tabs>
   <el-tab-pane label="重置密码" name="first">
      <form class="col-sm-4 col-sm-offset-4">
          <div class="form-group">
              <label for="old_password" class="label-control">旧密码</label>
              <input type="password" class="form-control" name="old_password" v-model="user.old_password">
          </div>
          <div class="form-group">
              <label for="password" class="label-control">新密码</label>
              <input type="password" class="form-control" name="password" v-model="user.password">
          </div>
          <div class="form-group">
              <label for="password_confirmation" class="label-control">确定新密码</label>
              <input type="password" class="form-control" name="password_confirmation" v-model="user.password_confirmation">
          </div>
          <button type="button" class="btn btn-info" name="button" @click="updatePwd">修改</button>
      </form>
   </el-tab-pane>
   <el-tab-pane label="配置管理" name="second">配置管理</el-tab-pane>
   <el-tab-pane label="角色管理" name="third">角色管理</el-tab-pane>
   <el-tab-pane label="定时任务补偿" name="fourth">定时任务补偿</el-tab-pane>
  </el-tabs>
</template>

<script>
import { stack_error } from '../../config/helper.js'
export default {
    data() {
        return {
            user: {
              'old_password': '',
              'password': '',
              'password_confirmation': ''
            }
        }
    },
    methods: {
        updatePwd() {
            axios.put('/api/user/changepwd', this.user).then( response => {
                toastr.success(this.$t( 'el.notification.update_password' ));
                this.$router.push('/');
            }, error => {
                stack_error(error.response.data)
            })
        }
    }
}
</script>

<style lang="css">
</style>
