<template>
    <vue-form :title="$t('el.form.edit_user')">
        <div slot="buttons">
            <router-link to="/users" class="btn btn-default" exact>{{ $t('el.form.back') }}</router-link>
        </div>
        <div slot="content">
            <div class="row">
                <form class="form col-md-4 col-md-offset-4" role="form" @submit.prevent="edit">
                    <div class="form-group text-center">
                        <img :src="user.avatar ? user.avatar : 'http://lncoa.app/images/default.png'" id="avatar" class="img-circle" width="100" :alt="user.name">
                    </div>


                    <div class="form-group">
                        <label for="name">{{ $t('el.form.name') }}</label>
                        <input type="text" class="form-control" id="name" :placeholder="$t('el.form.name')" v-model="user.name" disabled>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ $t('el.form.email') }}</label>
                        <input type="email" class="form-control" id="email" :placeholder="$t('el.form.email')" v-model="user.email" disabled>
                    </div>
                    <div class="form-group">
                        <label for="nickname">{{ $t('el.form.nickname') }}</label>
                        <input type="text" class="form-control" id="nickname" :placeholder="$t('el.form.nickname')" v-model="user.nickname">
                    </div>
                    <div class="form-group">
                        <label for="description">{{ $t('el.form.description') }}</label>
                        <input type="text" class="form-control" id="description" :placeholder="$t('el.form.description')" v-model="user.description">
                    </div>
                    <div class="form-group">
                        <label for="password">{{ $t('el.form.password') }}</label>
                        <input type="password" class="form-control" id="password" :placeholder="$t('el.form.password')" v-model="user.password">
                    </div>

                    <!-- Form -->
                    <!-- 设置角色 -->
                    <div class="form-group">
                      <label for="role">{{ $t('el.table.role') }}</label>
                      <div id="role">
                        <el-tag v-for="role in user_role" style="margin-right:5px">{{role}}</el-tag>
                        <i @click="openRole" class="el-icon-edit"></i>
                      </div>
                    </div>

                    <!-- 设置角色弹出框 -->
                    <el-dialog title="设置角色" v-model="dialogFormVisible">
                      <el-checkbox-group v-model="user_role">
                        <el-checkbox v-for="role in roles" :label="role.display_name" :data="role.id"></el-checkbox>
                      </el-checkbox-group>
                    <div slot="footer" class="dialog-footer">
                      <el-button @click="cancel">{{ $t('el.form.cancel') }}</el-button>
                      <el-button type="primary" @click="dialogFormVisible = false">{{ $t('el.form.ok') }}</el-button>
                    </div>
                    </el-dialog>
                    <!-- 设置角色弹出框END -->

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{ $t('el.form.edit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </vue-form>
</template>

<script>
    export default{
        data() {
            return {
                user: {},
                roles: {},
                user_role: [],
                oldCheckList: {},
                dialogFormVisible: false,
                formLabelWidth: '120px'
            }
        },
        created() {
            //在加载的过程中从服务端获取当前ID所对应的用户信息，以及获取所有角色信息。
            let vm = this;
            axios.all([this.getUserInfo(),this.getRoles()])
              .then(axios.spread(function (user,roles){
                  vm.user = user.data.data;
                  //获取当前用户拥有的角色
                  let checkRoles = user.data.data.roles.data;
                  checkRoles.forEach(function (e){
                      //将其拥有的角色对应的角色显示名称存放进user_role数组中
                      vm.user_role.push(e.display_name);
                  });
                  vm.roles = roles.data.data;
              }));
        },
        methods: {
            edit() {
                //在请求服务端修改数据前，重新加载一下用户新的角色id数组
                this.updateUserRoleValue();
                axios.put('/api/user/' + this.$route.params.id + '?include=roles', this.user).then(response => {
                        toastr.success(this.$t('el.notification.update_user'))

                        this.$router.push('/users')
                }, error => {
                        toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                })
            },
            getUserInfo() {
                //获取当前需要修改的用户信息
                return axios.get('/api/user/' + this.$route.params.id + '/edit' + '?include=roles');
            },
            getRoles() {
                //获取所有角色信息
                return axios.get('/api/role');
            },
            openRole() {
                this.oldCheckList = this.user_role;
                this.dialogFormVisible = true;
            },
            cancel() {
                //弹出框取消事件函数，将其拥有的角色数据回调到打开弹出框前。
                this.user_role = this.oldCheckList;
                this.dialogFormVisible = false;
            },
            updateUserRoleValue() {
                //check_id拥有的角色id数组
                let check_id = [], vm = this;
                //遍历角色数组，查找当前用户拥有的角色其对应的id数组
                vm.roles.forEach(function (e){
                    if(vm.user_role.indexOf(e.display_name) > -1){
                        check_id.push(e.id);
                      }
                });
                vm.user.roles = check_id;
            }

        }
    }
</script>
