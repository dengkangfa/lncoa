<template lang="html">
  <div class="row">
      <div class="ibox">
          <div class="ibox-title">
              <h5>{{ $t('el.page.applicat') }}</h5>
          </div>
          <div class="ibox-content">
              <div class="row">
                <el-form ref="form" :model="form" class="col-md-8 col-md-offset-2" label-width="80px">
                  <el-form-item label="负责人">
                    <el-input v-model="form.principal" style="width:180px"></el-input>
                  </el-form-item>
                  <el-form-item label="联系方式">
                    <el-input v-model="form.mobile" style="width:180px"></el-input>
                  </el-form-item>
                  <el-form-item label="申请机构">
                    <el-select v-model="form.app_organ" allow-create multiple filterable :multiple-limit="5" placeholder="请选择">
                      <el-option
                        v-for="item in options"
                        :label="item.name"
                        :value="item.id"
                        >
                      </el-option>
                    </el-select>
                  </el-form-item>
                  <el-form-item label="申请类型">
                    <el-cascader
                      v-model="form.region"
                      placeholder="请选择申请类型"
                      :options="types"
                      filterable
                      :show-all-levels="false"
                      :props=props
                      :clearable="true"
                    ></el-cascader>
                  </el-form-item>
                  <el-form-item label="借用时段">
                    <el-col :span="15">
                      <el-date-picker type="datetimerange" placeholder="开始时间-结束时间" v-model="form.date" style="width: 100%;"></el-date-picker>
                    </el-col>
                  </el-form-item>
                  <el-form-item label="联合机构">
                    <el-switch on-text="有" off-text="无" v-model="form.unite"></el-switch>
                  </el-form-item>
                  <el-form-item label="机构名称" v-if="form.unite">
                    <el-input v-model="form.agency" ></el-input>
                  </el-form-item>

                  <el-form-item label="申请缘由">
                    <el-input type="textarea" v-model="form.reason"></el-input>
                  </el-form-item>
                  <el-form-item label="物资申请">
                    <el-input type="textarea" v-model="form.goods" placeholder="如果需要申请物资，请描述相关物资名称以及申请的数量"></el-input>
                  </el-form-item>
                  <el-form-item label="活动策划">
                    <el-upload
                      class="upload-demo"
                      action="api/upload"
                      :headers="headers"
                      :on-preview="handlePreview"
                      :on-remove="handleRemove"
                      :on-success="handleSuccess"
                      :file-list="form.fileList">
                      <el-button size="small" type="primary">点击上传</el-button>
                      <!-- <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div> -->
                      <div slot="tip" class="el-upload__tip">如果有关于申请的相关文件,请点击上传!</div>
                    </el-upload>
                  </el-form-item>
                  <el-form-item>
                    <el-button type="primary" @click="onSubmit">立即申请</el-button>
                    <el-button>取消</el-button>
                  </el-form-item>
                </el-form>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  import server from '../config/api'

  export default {
    data() {
       return {
         form: {
           principal: '',
           mobile: '',
           app_organ: '',
           region: [],
           date: '',
           agency: '',
           unite: false,
           type: [],
           reason: '',
           goods: '',
           fileList: [],
         },
         headers: {},
         types: [],
         props: {
             value: 'id',
             label: 'name'
         },
         options:[{label:'软件开发三班',value:1},{label:'软件开发二班',value:2}],
       }
     },
     created() {
         this.headers = {
             'Authorization': 'Bearer ' + this.$store.state.access_token
         }
         axios.get(server.api.type + "?structure=tree").then( response => {
             this.types = response.data;
         },(error) => {
             toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
         });
         axios.get(server.api.mechanism).then( response => {
            this.options = response.data;
         }, error => {
            toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
         })
     },
    //  computed: {
    //      ...mapState([
    //          'types'
    //      ]),
    //  },
     methods: {
       onSubmit() {
         console.log('submit!');
         console.log(this.form);
       },
        handleRemove(file, fileList) {
          console.log(file, fileList);
        },
        handlePreview(file) {
          console.log(file);
        },
        handleSuccess(response, file, fileList) {
          console.log(response);
          console.log(file);
          console.log(fileList);
        }
     }
  }
</script>

<style lang="css">
  input[type="file"] {
      display: none;
  }
</style>
