<template lang="html">
  <div class="row">
          <div class="wrapper wrapper-content animated fadeInUp">
              <div class="ibox">
                  <div class="ibox-content">
                      <div class="row no-margin">
                          <div class="col-sm-12">
                              <div class="m-b-md">
                                  <!-- <a class="btn btn-white btn-xs pull-right" @click="update">{{ is_update ? '取消编辑' : '编辑项目'}}</a> -->
                                  <h3>{{applicat.mechanism}} - {{applicat.type}}</h3>
                              </div>
                              <dl class="dl-horizontal">
                                <span>{{$t('el.table.status')}}：<Status :status="applicat.status"></Status></span>
                              </dl>
                          </div>
                      </div>
                      <el-tabs value="detail" type="card">
                        <!-- 申请详情 -->
                       <el-tab-pane :label="$t('el.page.details')" name="detail">
                         <div class="row"  :class="{pass:applicat.status != '待审核'
                           && applicat.status != '审核中'
                           && applicat.status != '审核不通过'
                           && applicat.status != '已取消'}">
                             <div class="col-sm-5">
                                 <dl class="dl-horizontal">

                                     <dt>{{$t('el.form.principal')}}:</dt>
                                     <dd v-if="is_update">
                                         <input type="text" class="form-control no-height" v-model="applicat.principal">
                                     </dd>
                                     <dd v-else>
                                         {{applicat.principal}}
                                     </dd>
                                     <dt>{{$t('el.form.applay_type')}}:</dt>
                                     <dd v-if="is_update">
                                       {{applicat.type}}
                                     </dd>
                                     <dd v-else>
                                         {{applicat.type}}
                                     </dd>
                                     <dt>{{$t('el.form.mechanism')}}:</dt>
                                     <dd>{{applicat.mechanism}}</dd>
                                     <dt>{{$t('el.table.mobile')}}:</dt>
                                     <dd v-if="is_update">
                                         <input type="text" class="form-control no-height" v-model="applicat.mobile">
                                     </dd>
                                     <dd v-else>
                                       {{applicat.mobile}}
                                     </dd>
                                     <dt>{{$t('el.form.participate_number')}}:</dt>
                                     <dd>{{applicat.number}}</dd>
                                 </dl>
                             </div>
                             <div class="col-sm-7" id="cluster_info">
                                 <dl class="dl-horizontal">

                                     <dt>{{$t('el.table.latest_update')}}：</dt>
                                     <dd>{{applicat.updated_at}}</dd>
                                     <dt>{{$t('el.table.created_at')}}：</dt>
                                     <dd>{{applicat.created_at}}</dd>
                                     <dt>{{$t('el.form.borrow_period')}}：</dt>
                                     <dd class="project-people">
                                       {{applicat.startTime}} - {{applicat.endTime}}
                                     </dd>
                                 </dl>
                             </div>
                             <!-- <div class="row">
                               <div class="col-sm-12">
                                 <dl class="dl-horizontal">
                                   <dt>当前进度</dt>
                                   <dd>
                                     <div class="progress progress-striped active m-b-sm">
                                       <div style="width: 60%;" class="progress-bar"></div>
                                     </div>
                                     <small>当前已完成项目总进度的 <strong>60%</strong></small>
                                   </dd>
                                 </dl>
                               </div>
                             </div> -->
                             <dd class="col-sm-12 textarea">
                                 <dl class="dl-horizontal">
                                     <dt>{{$t('el.form.applay_unite')}}：</dt>
                                     <dd v-if="is_update">
                                         <textarea class="form-control" rows="3">{{applicat.agency}}</textarea>
                                     </dd>
                                     <dd v-else>
                                       {{applicat.agency}}
                                     </dd>
                                     <dt>{{$t('el.form.applay_reason')}}：</dt>
                                     <dd v-if="is_update">
                                         <textarea class="form-control" rows="3">{{applicat.reason}}</textarea>
                                     </dd>
                                     <dd v-else>
                                       {{applicat.reason}}
                                     </dd>
                                     <dt>{{$t('el.form.goods')}}：</dt>
                                     <dd v-if="is_update">
                                         <textarea class="form-control" rows="3">{{applicat.goods}}</textarea>
                                     </dd>
                                     <dd v-else>
                                       {{applicat.goods}}
                                     </dd>
                                     <dt>{{$t('el.form.activity_planning')}}：</dt>
                                     <dd v-if="is_update">
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
                                           :file-list="fileList">
                                           <el-button size="small" type="primary">{{$t('el.form.upload_btn')}}</el-button>
                                       </el-upload>
                                     </dd>
                                     <dd v-else>
                                       <li  v-for="file in fileList">
                                         <a :href="file.url" :title="file.original_name" target="_blank" class="filename">
                                           <i class="ion-document"></i>
                                           {{file.original_name}}
                                         </a>
                                       </li>
                                     </dd>
                                 </dl>
                             </dd>
                         </div>
                       </el-tab-pane>
                       <!-- 审核员意见 -->
                       <el-tab-pane v-if="opinions.length" :label="$t('el.page.opinion')" name="second">
                           <div class="feed-element" v-for="opinion in opinions">
                               <a href="#" class="pull-left">
                                 <img class="img-circle" :src="opinion.user.avatar" alt="">
                               </a>
                               <div class="media-body">
                                   <small class="pull-right">{{ opinion.created_at }}</small>
                                   <strong>{{opinion.user.name }}</strong>
                                   <div class="well">
                                     {{ opinion.opinion }}
                                     <li  v-for="file in JSON.parse(opinion.files)" class="pull-right" style="margin-top: 20px;">
                                       <a :href="file.url" :title="file.original_name" target="_blank" class="filename">
                                         <i class="ion-document"></i>
                                         {{file.original_name}}
                                       </a>
                                     </li>
                                   </div>
                               </div>
                               <hr>
                           </div>
                       </el-tab-pane>
                       <el-tab-pane v-if="show" :label="$t('el.page.appraisal')">
                         <div class="feed-element">
                             <a href="#" class="pull-left">
                               <img class="img-circle" :src="appraisal.user.avatar" alt="">
                             </a>
                             <div class="media-body">
                                 <small class="pull-right">{{ appraisal.created_at }}</small>
                                 <strong>{{appraisal.user.name }}</strong>
                                 <div class="well">
                                   {{ appraisal.appraisal }}
                                 </div>
                             </div>
                             <hr>
                         </div>
                       </el-tab-pane>
                      <!-- END审核员意见 -->
                     </el-tabs>

                  </div>
              </div>
          </div>
      </div>
