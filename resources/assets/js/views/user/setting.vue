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
   <el-tab-pane label="我的类型" name="second" id="type">
      <div class="row" v-for="(type, index) in types">
        <div class="col-md-offset-4 col-md-4">
          <!-- 类型名称 -->
          <h3>{{ index }}</h3>
          <!-- 管理该类型的角色组 -->
          <template v-for="(roles, priority) in type">
              <el-steps :space="200">
                <el-step v-for="(role, index) in roles" :class="{'bg' : index == priority}" :title="role.display_name"></el-step>
              </el-steps>
          </template>
        </div>
      </div>
   </el-tab-pane>
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
            },
            types: [],
        }
    },
    created() {
      axios.get('/api/type/me').then( response => {
          this.types = response.data;
          console.log(response);
      })
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
  .bg > div:first-child {
      background: #20a0ff !important;
      border-color: #20a0ff !important;
  }

</style>
