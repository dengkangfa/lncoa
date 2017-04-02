<template lang="html">
    <vue-form :title="$t('el.form.create_type')">
        <div slot="buttons">
            <router-link to="/types" class="btn btn-default" exact>{{ $t('el.form.back') }}</router-link>
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
                  <el-form-item
                    v-for="(approver, index) in type.approvers"
                    :label="$t('el.form.approver') + index"
                    :key="approver.name"
                    :prop="'approvers[' + index + '].id'"
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
                  <el-form-item>
                    <el-button type="primary" @click="submitForm('type')">{{ $t('el.form.submit') }}</el-button>
                    <el-button @click="addApprover">{{ $t('el.form.add_approver') }}</el-button>
                    <el-button @click="resetForm('type')">{{ $t('el.form.reset') }}</el-button>
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
          type: {
            approvers: [{
              value: ''
            }],
            parent_id: [],
            name: '',
            describe: '',
          },
          props: {
              value: 'id',
              label: 'name'
          },
          roles: [],
        }
      },
      created() {
          let vm = this;
          axios.all([this.getRole(), this.gettype()])
            .then(axios.spread(function (roles, type){
                vm.roles = roles.data.data;
                vm.type = type.data.data;
                vm.type.approvers = type.data.data.roles.data;
                console.log(vm.type);
                console.log(vm.type.approvers);
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
           axios.post(server.api.type, this.type).then( response => {
               toastr.success(vm.$t('el.notification.create_type'))

               vm.$router.push('/types')
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
           var index = this.type.approvers.indexOf(item)
           if (index !== -1) {
             this.type.approvers.splice(index, 1)
             this.change()
           }
         },
         addApprover() {
           this.type.approvers.push({
             id: '',
             name: Date.now()
           });
         },
         change(val) {
           var approversId = [];
            this.type.approvers.forEach(function(value){
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
         }
     }
   }
</script>

<style lang="css">
</style>
