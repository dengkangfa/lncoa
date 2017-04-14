<template lang="html">
  <div class="row">
      <div class="ibox">
          <div class="ibox-title">
              <h5>{{ $t('el.page.applicat') }}</h5>
          </div>
          <div class="ibox-content">
              <div class="row">
                <el-form ref="form"
                 :model="form"
                 :label-position="isPhone ? 'top' : 'right'"
                 class="col-md-8 col-md-offset-2"
                 label-width="80px">
                  <el-form-item label="负责人">
                    <el-col :span="8" style="min-width:90px">
                      <el-input v-model="form.principal" ></el-input>
                    <el-col>
                  </el-form-item>
                  <el-form-item label="联系方式">
                    <el-col :span="8" style="min-width:90px">
                        <el-input v-model.number="form.mobile" ></el-input>
                    <el-col>
                  </el-form-item>
                  <el-form-item label="申请机构">
                    <el-select v-model="form.mechanism_id" allow-create filterable placeholder="请选择">
                      <el-option
                        v-for="item in options"
                        :label="item.name"
                        :value="item.id"
                        >
                      </el-option>
                    </el-select>
                  </el-form-item>
                  <el-form-item label="参与人数">
                    <el-col :span="5" style="min-width:90px">
                      <el-input-number
                       v-model.number="form.number"
                       @change="handleChange"
                       :min="1"
                       :max="3000"
                      >
                      </el-input-number>
                    </el-col>
                  </el-form-item>
                  <el-form-item label="申请类型">
                    <el-cascader
                      v-model="form.type_id"
                      placeholder="选择申请类型"
                      :options="types"
                      filterable
                      :show-all-levels="false"
                      :props=props
                      :clearable="true"
                    ></el-cascader>
                  </el-form-item>
                  <el-form-item label="借用时段">
                    <!-- <el-col :span="15">
                      <el-date-picker
                       type="datetimerange"
                       placeholder="开始时间-结束时间"
                       v-model="form.date"
                       style="width: 100%;"
                       format="yyyy-MM-dd"
                       >
                       </el-date-picker>
                    </el-col> -->
                    <el-col :span="11">
                      <el-date-picker
                         type="datetime"
                         placeholder="开始时间"
                         :picker-options="pickerOptions"
                         v-model="form.startTime"
                         style="width: 100%;"></el-date-picker>
                    </el-col>
                    <el-col class="line" :span="2" style="text-align: center;">-</el-col>
                    <el-col :span="11">
                      <el-date-picker type="datetime" placeholder="结束时间" v-model="form.endTime" style="width: 100%;"></el-date-picker>
                    </el-col>
                  </el-form-item>
                  <el-form-item label="联合机构">
                    <el-switch on-text="有" off-text="无" v-model="form.unite"></el-switch>
                  </el-form-item>
                  <el-form-item label="机构名称" v-if="form.unite">
                    <el-input type="textarea" autosize v-model="form.agency" ></el-input>
                  </el-form-item>

                  <el-form-item label="申请缘由">
                    <el-input type="textarea" v-model="form.reason"></el-input>
                  </el-form-item>
                  <el-form-item label="物资申请">
                    <el-input type="textarea"
                    v-model="form.goods"
                    placeholder="如果需要申请物资，请描述相关物资名称以及申请的数量"
                    ></el-input>
                  </el-form-item>
                  <el-form-item label="活动策划">
                    <el-upload
                      ref="upload"
                      class="upload-demo"
                      action="/api/applicat/file"
                      accept="application/msword,application/msexcel,application/pdf"
                      :headers="headers"
                      :on-preview="handlePreview"
                      :on-remove="handleRemove"
                      :on-success="handleSuccess"
                      :before-upload="handleUpload"
                      :file-list="form.fileList">
                      <el-button size="small" type="primary">点击上传</el-button>
                      <!-- <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div> -->
                      <div slot="tip" class="el-upload__tip">
                        <span style="line-height: 15px">上传活动策划,会提高申请的通过率哦!</span>
                      </div>
                    </el-upload>
                  </el-form-item>
                  <el-form-item>
                    <el-button type="primary" @click="onSubmit">立即申请</el-button>
                  </el-form-item>
                </el-form>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  import server from '../../config/api'
  import { stack_error } from '../../config/helper.js'

  export default {
    data() {
       return {
         form: {
           principal: '',
           mobile: '',
           mechanism_id: '',
           number: 1,
           startTime: '',
           endTime: '',
           agency: '',
           unite: false,
           type_id: [],
           reason: '',
           goods: '',
           fileList: [],
         },
         labelPosition: 'left',
         headers: {},
         types: [],
         props: {
             value: 'id',
             label: 'name',
             disabled: 'disabled'
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
     computed: {
         ...mapState([
             'isPhone'
         ]),
     },
     methods: {
       onSubmit() {
         this.form.startTime = this.formatDataTime(this.form.startTime);
         this.form.endTime = this.formatDataTime(this.form.endTime);
         axios.post(server.api.applicat, this.form).then( response => {
            toastr.success('申请已提交,结果将发到您邮箱,请注意查看!');
            // this.$router.push('/applicat-manage');
         }, error => {
            stack_error(error.response.data)
         })
       },
        handleRemove(file, fileList) {
          //删除文件
          let path = file.response.relative_url,vm = this;
          axios.post('/api/file/delete', { path: path.substring(path.indexOf("/")+1)})
          .then( response => {
              vm.form.fileList = fileList;
          }, error => {
              console.log(error);
          })
        },
        handlePreview(file) {
          // console.log(file);
        },
        handleSuccess(response, file, fileList) {
          //上传文件
          if(response.success){
              this.form.fileList.push(file);
          }else{
            stack_error(response.errors);
            fileList.splice(-1,1);
          }
        },
        handleChange(val) {
          // console.log(val);
        },
        handleUpload(file) {
          //上传之前的回调函数
          let fileList = this.form.fileList;
          let flag = true;
          for (let i = 0; i < fileList.length; i++) {
              if(this.fileEqual(fileList[i].raw,file)){
                flag = false;
                break;
              }
          }
          return flag;
        },
        fileEqual(f1, f2) {
            //判断用户是否重复上传相同的文件
            if(f1.lastModified === f2.lastModified &&
               f1.name === f2.name &&
               f1.size === f2.size &&
               f1.type === f2.type){
                return true
            }
        },
        pickerOptions: {
         disabledDate(time) {
           return time.getTime() < Date.now() - 8.64e7;
         }
       },
       formatDataTime (date) {
         if(date.getFullYear){
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            m = m < 10 ? ('0' + m) : m;
            var d = date.getDate();
            d = d < 10 ? ('0' + d) : d;
            var h = date.getHours();
            var minute = date.getMinutes();
            minute = minute < 10 ? ('0' + minute) : minute;
            return y + '-' + m + '-' + d+' '+h+':'+minute;
          }
          return date;
      },
     }
  }
</script>

<style lang="css">
  input[type="file"] {
      display: none;
  }
  .upload-demo {
      line-height: 0;
  }
</style>
