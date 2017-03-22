<template lang="html">
  <vue-form :title="$t('form.edit_role')">
        <div slot="buttons">
            <router-link to="/roles" class="btn btn-default" exact>{{ $t('form.back') }}</router-link>
        </div>
        <div slot="content">
            <div class="row">
                <form class="form col-md-4 col-md-offset-4" role="form" @submit.prevent="edit">
                    <div class="form-group">
                        <label for="role">{{ $t('form.role') }}</label>
                        <input type="text" class="form-control" id="role" :placeholder="$t('form.role')" name="name" v-model="role.name" disabled>
                    </div>
                    <div class="form-group">
                        <label for="display_name">{{ $t('form.display_name') }}</label>
                        <input type="text" class="form-control" id="display_name" :placeholder="$t('form.display_name')" name="display_name" v-model="role.display_name">
                    </div>
                    <div class="form-group">
                        <label for="description">{{ $t('form.description') }}</label>
                        <textarea class="form-control" name="description" id="description" :placeholder="$t('form.description')" v-model="role.description"></textarea>
                    </div>

                    <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">用户管理</el-checkbox>
                    <div style="margin: 15px 0;"></div>
                    <el-checkbox-group v-model="checkedCities" @change="handleCheckedCitiesChange">
                      <el-checkbox v-for="city in cities" :label="city">{{city}}</el-checkbox>
                    </el-checkbox-group>

                    <el-button type="text" @click="treeVisible = true" >设置可见菜单</el-button>

                    <div class="form-group">
                        <button type="submit" class="btn btn-info">{{ $t('form.edit') }}</button>
                    </div>


                    <el-dialog title="可见菜单" v-model="treeVisible" size="tiny">
                        <el-tree
                          :data="tree"
                          show-checkbox
                          node-key="id"
                          :default-checked-keys="menusId"
                          :props="defaultProps">
                        </el-tree>
                        <div slot="footer" class="dialog-footer">
                          <el-button @click="treeVisible = false">{{ $t('form.cancel') }}</el-button>
                          <el-button type="primary" @click="treeVisible = false">{{ $t('form.ok') }}</el-button>
                        </div>
                    <el-dialog>
                </form>
            </div>
        </div>
    </vue-form>
</template>

<script>
const cityOptions = ['上海', '北京', '广州', '深圳'];
export default {
  data() {
      return {
          role: {},
          menus: [],
          menusId: [],
          treeVisible: false,
          defaultProps: {
             children: 'items',
             label: 'title'
          },
          checkAll: true,
           checkedCities: ['上海', '北京'],
           cities: cityOptions,
           isIndeterminate: true
      }
  },
  created() {
      let vm = this;
      axios.all([this.getRole(), this.getMenus()])
        .then(axios.spread(function (role,menus){
            vm.role = role.data.data;
            vm.setMenusId();
            vm.menus = menus.data.data;
        }));
  },
  methods: {
      getRole(){
          return axios.get('/api/role/' + this.$route.params.id + '/edit'+'?include=menus');
      },
      getMenus(){
          return axios.get('/api/menus');
      },
      edit() {
          axios.put('/api/role/' + this.$route.params.id, this.role).then((response) => {
                  toastr.success('You updated a new account information!')

                  this.$router.push('/roles')
              })
      },
      setMenusId() {
        console.log(this.role);
          let menus = this.role['menus'].data,vm = this;
          menus.forEach(function(value, index ,array){
              if(value['uri'] != ''){
                vm.menusId.push(value['id']);
              }
          })
      },
      handleCheckAllChange(event) {
      this.checkedCities = event.target.checked ? cityOptions : [];
      this.isIndeterminate = false;
    },
    handleCheckedCitiesChange(value) {
      console.log(value);
      let checkedCount = value.length;
      this.checkAll = checkedCount === this.cities.length;
      this.isIndeterminate = checkedCount > 0 && checkedCount < this.cities.length;
    }

  },
  computed: {
      tree() {
          let temp = [],menus = this.menus;
          for(let i in menus){
              temp[menus[i]['id']] = menus[i];
              temp[menus[i]['id']]['title'] = temp[menus[i]['id']]['title'];
              temp[menus[i]['id']]['items'] = [];
              if(menus[i]['parent_id'] != null) {
                  temp[menus[i]['parent_id']]['items'].push(menus[i]);
              }
          }
          temp.forEach(function(value, index, array){

              if(value['parent_id'] != null) {
                delete array[index]
              }
          })
          let tree = [];
          for(let i in temp) {
              tree.push(temp[i])
          }
          return tree;
      },

  }
}
</script>
