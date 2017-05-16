<template lang="html">
  <vue-form :title="$t('el.form.edit_role')">
        <div slot="buttons">
            <router-link to="/roles" class="btn btn-default" exact>{{ $t('el.form.back') }}</router-link>
        </div>
        <div slot="content" id="role-edit">
            <div class="row">
                <form class="form col-md-4 col-md-offset-4" role="form" @submit.prevent="edit">
                    <div class="form-group">
                        <label for="role">{{ $t('el.form.role') }}</label>
                        <input type="text" class="form-control" id="role" :placeholder="$t('el.form.role')" name="name" v-model="role.name" disabled>
                    </div>
                    <div class="form-group">
                        <label for="display_name">{{ $t('el.form.display_name') }}</label>
                        <input type="text" class="form-control" id="display_name" :placeholder="$t('el.form.display_name')" name="display_name" v-model="role.display_name">
                    </div>
                    <div class="form-group">
                        <label for="description">{{ $t('el.form.description') }}</label>
                        <textarea class="form-control" name="description" id="description" :placeholder="$t('el.form.description')" v-model="role.description"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="permission" style="display: block;">权限</label>
                        <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox>
                        <div id="permission">
                          <el-checkbox-group v-model="permsId" @change="handleCheckedCitiesChange">
                            <el-checkbox v-for="permission in permissions" :label="permission.id">{{permission.display_name}}</el-checkbox>
                          </el-checkbox-group>
                        </div>
                    </div>

                    <el-button type="text" @click="openTree" >设置可见菜单</el-button>
                    <el-dialog title="可见菜单" v-model="treeVisible" :size="isPhone ? 'full' : 'tiny'">
                      <el-tree
                      :data="tree"
                      show-checkbox
                      node-key="id"
                      ref="tree"
                      :default-checked-keys="menusId"
                      :props="defaultProps">
                    </el-tree>
                    <div slot="footer" class="dialog-footer">
                      <el-button @click="cancel">{{ $t('el.form.cancel') }}</el-button>
                      <el-button type="primary" @click="treeVisible = false">{{ $t('el.form.ok') }}</el-button>
                    </div>
                    </el-dialog>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info">{{ $t('el.form.edit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </vue-form>
</template>

<script>
  import Modal from '../../components/Modal'
  import { mapState } from 'vuex'
  export default {
    data() {
        return {
            role: {},
            menus: [],
            permissions: '',
            menusId: [],
            permsId: [],
            oldCheckList: {},
            treeVisible: false,
            defaultProps: {
               children: 'items',
               label: 'title'
            },
            checkAll: true,
            cities: [],
            isIndeterminate: true
        }
    },
    created() {
        let vm = this;
        axios.all([this.getRole(), this.getMenus(), this.getPermission()])
          .then(axios.spread(function (role,menus,permissions){
              vm.role = role.data.data;
              vm.setMenusId();
              vm.setPermsId();
              vm.menus = menus.data.data;
              vm.permissions = permissions.data.data;
          }));
    },
    components: {
        Modal,
    },
    computed: {
        tree() {
            //将一维数组的菜单转换成为二维(目前项目只考虑二级)菜单树
            let temp = [],menus = this.deepCopy(this.menus);
            for(let i in menus){
                temp[menus[i]['id']] = menus[i];
                temp[menus[i]['id']]['title'] = this.$t( 'el.sidebar.' + temp[menus[i]['id']]['title'] );
                temp[menus[i]['id']]['items'] = [];
                if(typeof menus[i]['parent_id'] != 'object') {
                    temp[menus[i]['parent_id']]['items'].push(menus[i]);
                }
            }
            temp.forEach(function(value, index, array){

                if(typeof value['parent_id'] != 'object') {
                  delete array[index]
                }
            })
            let tree = [];
            for(let i in temp) {
                tree.push(temp[i])
            }
            return tree;
        },
        ...mapState([
            'isPhone'
        ])
    },
    methods: {
        getRole(){
            return axios.get('/api/role/' + this.$route.params.id + '/edit'+'?include=menus,permissions');
        },
        getMenus(){
            return axios.get('/api/menus');
        },
        getPermission(){
            return axios.get('/api/permission?pageSize=all');
        },
        edit() {
            this.updateRoleMenuValue();
            this.role.permissions = this.permsId;
            axios.put('/api/role/' + this.$route.params.id, this.role).then((response) => {
                    toastr.success(this.$t('el.notification.update_role'))

                    //返回角色列表
                    this.$router.push('/roles')
                })
        },
        setMenusId() {
          //当前用户可见菜单
            let menus = this.role['menus'].data,vm = this;
            menus.forEach(function(value, index, array){
                if(value['uri'] != ''){
                  vm.menusId.push(value['id']);
                }
            })
        },
        //当前角色拥有的权限
        setPermsId(){
            let permissions = this.role['permissions'].data,vm = this;
            permissions.forEach(function(value, index, array){
                vm.permsId.push(value['id']);
            })
        },
        //全选回调
        handleCheckAllChange(event) {
          let permissionsId = [];
          for (var i = 0; i < this.permissions.length; i++) {
            permissionsId.push(this.permissions[i].id);
          }
          this.permsId = event.target.checked ? permissionsId : [];
          this.isIndeterminate = false;
        },
        //判断当前是否已全选
        handleCheckedCitiesChange(value) {
          let checkedCount = value.length;
          this.checkAll = checkedCount === this.permissions.length;
          this.isIndeterminate = checkedCount > 0 && checkedCount < this.cities.length;
        },
        updateRoleMenuValue() {
            //如果可见菜单未做修改，则将role对象的menus字段设为null
            if(typeof this.$refs.tree == 'undefined'){
                this.role['menus'] = null;
            }else{
                let temp = [], items = this.$refs.tree.getCheckedNodes();
                for (var i = 0; i < items.length; i++) {
                  temp.push(items[i].id);
                  if(items[i].parent_id != null) {
                      temp.push(items[i].parent_id);
                  }
                }
                let a = {}, j=0;
                for(var i = 0; i < temp.length; i++){
                  if(typeof a[temp[i]] == "undefined")
                      a[temp[i]] = 1;
                }
                temp.splice(0,temp.length);

                for(let i in a)
                  temp.push(i);
                temp.splice(-1,1)
                this.role['menus'] = temp;
            }
        },
        //为了不影响原值，使用克隆后的值来做修改
        deepCopy(source) {
          //克隆对象
          var result={};
           for (var key in source) {
                result[key] = typeof source[key]==='object'? this.deepCopy(source[key]): source[key];
             }
             return result;
        },
        openTree() {
            //设置菜单面板打开时调用
            this.oldCheckList = this.menus;
            this.treeVisible = true;
        },
        cancel() {
            //弹出框取消事件函数，将其拥有的角色数据回调到打开弹出框前。
            this.menus = this.oldCheckList;
            this.treeVisible = false;
        },
        //提交隐藏树弹出框
        confirm() {
            this.treeVisible = false;
        },

    },
  }
</script>
<style media="screen">
  @media (max-width: 768px){
    .dialog {
      width: 70%;
    }
  }

  #role-edit .el-checkbox-group label:nth-child(1) {
      margin-left: 15px;
  }
</style>
