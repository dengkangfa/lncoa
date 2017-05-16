<template lang="html">
  <div class="row" id="review-details">
    <div class="col-md-12 ">
      <!-- 申请列表 -->
    <div class="col-md-4 col-xs-12 apply-list">
      <div class="ibox">
        <div class="ibox-content">
          <!-- 搜索控件 -->
            <div class="row" style="margin-bottom: 10px">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" v-model="keyWord" :placeholder="$t('el.form.review_filter_placeholder')" class="input-sm form-control"> <span class="input-group-btn">
                            <button type="button" class="btn btn-sm btn-primary"> {{ $t('el.page.search') }}</button> </span>
                    </div>
                </div>
            </div>
            <!-- 搜索控件END -->
            <!-- 申请列表 -->
            <table class="table table-hover" v-loading="loading">
                <tbody>
                <template v-if="applicatlist.length > 0">
                  <tr v-for="applicat in applicatlist" :class="{'current' : $route.params.id == applicat.id}">
                      <td class="project-status">
                          <Status :status="applicat.status"></Status>
                      </td>
                      <td class="project-title">
                          <router-link :to="'/review/details/'+applicat.id">
                            {{applicat.mechanism}} - {{applicat.type}}
                          </router-link>
                          <br/>
                          <small>{{ $t('el.table.created_at') + " " }}<timeInterval :date="applicat.created_at"></timeInterval></small>
                      </td>
                  </tr>
                </template>
                  <h3 class="none text-center" v-if="applicats.length == 0">{{ $t('el.page.nothing') }}</h3>
                  </tbody>
              </table>
              <!-- 申请列表END -->
              <nav class="text-center">
                <div v-if="applicats.length < 1"></div>
                <el-pagination
                v-else
                :small="true"
                @current-change="handleCurrentChange"
                :current-page="currentPage"
                :layout="'prev, pager, next'"
                :total="total">
              </el-pagination>
            </nav>
          </div>
      </div>
    </div>
    <!-- 申请列表END -->
    <template v-if="applicats.length > 0">
    <div class="col-md-8 col-xs-12"  v-if="show">
              <div class="wrapper wrapper-content animated fadeInUp">
                  <div class="ibox">
                      <div class="ibox-content">
                          <div class="row">
                              <div class="col-sm-12">
                                  <div class="m-b-md">
                                      <!-- <a href="project_detail.html#" class="btn btn-white btn-xs pull-right">编辑项目</a> -->
                                      <h3>{{applicat.mechanism}} - {{applicat.type}}</h3>
                                  </div>
                              </div>
                          </div>

                          <div class="row m-t-sm">
                            <el-tabs type="card" v-model="activeName" class="tabs"  @tab-click="forwardClick">
                                <el-tab-pane :label="$t('el.page.details')" name="detail">
                                  <dl class="dl-horizontal" v-if="applicat">
                                    <dt>{{$t('el.table.status')}}：</dt>
                                      <dd><Status :statusClass="false" :status="applicat.status"></Status></dd>
                                      <dt>{{$t('el.table.applicat_user')}}：</dt>
                                      <dd>{{applicat.user}}</dd>
                                      <dt>{{$t('el.form.mechanism')}}：</dt>
                                      <dd>{{applicat.mechanism}}</dd>
                                      <dt>{{$t('el.form.principal')}}：</dt>
                                      <dd>{{applicat.principal}}</dd>
                                      <dt>{{$t('el.table.mobile')}}：</dt>
                                      <dd>{{applicat.mobile}}</dd>
                                      <dt>{{$t('el.form.applay_type')}}：</dt>
                                      <dd>{{applicat.type}}</dd>
                                      <dt>{{$t('el.form.participate_number')}}：</dt>
                                      <dd>{{applicat.number}}</dd>
                                      <dt>{{$t('el.form.borrow_period')}}：</dt>
                                      <dd>{{applicat.startTime}} - {{applicat.endTime}}</dd>
                                      <br/>
                                      <dt>{{$t('el.form.applay_unite')}}：</dt>
                                      <dd>
                                        {{applicat.agency}}
                                      </dd>
                                      <br/>
                                      <dt>{{$t('el.form.applay_reason')}}：</dt>
                                      <dd>
                                        {{applicat.reason}}
                                      </dd>
                                      <br/>
                                      <dt>{{$t('el.form.goods')}}：</dt>
                                      <dd>{{applicat.goods}}</dd>
                                      <br/>
                                      <dt>{{$t('el.form.activity_planning')}}：</dt>
                                      <dd v-if="applicat.files">
                                        <li  v-for="file in JSON.parse(applicat.files)">
                                          <a :href="file.url" v-show="file" :title="file.original_name" target="_blank" class="filename">
                                            <i class="ion-document"></i>
                                            {{file.original_name}}
                                          </a>
                                        </li>
                                      </dd>
                                  </dl>
                                </el-tab-pane>
                                <!-- 审核面板 -->
                                <el-tab-pane :label="$t('el.page.review')" name="second">
                                  <div class="feed-activity-list">
                                    <!-- 审核意见 -->
                                      <template v-if="applicat.opinions">
                                        <div class="feed-element" v-for="opinion in applicat.opinions.data">
                                            <a href="#" class="pull-left">
                                              <img class="img-circle" :src="opinion.user.avatar" alt="">
                                            </a>
                                            <div class="media-body">
                                                <small class="pull-right">{{ opinion.created_at }}</small>
                                                <strong>{{opinion.user.name }}</strong>
                                                <div class="well">
                                                  {{ opinion.opinion }}
                                                </div>
                                            </div>
                                        </div>
                                      </template>
                                      <!-- 审核意见END -->
                                      <!-- 审核控件 -->
                                      <div class="feed-element">
                                          <div class="media-body ">
                                            <form  @submit.prevent="submit">
                                              <el-input type="textarea" v-model="form.opinion" :placeholder="$t('el.form.opinion_placeholder')" class="opinion"></el-input>
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
                                                  <span style="line-height: 15px">{{ $t('el.form.review_tip') }}</span>
                                                </div>
                                              </el-upload>
                                              <div class="">
                                                  <el-radio-group v-model="form.radio">
                                                     <el-radio-button :label="$t('el.form.pass')"></el-radio-button>
                                                     <el-radio-button :label="$t('el.form.no_pass')"></el-radio-button>
                                                   </el-radio-group>
                                                     <button type="submit" class="btn btn-info btn-sm" :disabled="!(form.radio&&form.opinion)"  style="float: right">{{$t('el.form.submit')}}</button>
                                             </div>
                                           </form>
                                          </div>
                                      </div>
                                      <!-- 审核控件END -->
                                  </div>
                                </el-tab-pane>
                                <!-- 审核面板END -->
                                <!-- 转发面板 -->
                                <el-tab-pane :label="$t('el.page.forwarding')" name="forward" class="forward">
                                    <el-form label-position="top" :model="forward_form" label-width="80px">
                                        <el-form-item :label="$t('el.form.recipient')">
                                          <select v-if="isPhone" v-model="forward_form.role_id" class="form-control" :placeholder="$t('el.form.recipient_placeholder')">
                                            <option v-for="role in roles" :label="role.display_name" :value="role.id">
                                            </option>
                                          </select>
                                          <el-select v-else v-model="forward_form.role_id" :placeholder="$t('el.form.recipient_placeholder')">
                                            <el-option v-for="role in roles" :label="role.display_name" :value="role.id">
                                            </el-option>
                                          </el-select>
                                          <!-- -
                                          <el-select v-model="forward_form.user_id" :filterable="true" :clearable="true" placeholder="请选择具体用户">
                                            <el-option v-for="user in users" :label="user.name" :value="user.id">
                                            </el-option>
                                          </el-select> -->
                                        </el-form-item>
                                        <el-input type="textarea" v-model="forward_form.opinion" :placeholder="$t('el.form.opinion_placeholder')" class="opinion"></el-input>
                                         <el-form-item>
                                           <el-button type="primary" class="btn btn-info btn-sm" :disabled="!forward_form.role_id" @click="forward" style="float:right">{{ $t('el.form.submit') }}</elbutton>
                                         </el-form-item>
                                    </el-form>
                                </el-tab-pane>
                                <!-- 转发面板END -->
                                <el-tab-pane :label="$t('el.page.approval')" v-if="applicat.status == '审核通过'">
                                    <div>
                                      {{$t('el.page.borrow_period')}}: {{applicat.startTime}} - {{applicat.endTime}}
                                    </div>
                                    <button type="button" class="btn btn-info" name="button" @click="approval(applicat)">{{$t('el.page.approval')}}</button>
                                </el-tab-pane>
                                <el-tab-pane :label="$t('el.page.approval')" v-if="applicat.status == '进行中'">
                                    <div class="">
                                      {{applicat.endTime}}
                                    </div>
                                    <button type="button" class="btn btn-info" name="button" @click="end(applicat)">{{$t('el.page.end')}}</button>
                                </el-tab-pane>
                                <!-- 评价面板 -->
                                <el-tab-pane :label="$t('el.page.appraisal')" v-if="applicat.status == '待评价'">
                                  <div class="block">
                                    <!-- 反馈输入框 -->
                                    <el-input type="textarea" v-model="appraisal_form.appraisal" placeholder="$t('el.page.feedback')"></el-input>
                                    <!-- 反馈输入框END -->
                                    <!-- <el-form-item label="评分"> -->
                                      <el-rate
                                        v-model="appraisal_form.score"
                                        show-text
                                        :colors="['#99A9BF', '#F7BA2A', '#FF9900']">
                                      </el-rate>
                                    <!-- </el-form-item> -->

                                    <button type="button" class="btn btn-info pull-right" :disabled="!appraisalCanSubmit" name="button" @click="appraisal(applicat)">{{$t('el.form.submit')}}</button>
                                  </div>
                                </el-tab-pane>
                                <!-- 评价面板END -->
                              </el-tabs>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </template>
      </div>
    </div>
