<template>
  <vue-form :title="$t('el.form.create_type')">
      <div slot="buttons">
          <router-link to="/system-types" class="btn btn-default" exact>{{ $t('el.form.back') }}</router-link>
      </div>
      <div slot="content">
          <div class="row">
              <el-form :model="type" label-position="top" ref="type" label-width="100px" class="col-md-4 col-md-offset-4">
                <!-- 场地所属的父级选择 -->
                <el-form-item
                  label="parentId"
                  prop="parent_id"
                >
                <el-cascader
                  v-model="type.parent_id"
                  placeholder="Root"
                  :options="types"
                  filterable
                  change-on-select
                  :props=props
                  :clearable="true"
                ></el-cascader>
                </el-form-item>
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
                  :label="$t('el.form.approver') + (index + 1)"
                  :key="approver.key"
                  :prop="'approvers.' + index + '.id'"
                  class="form-inline"
                >
                  <!-- <el-input v-model="approver.value" style="width: 80%; margin-right: 10px"></el-input> -->
                  <el-select v-model="approver.id"  style="padding-left: 0" filterable :placeholder="$t('el.form.approver_select')" @change="change">
                    <el-option
                      v-for="item in roles"
                      :label="item.display_name"
                      :value="item.id"
                      :disabled="item.disabled">
                    </el-option>
                  </el-select>
                    <!-- <select class=" form-control" v-model="approver.value ? 0 : approver.value" :placeholder="$t('el.form.approver_select')">
                        <option value=0>
                          请选择审核人
                        </option>
                        <option
                          v-for="item in roles"
                          :label="item.display_name"
                          :value="item.id"
                          :disabled="item.disabled"
                          >
                          {{ item.display_name }}
                        </option>
                    </select> -->
                    <el-button  @click.prevent="removeApprover(approver)"  style="padding: 10px;">{{ $t('el.form.delete') }}</el-button>
                </el-form-item>
                <el-row :gutter="20">
                  <el-col :span="8">
                    <el-form-item :label="$t( 'el.form.is_enable' )">
                      <el-switch
                        v-model="status"
                        on-text=""
                        off-text="">
                      </el-switch>
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                      <el-form-item prop="date_unique" label="唯一时间段">
                        <el-switch
                        v-model="type.date_unique"
                        on-text=""
                        off-text="">
                      </el-switch>
                    </el-form-item>
                  </el-col>
                </el-row>
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
  import server from '../../config/api';
  import { stack_error } from '../../config/helper.js'
  import { mapState, mapMutations } from 'vuex'

  export default {
    data() {
        return {
          type: {
            approvers: [{
              id: ''
            }],
            parent_id: [],
            name: '',
            describe: '',
            disabled: '',
            date_unique: true,
          },
          status: true,
          props: {
              value: 'id',
              label: 'name'
          },
          roles: [],
        }
      },
      created() {
          let vm = this;
          axios.get(server.api.roles).then( response => {
              vm.roles = response.data.data;
          })
      },
      computed: {
          ...mapState([
              'types'
          ]),
      },
      methods: {
         submitForm(formName) {
           //提交
           let vm = this;
           this.type.disabled = !this.status;
           axios.post(server.api.type, this.type).then( response => {
             console.log(response);
               toastr.success(vm.$t('el.notification.create_type'))
               vm.SET_TYPES(response.data)
               vm.$router.push('/system-types')
           }, error => {
                stack_error(error.response.data);
           })
         },
         resetForm(formName) {
           this.$refs[formName].resetFields();
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
             key: Date.now()
           });
         },
         change(val) {
           var approversId = [];
           console.log(this.type.approvers);
            this.type.approvers.forEach(function(value){
                if(value.id != ''){
                  approversId.push(value.id);
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
            console.log(this.roles);
         },
         ...mapMutations([
             'SET_TYPES'
         ]),
     }
  }
</script>

<style lang="css" scoped>
  .el-input{
      width: 80%;
      margin-right: 10px
  }
</style>