</template>

<script>
  import Status from '../../components/Status'
  export default {
      data() {
          return {
              applicat: [],
              fileList: {},
              is_update: false,
              headers: {},
              opinions: [],
              appraisal: {}
          }
      },
      created() {
          //将当前用户令牌添加到请求头
          this.headers = {
              'Authorization': 'Bearer ' + this.$store.state.access_token
          }
          //请求当前id对应的详细资源
          axios.get('/api/applicat/' + this.$route.params.id + '?include=opinions,appraisal').then( response => {
              this.applicat = response.data.data;
              this.opinions = response.data.data.opinions.data;
              this.appraisal = response.data.data.appraisal ? response.data.data.appraisal.data : {};
              this.fileList = JSON.parse(this.applicat.files);
          })
      },
      computed: {
          show() {
              return !$.isEmptyObject(this.appraisal);
          }
      },
      components: {
          Status
      },
      methods: {
          update() {
              this.is_update = !this.is_update;
          },
          handleRemove(file, fileList) {
            //删除文件
            let path = file.response.relative_url,vm = this;
            axios.post('/api/file/delete', { path: path.substring(path.indexOf("/")+1)})
            .then( response => {
                vm.fileList = fileList;
            }, error => {
                if ((typeof error.response.data.error !== 'string') && error.response.data.error) {
                    toastr.error(error.response.data.error.message)
                } else {
                    toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                }
            })
          },
          handleSuccess(response, file, fileList) {
            //上传文件
            if(response.success){
                this.fileList.push(file);
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
                fileList = fileList[i].raw ? fileList[i].raw : fileList[i]
                if(this.fileEqual(fileList,file)){
                  flag = false;
                  break;
                }
            }
            return flag;
          },
          fileEqual(f1, f2) {
              //判断用户是否重复上传相同的文件
              if(f1.original_name === f2.original_name &&
                 f1.size === f2.size &&
                 f1.ime === f2.ime){
                  return true
              }
          },
      }
  }
</script>

<style lang="css" scoped>
    a:hover{
       text-decoration:none;
     }
    .wrapper .dl-horizontal li {
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
    .wrapper .dl-horizontal li:hover {
        background-color: #eef1f6;
    }
    .wrapper .dl-horizontal .filename {
        color: #48576a;
        display: block;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        padding-left: 5px
    }
    .row .no-margin {
        margin: 0px;
    }
    .dl-horizontal .no-height {
        height: 25px;
        padding: 0 12px;
    }
    .textarea .dl-horizontal dd {
        margin-bottom: 15px;
    }
    @media(min-width:768px) {
      .pass {
          background-image: url(http://lncoa.app/images/pass.png);
          background-repeat: no-repeat;
          background-position: center;
          background-size: auto;
      }
    }
    .feed-element img.img-circle {
        width:38px;
        height: 38px;
        border-radius: 50%;
    }
    .feed-element hr {
         margin-top: 0;
         margin-bottom: 10px;
    }
    .feed-element > .pull-left {
        margin-right: 10px;
    }

</style>
