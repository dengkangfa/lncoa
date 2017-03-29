<template>
  <vue-form :title="$t('el.form.create_site')">
      <div slot="buttons">
          <router-link to="/sites" class="btn btn-default" exact>{{ $t('el.form.back') }}</router-link>
      </div>
      <div slot="content">
          <div class="row">
              <el-form :model="site" label-position="top" ref="site" label-width="100px" class="col-md-4 col-md-offset-4">
                <!-- 场地所属的父级选择 -->
                <el-form-item
                  label="parentId"
                  prop="parentId"
                >
                <el-cascader
                  v-model="site.parentId"
                  placeholder="Root"
                  :options="options"
                  filterable
                  change-on-select
                ></el-cascader>
                </el-form-item>
                <!-- 场地名称 -->
                <el-form-item label="siteName" prop="siteName">
                  <el-input v-model="site.siteName"></el-input>
                </el-form-item>
                <!-- 场地描述 -->
                <el-form-item label="siteDesc" prop="siteDesc">
                    <el-input type="textarea" autosize placeholder="请输入内容" v-model="site.siteDesc"></el-input>
                </el-form-item>
                <!-- 审核人组 -->
                <el-form-item
                  v-for="(approver, index) in site.approvers"
                  :label="'审批人' + index"
                  :key="approver.key"
                  :prop="'approvers.' + index + '.value'"
                >
                  <!-- <el-input v-model="approver.value" style="width: 80%; margin-right: 10px"></el-input> -->
                  <el-select v-model="approver.value" filterable placeholder="请选择审核人" @change="change">
                    <el-option
                      v-for="item in roles"
                      :label="item.label"
                      :value="item.id"
                      :disabled="item.disabled">
                    </el-option>
                  </el-select>
                  <el-button @click.prevent="removeApprover(approver)">删除</el-button>
                </el-form-item>
                <el-form-item>
                  <el-button type="primary" @click="submitForm('site')">提交</el-button>
                  <el-button @click="addApprover">新增审批人</el-button>
                  <el-button @click="resetForm('site')">重置</el-button>
                </el-form-item>
              </el-form>
          <div>
      </div>
  </vue-form>
</template>

<script>
export default {
  data() {
      return {
        site: {
          approvers: [{
            value: ''
          }],
          parentId: [],
          siteName: '',
          siteDesc: '',
        },
        options: [{
          value: 1,
          label: '校级场地',
          children: [{
            value: 3,
            label: '图书报告大厅',
          },{
            value: 4,
            label: '博雅报告厅'
          }]
        }, {
          value: 2,
          label: '书院场地'
        }],
        roles: [{
            id: 1,
            label: '中间审核人',
        },{
            id: 2,
            label: '最终审核人'
        }]
      }
    },
    methods: {
     submitForm(formName) {
       console.log(this.site);
      //  this.$refs[formName].validate((valid) => {
      //    if (valid) {
      //      alert('submit!');
      //    } else {
      //      console.log('error submit!!');
      //      return false;
      //    }
      //  });
     },
     resetForm(formName) {
       this.$refs[formName].resetFields();
       this.change();
     },
     removeApprover(item) {
       var index = this.site.approvers.indexOf(item)
       if (index !== -1) {
         this.site.approvers.splice(index, 1)
         this.change()
       }
     },
     addApprover() {
       this.site.approvers.push({
         value: '',
         key: Date.now()
       });
     },
     change(val) {
       var approversId = [];
        this.site.approvers.forEach(function(value){
            if(value.value != ''){
              approversId.push(value.value);
              console.log(approversId);
            }
        })
        this.roles.forEach(function(value, index, array){
            for(let i=0;i<approversId.length;i++){
              console.log(value['id']);
              console.log(approversId[i]);
              if(value['id'] == approversId[i]){
                console.log(value['id']);
                value.disabled = true;
                return
              }
            }
            value.disabled = false;
        })
        console.log(this.roles);
     }
   }
}
</script>

<style lang="css">
  .el-input{
      width: 80%;
      margin-right: 10px
  }
</style>
