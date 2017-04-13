<template lang="html">
  <div class="row">
    <div class="col-md-12 ">
    <div class="col-md-4">
      <div class="ibox">
        <div class="ibox-content">
            <div class="row m-b-sm m-t-sm">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" placeholder="请输入申请人" class="input-sm form-control"> <span class="input-group-btn">
                            <button type="button" class="btn btn-sm btn-primary"> 搜索</button> </span>
                    </div>
                </div>
            </div>
            <table class="table table-hover">
                <tbody>
                <template v-if="applicats.length > 0">
                  <tr v-for="applicat in applicats" :class="{'current' : $route.params.id == applicat.id}">
                      <td class="project-status">
                          <span class="label label-primary">{{applicat.status}}
                      </td>
                      <td class="project-title">
                          <router-link :to="'/audit/details/'+applicat.id">
                            {{applicat.mechanism}} - {{applicat.type}}
                          </router-link>
                          <br/>
                          <small>创建于 {{applicat.created_at}}</small>
                      </td>
                  </tr>
                </template>
                  <h3 class="none text-center" v-if="applicats.length == 0">{{ $t('el.page.nothing') }}</h3>
                  </tbody>
              </table>
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
    <template v-if="applicats.length > 0">
    <div class="col-md-8 col-sm-12 col-xs-12"  v-if="applicat">
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
                              <div class="col-sm-12">
                                  <div class="panel blank-panel">
                                      <div class="panel-heading">
                                          <div class="panel-options">
                                              <ul class="nav nav-tabs">
                                                  <li class="active"><a href="project_detail.html#tab-1" data-toggle="tab">详情</a>
                                                  </li>
                                                  <li class=""><a href="project_detail.html#tab-2" data-toggle="tab">审核</a>
                                                  </li>
                                              </ul>
                                          </div>
                                      </div>

                                      <div class="panel-body">

                                          <div class="tab-content">
                                              <div class="tab-pane active" id="tab-1">
                                                <!-- <div class="row"> -->
                                                        <dl class="dl-horizontal" v-if="applicat">
                                                          <dt>状态：</dt>
                                                          <dd><span class="label label-primary">{{applicat.status}}</span></dd>
                                                            <dt>申请人：</dt>
                                                            <dd>{{applicat.user}}</dd>
                                                            <dt>申请机构：</dt>
                                                            <dd>{{applicat.mechanism}}</dd>
                                                            <dt>负责人：</dt>
                                                            <dd>{{applicat.principal}}</dd>
                                                            <dt>负责人联系方式：</dt>
                                                            <dd>{{applicat.mobile}}</dd>
                                                            <dt>申请类型：</dt>
                                                            <dd>{{applicat.type}}</dd>
                                                            <dt>参与人数：</dt>
                                                            <dd>{{applicat.number}}</dd>
                                                            <dt>借用时段：</dt>
                                                            <dd>{{applicat.startTime}} - {{applicat.endTime}}</dd>
                                                            <br/>
                                                            <dt>联合机构：</dt>
                                                            <dd>
                                                              {{applicat.agency}}
                                                            </dd>
                                                            <br/>
                                                            <dt>申请缘由：</dt>
                                                            <dd>
                                                              {{applicat.reason}}
                                                            </dd>
                                                            <br/>
                                                            <dt>物资申请：</dt>
                                                            <dd>{{applicat.goods}}</dd>
                                                            <br/>
                                                            <dt>活动策划：</dt>
                                                            <dd v-if="applicat.files">
                                                              <li  v-for="file in JSON.parse(applicat.files)">
                                                                <a :href="file.url" :title="file.original_name" target="_blank" class="filename">
                                                                  <i class="ion-document"></i>
                                                                  {{file.original_name}}
                                                                </a>
                                                              </li>
                                                            </dd>
                                                            <!-- <dd v-else>
                                                                <h3 class="none text-center" v-if="items.length == 0">{{ $t('el.page.nothing') }}</h3>
                                                            </dd> -->
                                                        </dl>
                                                <!-- </div> -->
                                              </div>
                                              <div class="tab-pane" id="tab-2">
                                                <div class="feed-activity-list">
                                                    <template v-if="applicat.opinions">
                                                      <div class="feed-element" v-for="opinion in applicat.opinions.data">
                                                          <a href="#" class="pull-left">
                                                            <img class="img-circle" :src="opinion.user.avatar" alt="">
                                                          </a>
                                                          <div class="media-body">
                                                              <small class="pull-right">{{ opinion.create_at }}</small>
                                                              <strong>{{opinion.user.name }}</strong>
                                                              <div class="well">
                                                                {{ opinion.opinion }}
                                                              </div>
                                                          </div>
                                                      </div>
                                                    </template>
                                                    <div class="feed-element" v-if="is_opinion">
                                                        <div class="media-body ">
                                                          <el-input type="textarea" v-model="form.opinion" placeholder="您的意见" class="opinion"></el-input>
                                                          <div class="">
                                                              <el-radio-group v-model="form.radio">
                                                                 <el-radio-button label="通过"></el-radio-button>
                                                                 <el-radio-button label="不通过"></el-radio-button>
                                                               </el-radio-group>
                                                                 <button type="submit" @click="submit" class="btn btn-info btn-sm" :class="{disabled: !(form.radio&&form.opinion)}"  style="float: right">发表</button>
                                                         </div>
                                                        </div>
                                                    </div>
                                                </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
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
  export default {
      data() {
          return {
              applicats: [],
              applicat: {},
              fileList: {},
              is_opinion: true,
              id: '',
              form: {
                  radio: '',
                  opinion: ''
              },
              total: 0,
              totalPage: 0,
              currentPage: 0,
              pageSize: 10,
          }
      },
      created() {
          this.currentPage = this.$route.query.page;
          this.pageSize = parseInt(this.$route.query.pageSize);
          this.loadData();
          this.loadCurrentData();
      },
      watch: {
          '$route.params': 'currentApplicat'
      },
      computed: {
          ...mapState([
              'isPhone',
              'user'
          ])
      },
      methods: {
          ...mapMutations([
              'DELETE_NOTIFICAT'
          ]),
          loadData() {
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
                console.log(response);
                      this.applicats = response.data.data
                      this.totalPage = response.data.meta.pagination.total_pages
                      this.currentPage = response.data.meta.pagination.current_page
                      this.total = response.data.meta.pagination.total
                  })
          },
          loadCurrentData() {
            //请求当前id对应的详细资源
            axios.get('/api/applicat/' + this.$route.params.id + '?include=opinions').then( response => {
              console.log(response);
                this.applicat = response.data.data;
                this.DELETE_NOTIFICAT(this.applicat.id);
                this.isOpinion();
            })
          },
          currentApplicat() {
              //当前申请表详细信息
              for (var i = 0; i < this.applicats.length; i++) {
                  if(this.applicats[i].id == this.$route.params.id) {
                      this.applicat = this.applicats[i];
                      this.DELETE_NOTIFICAT(this.applicats[i].id);
                      this.isOpinion();
                      break;
                  }
              }
              this.form.opinion = '';
              this.form.radio = '';
          },
          isOpinion() {
              //判断是否可发表意见
              let opinions = this.applicat.opinions.data;
              for (var i = 0; i < opinions.length; i++) {
                  if(opinions[i].user.id == this.user.id) {
                      this.is_opinion = false;
                      return;
                  }
              }
              this.is_opinion = true;
          },
          // getApplicat() {
          //     axios.get('/api/applicat/ ' + this.$route.params.id);
          // },
          // getApplicatByTypeId() {
          //     axios.get('/api/applicat?type=')
          // },
          submit() {
              this.form.applicat_id = this.$route.params.id;
              axios.post('/api/opinion/', this.form).then( response => {
                  toastr.success('您的审核以及意见已提交');
                  this.$router.push('/audit');
              }, error => {
                  toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
              })
          },
          handleSizeChange(val) {
              this.pageSize = val;
              this.loadData();
         },
         handleCurrentChange(val) {
             //currentPage 改变时会触发
             this.currentPage = val;
             this.loadData();
         },
      }
  }
</script>

<style scoped>
   a{
     text-decoration:none;
     text-decoration-line: none;
   }
  .panel-body .dl-horizontal li {
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
  .panel-body .dl-horizontal li:hover {
      background-color: #eef1f6;
  }
  .panel-body .dl-horizontal .filename {
      color: #48576a;
      display: block;
      text-overflow: ellipsis;
      overflow: hidden;
      white-space: nowrap;
      padding-left: 5px
  }
  .tab-pane .opinion {
      margin-bottom: 15px;
  }
  .tab-pane .media-body .el-radio-button__inner {
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

</style>
