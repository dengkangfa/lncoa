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
                    <tr v-for="type_applicat in type_applicats">
                        <td class="project-status">
                            <span class="label label-primary">{{type_applicat.status}}
                        </td>
                        <td class="project-title">
                            <router-link :to="'/audit/details/'+type_applicat.id">
                              {{type_applicat.mechanism}} - {{type_applicat.type}}
                            </router-link>
                            <br/>
                            <small>创建于 {{type_applicat.created_at}}</small>
                        </td>
                    </tr>
                    </tbody>
                </table>
              </div>
                </div>

    </div>
    <div class="col-md-8 col-sm-12 col-xs-12">
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
                                                            <dd>
                                                              <li  v-for="file in fileList">
                                                                <a :href="file.url" :title="file.original_name" target="_blank" class="filename">
                                                                  <i class="ion-document"></i>
                                                                  {{file.original_name}}
                                                                </a>
                                                              </li>
                                                            </dd>
                                                        </dl>
                                                <!-- </div> -->
                                              </div>
                                              <div class="tab-pane" id="tab-2">
                                                <div class="feed-activity-list">
                                                    <div class="feed-element">
                                                        <div class="media-body ">
                                                            <small class="pull-right text-navy">1天前</small>
                                                            <strong>奔波儿灞</strong> 关注了 <strong>灞波儿奔</strong>.
                                                            <br>
                                                            <small class="text-muted">54分钟前 来自 皮皮时光机</small>
                                                            <div class="actions">
                                                                <a class="btn btn-xs btn-white"><i class="fa fa-thumbs-up"></i> 赞 </a>
                                                                <a class="btn btn-xs btn-danger"><i class="fa fa-heart"></i> 收藏</a>
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
      </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            applicat: {},
            type_applicats: {},
            fileList: {},
        }
    },
    created() {
        axios.get('/api/applicat/' + this.$route.params.id + '?include=type_applicats').then( response => {
            this.applicat = response.data.data;
            //获取当前相同类型的申请
            this.type_applicats = response.data.data.type_applicats.data;
            this.fileList = JSON.parse(this.applicat.files);
            console.log(response);
            console.log(this.fileList);
        })
    },
    methods: {
        getApplicat() {
            axios.get('/api/applicat/ ' + this.$route.params.id);
        },
        getApplicatByTypeId() {
            axios.get('/api/applicat?type=')
        }
    }
}
</script>

<style>
  a:hover{
     text-decoration:none;
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

</style>
