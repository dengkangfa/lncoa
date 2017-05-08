<template lang="html">
  <div class="row">
      <div class="ibox">
          <div class="ibox-title">
              <h5><blockquote>{{ $t('el.page.applicat') }}</blockquote></h5>
          </div>
          <div class="ibox-content">
              <div class="row" style="margin-top: 15px">
                <el-form
                 :model="form"
                 :rules="rules"
                 ref="ruleForm"
                 :label-position="isPhone ? 'top' : 'right'"
                 class="col-md-8 col-md-offset-2"
                 label-width="90px">
                 <!-- 负责人 -->
                  <el-form-item :label="$t('el.form.principal')" style="color: red" prop="principal">
                    <el-col  :xs="24" :sm="8" :md="8" style="min-width:90px">
                      <el-input v-model="form.principal" ></el-input>
                    </el-col>
                  </el-form-item>
                  <!-- 负责人END -->
                  <!-- 联系方式 -->
                  <el-form-item :label="$t('el.form.mobile')" prop="mobile">
                    <el-col :xs="24" :sm="8" :md="8" style="min-width:90px">
                        <el-input v-model.number="form.mobile" ></el-input>
                    <el-col>
                  </el-form-item>
                  <!-- 联系方式END -->
                  <!-- 申请机构 -->
                  <el-form-item :label="$t('el.form.mechanism')" prop="mechanism" required>
                      <el-select v-model="form.mechanism_id" allow-create filterable :placeholder="$t('el.select.placeholder')">
                        <el-option
                          v-for="item in options"
                          :label="item.name"
                          :value="item.id"
                          >
                        </el-option>
                      </el-select>
                  </el-form-item>
                  <!-- 申请机构END -->
                  <!-- 参与人数 -->
                  <el-form-item :label="$t('el.form.participate_number')">
                    <el-col :span="5" style="min-width:90px">
                      <el-input-number
                       v-model.number="form.number"
                       :min="1"
                       :max="3000"
                      >
                      </el-input-number>
                    </el-col>
                  </el-form-item>
                  <!-- 参与人数END -->
                  <!-- 申请类型 -->
                  <el-form-item :label="$t('el.form.applay_type')" prop="type_id">
                    <el-cascader
                      v-model="form.type_id"
                      :placeholder="$t('el.form.type_placeholder')"
                      :options="types"
                      filterable
                      :show-all-levels="false"
                      :props=props
                      :clearable="true"
                      @change="typeChange"
                    ></el-cascader>
                  </el-form-item>
                  <!-- 申请类型END -->
                  <!-- 使用时段 -->
                  <el-form-item :label="$t('el.form.borrow_period')" required>
                    <template v-if="isPhone">
                      <el-col :xs="24" :sm="11" :md="11">
                        <el-form-item prop="startTime">
                          <el-date-picker
                             type="datetime"
                             :placeholder="$t('el.form.start_time')"
                             :picker-options="startPickerOptions"
                             v-model="form.startTime"
                             style="width: 100%;">
                           </el-date-picker>
                         </el-form-item>
                      </el-col>
                      <el-col class="line" :xs="24" :sm="2" :md="2" style="text-align: center;">-</el-col>
                      <el-col :xs="24" :sm="11" :md="11">
                        <el-form-item prop="endTime">
                          <el-date-picker
                            type="datetime"
                            :placeholder="$t('el.form.end_time')"
                            :picker-options="endPickerOptions"
                            v-model="form.endTime"
                            style="width: 100%;">
                           </el-date-picker>
                         </el-form-item>
                      </el-col>
                    </template>
                    <el-col :span="15" v-else>
                        <el-date-picker
                        type="datetimerange"
                        placeholder="开始时间-结束时间"
                        v-model="date"
                        style="width: 100%;"
                        >
                      </el-date-picker>
                    </el-col>
                  </el-form-item>
                  <!-- 使用时段END -->
                  <!-- 是否存在联合机构 -->
                  <el-form-item :label="$t('el.form.applay_unite')">
                    <el-switch :on-text="$t('el.form.have')" :off-text="$t('el.form.not')" v-model="form.unite"></el-switch>
                  </el-form-item>
                  <!-- 是否存在联合机构END -->
                  <!-- 联合机构 -->
                  <el-form-item :label="$t('el.form.agency')" v-if="form.unite" prop="agrncy">
                    <el-input type="textarea" autosize v-model="form.agency" ></el-input>
                  </el-form-item>
                  <!-- 联合机构END -->
                  <el-form-item :label="$t('el.form.applay_reason')" prop="reason">
                    <el-input type="textarea" v-model="form.reason"></el-input>
                  </el-form-item>
                  <el-form-item :label="$t('el.form.goods')" prop="goods">
                    <el-input type="textarea"
                    v-model="form.goods"
                    :placeholder="$t('el.form.goods_placeholder')"
                    ></el-input>
                  </el-form-item>
                  <el-form-item :label="$t('el.form.activity_planning')">
                    <el-upload
                      ref="upload"
                      class="upload-demo"
                      action="/api/applicat/file"
                      accept="application/msword,application/msexcel,application/pdf"
                      :headers="headers"
                      :on-remove="handleRemove"
                      :on-success="handleSuccess"
                      :before-upload="handleUpload"
                      :file-list="fileList">
                      <el-button size="small" type="primary">{{ $t('el.form.upload_btn') }}</el-button>
                      <!-- <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div> -->
                      <div slot="tip" class="el-upload__tip">
                        <span style="line-height: 15px">{{ $t('el.form.upload_tip') }}</span>
                      </div>
                    </el-upload>
                  </el-form-item>
                  <el-form-item>
                    <el-button type="primary" @click="onSubmit('ruleForm')">{{ $t('el.form.applay_btn') }}</el-button>
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
       var checkMobile = (role, value, callback) => {
          let regu = /^1[34578][0-9]{9}$/;
          let re = new RegExp(regu);
          if( re.test(value) ){
              callback();
          }else{
              callback(new Error('联系方式的格式不正确'));
          }
       };
       var checkMechanism = (role, value, callback) => {
          if(this.form.mechanism_id > 0) {
              callback();
          } else {
              callback(new Error('请选择申请机构'));
          }
       };
       var checkData = (role, value, callback) => {
            if(value == '') {
                callback(new Error('请选择日期'));
            } else if(this.form.startTime != ''
                        && this.form.endTime != ''
                        && this.form.startTime > this.form.endTime
                      ){
                callback(new Error('开始的日期不能大过结束日期'));
            } else {
                callback();
            }
       };
       return {
         startPickerOptions: {},
         endPickerOptions: {},
         dateTakeUp: [],
         date: [],
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
         fileList: [],
         rules: {
            principal: [
              { required: true, message: '请输入负责人名称', trigger: 'blur' },
              { min: 2, max: 5, message: '长度在 2 到 5 个字符', trigger: 'blur' }
            ],
            mobile: [
                { type: 'number', required: true, message: '请填写联系方式', trigger: 'blur' },
                { validator: checkMobile, trigger: 'blur'}
            ],
            mechanism: [
                { validator: checkMechanism, trigger: 'change'}
            ],
            type_id: [
                { type: 'array', required: true, message: '请选择申请类型', trigger: 'change'}
            ],
            startTime: [
                { validator: checkData, required: true, trigger: 'change'}
            ],
            endTime: [
                { validator: checkData, required: true, trigger: 'change'}
            ],
            reason: [
                { required: true, message: '请输入您的申请缘由', trigger: 'blur'}
            ],
            agrncy: [
                { max: 100, message: '长度不得超过100个字符', trigger: 'blur'}
            ],
            goods: [
                { max: 100, message: '长度不得超过100个字符', tigger: 'blur'}
            ]
         },
         labelPosition: 'left',
         headers: {},
         types: [],
         props: {
             value: 'id',
             label: 'name',
             disabled: 'disabled'
         },
         options:[],
       }
     },
     created() {
         //请求头(用于文件上传的请求头)
         this.headers = {
             'Authorization': 'Bearer ' + this.$store.state.access_token
         }
         //禁止开始选择日期框选择的日期
         this.startPickerOptions.disabledDate = (time) => {
           return time.getTime() < Date.now() - 8.64e7;
         };
        //禁止结束选择日期框选择的日期
         this.endPickerOptions.disabledDate = (time) => {
           return time.getTime() < Date.now() - 8.64e7 || time.getTime() < this.form.startTime;
         };
         //获取所有申请类型，并要求服务端格式化成树结构
         axios.get(server.api.type + "?structure=tree").then( response => {
             this.types = response.data;
         },error => {
             toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
         });
         //获取机构列表
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
       //提交
       onSubmit(formName) {
         let vm = this;
         //再次验证所有表单是否符合要求
         vm.$refs[formName].validate((valid) => {
            //正确执行
            if(valid) {
              //将文件列表赋值给表单对象
              vm.form.fileList = vm.fileList;
              //格式化时间
              if(vm.date.length > 0) {
                  vm.form.startTime = vm.formatDataTime(vm.date[0]);
                  vm.form.endTime = vm.formatDataTime(vm.date[1]);
              }else{
                  vm.form.startTime = vm.formatDataTime(vm.form.startTime);
                  vm.form.endTime = vm.formatDataTime(vm.form.endTime);
              }
              axios.post(server.api.applicat, vm.form).then( response => {
                 toastr.success(vm.$t('el.notification.create_applicat'));
                 //成功提交后跳转到申请管理页面
                 vm.$router.push('/applicat-manage');
              }, error => {
                  if(error.response.status == 422){
                    //表单验证有错误执行
                    stack_error(error.response.data)
                  }else{
                    toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                  }
              })
            } else {
                return false;
            }
         })
       },
        handleRemove(file, fileList) {
          //删除文件
          if(file) {
              let path = file.response.relative_url,vm = this;
              axios.post('/api/file/delete', { path: path.substring(path.indexOf("/")+1)})
              .then( response => {
                  vm.fileList = fileList;
              }, error => {
                  toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
              })
          }
        },
        handleSuccess(response, file, fileList) {
          //上传文件
          if(response.success){
              this.fileList = fileList;
              // this.form.fileList.push(file);
          }else{
              stack_error(response.errors);
              fileList.splice(-1,1);
          }
        },
        handleUpload(file) {
          //上传之前的回调函数
          let fileList = this.fileList;
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
        //当级联选择框内容改变时调用
        typeChange(el) {
            // let temp = this.types;
            // for (var i = 0; i < el.length; i++) {
            //     temp = this.currentType(temp,el[i]);
            // }
            // if(temp.date_unique) {
            //     axios.get('/api/applicat/' + temp.id + '/dateTakeUp').then( response => {
            //       console.log(response);
            //       this.dateTakeUp = response.data.data;
            //         // let startTime = response.data.data.startTime;
            //         // let entTime = response.data.data.entTime;
            //         // this.dateTakeUp = startTime + '-' + endTime;
            //     });
            // }
        },
        //获取用户当前选择的类型
        currentType(types, id) {
            for (var i = 0; i < types.length; i++) {
                if(types[i].id == id ) {
                    return types[i].children ? types[i].children : types[i];
                }
            }
        },
        pickerOptions: {
         disabledDate(time) {
           return time.getTime() < Date.now() - 8.64e7;
         }
       },
       formatDataTime (date) {
         //时间格式化
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
