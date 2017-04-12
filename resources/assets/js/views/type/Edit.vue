<template lang="html">
    <vue-form :title="$t('el.form.create_type')">
        <div slot="buttons">
            <router-link to="/system-types" class="btn btn-default" exact>{{ $t('el.form.back') }}</router-link>
        </div>
        <div slot="content">
            <div class="row">
                <el-form :model="type" label-position="top" ref="type" label-width="100px" class="col-md-4 col-md-offset-4">
                  <!-- 场地名称 -->
                  <el-form-item :label="$t( 'el.form.type_name' )" prop="name">
                    <el-input v-model="type.name" :placeholder="$t('el.form.type_name')"></el-input>
                  </el-form-item>
                  <!-- 场地描述 -->
                  <el-form-item :label="$t( 'el.form.description' )" prop="describe">
                      <el-input type="textarea" autosize :placeholder="$t('el.form.description')" v-model="type.describe"></el-input>
                  </el-form-item>
                  <!-- 审核人组 -->
                  <template v-if="type.roles">
                  <el-form-item
                    v-for="(approver, index) in type.roles.data"
                    :label="$t('el.form.approver') + index"
                    :key="approver.name"
                    :prop="'roles.data.' + index + '.id'"
                  >
                    <el-select v-model="approver.id" filterable :placeholder="$t('el.form.approver_select')" @change="change">
                      <el-option
                        v-for="item in roles"
                        :label="item.display_name"
                        :value="item.id"
                        :disabled="item.disabled">
                      </el-option>
                    </el-select>
                    <el-button @click.prevent="removeApprover(approver)">{{ $t('el.form.delete') }}</el-button>
                  </el-form-item>
                </template>
                  <el-form-item
                    :label="$t( 'el.form.is_enable' )"
                    prop="disabled"
                  >
                    <el-switch
                      v-model="status"
                      on-text=""
                      off-text="">
                    </el-switch>
                  </el-form-item>
                  <el-form-item>
                    <el-button type="primary" @click="submitForm('type')">{{ $t('el.form.submit') }}</el-button>
                    <el-button @click="addApprover">{{ $t('el.form.add_approver') }}</el-button>
                    <!-- <el-button @click="resetForm('type')">{{ $t('el.form.reset') }}</el-button> -->
                  </el-form-item>
                </el-form>
            <div>
        </div>
    </vue-form>
</template>

<script>
  import { stack_error } from '../../config/helper.js'
  import server from '../../config/api';
  import { mapState } from 'vuex'

  export default {
    data() {
        return {
          type: {},
          roles: [],
          status: true,
        }
      },
      created() {
          let vm = this;
          axios.all([this.getRole(), this.gettype()])
            .then(axios.spread(function (roles, type){
                //所有角色组
                vm.roles = roles.data.data;
                vm.type = type.data.data;
                vm.status = vm.type.disabled ? false : true;
            }));
      },
      computed: {
          ...mapState([
              'types'
          ]),
      },
      methods: {
         getRole() {
           return axios.get(server.api.roles);
         },
         gettype() {
           return axios.get( server.api.type + "/" + this.$route.params.id + "/edit" + "?include=roles" );
         },
         submitForm(formName) {
           let vm = this;
           this.rolesId();
           this.type.disabled = !this.status;
           axios.put(server.api.type + "/" + this.$route.params.id, this.type).then( response => {
             console.log(response);
               toastr.success(vm.$t('el.notification.update_type'))

               vm.$router.push('/system-types')
           }, error => {
                stack_error(error.response.data);
           })
         },
         resetForm(formName) {
           this.$refs[formName].resetFields();
           console.log(this.type);
           this.change();
         },
         removeApprover(item) {
           var index = this.type.roles.data.indexOf(item)
           if (index !== -1) {
             this.type.roles.data.splice(index, 1)
             this.change()
           }
         },
         addApprover() {
           this.type.roles.data.push({
             id: '',
             name: Date.now()
           });
         },
         change(val) {
           var approversId = [];
            this.type.roles.data.forEach(function(value){
                if(value.value != ''){
                  approversId.push(value.value);
                }
            })
            this.roles.forEach(function(value, index, array){
                for(let i=0;i<approversId.length;i++){
                  if(value['id'] == approversId[i]){
                    value.disabled = true;
                    return
                  }
                }
                value.disabled = false;
            })
         },
         parentPath(pid,arr) {
            arr.forEach(function(value){
                if(value['id'] == pid){
                  console.log(value['id']);
                    if(value['parent_id']){
                      console.log(this.type);
                        this.type.push(this.parentPath(value['id']));
                    }
                    this.type.push(value['id']);
                }else{
                    if(arr.children){
                        this.parentPath(pid,arr.children);
                    }
                }
            })
         },
         rolesId() {
            let temp = [];
            for (var i = 0; i < this.type.roles.data.length; i++) {
                temp.push(this.type.roles.data[i].id);
            }
            this.type.approvers = temp;
            console.log(this.type.approvers);
         }
     }
   }
</script>

<style lang="css">
</style>