</template>

<script>
  import { mapState, mapMutations } from 'vuex'
  import Status from '../../components/Status'
  import TimeInterval from '../../components/TimeInterval'
  import server from '../../config/api.js'
  export default {
      data() {
          return {
              applicats: [],
              applicat: {},
              // is_opinion: true,
              id: '',
              form: {
                  radio: '',
                  opinion: '',
                  fileList: [],
              },
              forward_form: {
                  opinion: null,
                  role_id: null
              },
              appraisal_form: {
                  appraisal: null,
                  score: null
              },
              total: 0,
              totalPage: 0,
              currentPage: 0,
              pageSize: 10,
              headers: {},
              fileList: [],
              roles: null,
              users: null,
              keyWord: '',
              rate: null,
              activeName: 'detail',
              loading: false
          }
      },
      created() {
          this.headers = {
              'Authorization': 'Bearer ' + this.$store.state.access_token,
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
          this.currentPage = this.$route.query.page;
          this.pageSize = parseInt(this.$route.query.pageSize);
          this.loadData();
          if(this.$route.fullPath != '/review/details'){
            this.loadCurrentData();
          }
      },
      watch: {
          '$route.params': 'currentApplicat'
      },
      components: {
          Status,
          TimeInterval
      },
      computed: {
          ...mapState([
              'isPhone',
              'user'
          ]),
          //过滤后的申请列表
          applicatlist:function(){
              let vm = this;
              return this.applicats.filter(function(item){
                  if((item.mechanism+'-'+item.type)
                  .indexOf(vm.keyWord) >= 0 || item.principal
                  .indexOf(vm.keyWord) >= 0) {
                      return true;
                  }
                  return false;
              })
          },
          appraisalCanSubmit() {
              return this.appraisal_form.appraisal != null || this.appraisal_form.score != null;
          },
          show() {
              return !$.isEmptyObject(this.applicat);
          }
      },
      methods: {
          ...mapMutations([
              'DELETE_NOTIFICAT'
          ]),
          loadData() {
              this.loading = true;
              //加载数据
              this.id = this.$route.params.id;
              let url = '/api/applicats?include=opinions';

              if (this.currentPage) {
                  let page = ''
                  if (url.indexOf('?') != -1) {
                      page = '&page='
                  } else {
                      page = '?page='
                  }
                  url = url + page + this.currentPage;
                  this.$router.push('?page=' + this.currentPage)
              }

              if (this.pageSize > 10) {
                  let pageSize = ''
                  if (url.indexOf('?') != -1) {
                      pageSize = '&pageSize='
                  } else {
                      pageSize = '?pageSize='
                  }
                  url = url + pageSize + this.pageSize;
                  this.$router.push('?pageSize=' + this.pageSize)
              }
              axios.get(url).then(response => {
                      this.applicats = response.data.data
                      this.totalPage = response.data.meta.pagination.total_pages
                      this.currentPage = response.data.meta.pagination.current_page
                      this.total = response.data.meta.pagination.total
                      this.loading = false;
                  })
          },
          loadCurrentData() {
            //请求当前id对应的详细资源
            axios.get('/api/applicat/' + this.$route.params.id + '?include=opinions').then( response => {
                this.applicat = response.data.data;
                //清除通知
                this.DELETE_NOTIFICAT(this.applicat.id);
                // this.isOpinion();
            })
          },
          currentApplicat() {
              //当前申请表详细信息
              for (var i = 0; i < this.applicats.length; i++) {
                  if(this.applicats[i].id == this.$route.params.id) {
                      this.applicat = this.applicats[i];
                      //清除通知
                      this.DELETE_NOTIFICAT(this.applicats[i].id);
                      // this.isOpinion();
                      break;
                  }
              }
              this.form.opinion = '';
              this.form.radio = '';
          },
          // isOpinion() {
          //     //判断是否可发表意见
          //     let opinions = this.applicat.opinions.data;
          //     for (var i = 0; i < opinions.length; i++) {
          //         if(opinions[i].user.id == this.user.id) {
          //             this.is_opinion = false;
          //             return;
          //         }
          //     }
          //     this.is_opinion = true;
          // },

          //条数更改事件回调
          handleSizeChange(val) {
              this.pageSize = val;
              this.loadData();
         },
         //当前页码更改事件回调
         handleCurrentChange(val) {
             //currentPage 改变时会触发
             this.currentPage = val;
             this.loadData();
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
         forwardClick(el) {
           if(el.name == 'forward' && !this.roles){
             axios.get(server.api.roles).then( response => {
                this.roles = response.data.data;
             })
           }
         },
         roleChange() {
            this.forward_form.user_id = '';
            axios.get('/api/role/' + this.forward_form.role_id + '/users').then( response => {
                this.users = response.data.data;
            })
         },
         // 提交审核
         submit() {
             let vm = this;
             vm.form.applicat_id = vm.$route.params.id;
             vm.form.fileList = vm.fileList;
             axios.post('/api/opinion/', vm.form).then( response => {
                 toastr.success(vm.$t('el.notification.review_success'));
                 this.removeCurrentApplicat()
                 vm.$router.push('/review/details');
             }, error => {
                 toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
             })
         },
         forward(applicat) {
            axios.post('/api/applicat/' + this.$route.params.id +'/forward',this.forward_form).then( response => {
                toastr.success('申请已转发!');
                this.removeCurrentApplicat()
                this.$router.push('/review/details');
            })
         },
         approval(applicat) {
             if(applicat.status == '审核通过') {
               axios.put('/api/applicat/' + this.$route.params.id +'/approval').then( response => {
                   applicat.status = '进行中';
                   for (var i = 0; i < this.applicats.length; i++) {
                       if(this.applicats[i].id == applicat.id){
                          this.applicats[i].status = '进行中';
                          continue;
                       }
                   }
                   this.activeName = 'detail';
                   toastr.success('审批成功!');
               })
             }
         },
         appraisal(applicat) {
            axios.post('/api/applicat/' + this.$route.params.id + '/appraisal',this.appraisal_form).then( response => {
                this.removeCurrentApplicat()
                this.$router.push('/review/details');
            }, error => {
                toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
            })
         },
         end(applicat) {
           axios.put('/api/applicat/' + this.$route.params.id + '/end').then( response => {
               applicat.status = '待评价';
               for (var i = 0; i < this.applicats.length; i++) {
                   if(this.applicats[i].id == applicat.id){
                      this.applicats[i].status = '待评价';
                      continue;
                   }
               }
               this.activeName = 'detail';
           }, error => {
               toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
           })
         },
         removeCurrentApplicat() {
             for (var i = 0; i < this.applicats.length; i++) {
                 if(this.applicats[i].id == this.applicat.id){
                    this.applicats.splice(i,1);
                    this.applicat = {};
                    this.fileList = [];
                    return;
                 }
             }
         }
      }
  }
</script>

<style scoped>
   a{
     text-decoration:none;
     text-decoration-line: none;
   }
   /*.row {
     margin: 0px !important;
   }*/


   #review-details div[class*="col-"] {
     position: relative;
     min-height: 1px;
     padding-left: 0;
     padding-right: 0;
   }

   #review-details .ibox {
     margin-bottom: 10px;
   }

  .tabs .dl-horizontal li {
      list-style-type: none;
      transition: all .5s cubic-bezier(.55,0,.1,1);
      font-size: 14px;
      color: #48576a;
      line-height: 1.8;
      box-sizing: border-box;
      border-radius: 4px;
      width: 100%;
      position: relative;
  }
  .tabs .dl-horizontal li:hover {
      background-color: #eef1f6;
  }
  .tabs .dl-horizontal .filename {
      color: #48576a;
      display: block;
      text-overflow: ellipsis;
      overflow: hidden;
      white-space: nowrap;
      padding-left: 5px
  }
  .tabs .media-body .upload-demo {
      padding-bottom: 15px;
  }
  .tabs .forward .el-form-item{
      margin-bottom: 10px;
  }
  .tabs .forward .opinion {
      margin-bottom: 10px;
  }
  .media-body .opinion {
      margin-bottom: 15px;
  }
  .media-body .media-body .el-radio-button__inner {
      padding: 6px 15px;
  }
  .feed-element img.img-circle {
      width:38px;
      height: 38px;
      border-radius: 50%;
  }
  .feed-element > .pull-left {
      margin-right: 10px;
  }
  .current {
      background-color: #f5f5f5;
  }

  @media (min-width: 768px){
      .apply-list {
          padding-right: 15px !important;
      }
  }

</style>
