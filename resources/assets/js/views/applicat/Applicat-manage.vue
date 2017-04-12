<template lang="html">
  <div class="row">
              <div class="col-sm-12">

                  <div class="ibox">
                      <div class="ibox-title">
                          <h5>所有申请</h5>
                          <div class="ibox-tools">
                              <router-link to="/applicat" class="btn btn-primary btn-xs">
                                创建新申请
                              </router-link>
                          </div>
                      </div>
                      <div class="ibox-content">
                          <div class="row m-b-sm m-t-sm">
                              <div class="col-md-1">
                                  <button type="button" id="loading-example-btn" class="btn btn-white btn-sm"><i class="fa fa-refresh"></i> 刷新</button>
                              </div>
                              <div class="col-md-11">
                                  <div class="input-group">
                                      <input type="text" placeholder="请输入项目名称" class="input-sm form-control"> <span class="input-group-btn">
                                          <button type="button" class="btn btn-sm btn-primary"> 搜索</button> </span>
                                  </div>
                              </div>
                          </div>

                          <div class="project-list">

                              <table class="table table-hover">
                                  <tbody>
                                    <template v-if="applicats.length > 0">
                                      <tr v-for="applicat in applicats">
                                          <td class="project-status col-md-1">
                                              <span class="label label-primary">{{applicat.status}}
                                          </td>
                                          <td class="project-title col-md-4">
                                              <a href="project_detail.html">{{ applicat.mechanism }} - {{ applicat.type }}</a>
                                              <br/>
                                              <small>创建于 {{ applicat.created_at }}</small>
                                          </td>
                                          <!-- <td class="project-completion">
                                            {{applicat.stage+1 / applicat.roles.data.length}}
                                                  <small>当前进度： {{(applicat.stage+1 / applicat.roles.data.length)*100  }}%</small>
                                                  <div class="progress progress-mini">
                                                      <div style="width: 48%;" class="progress-bar"></div>
                                                  </div>
                                          </td> -->
                                          <td class="project-roles col-md-5">
                                            <el-steps :space="150" :active="applicat.stage" :direction="isPhone ? 'vertical' : 'horizontal'" finish-status="success">
                                              <el-step v-for="role in applicat.roles.data" :description="role.display_name"></el-step>
                                            </el-steps>
                                          </td>
                                          <td class="project-actions col-md-2">
                                            <router-link :to="$route.path + '/details/' + applicat.id">
                                                <i class="btn btn-white btn-sm ion-folder"> 查看</i>
                                            </router-link>
                                          <a href="projects.html#" class="btn btn-white btn-sm"><i class="ion-edit"></i> 编辑 </a>
                                          </td>
                                      </tr>
                                      </template>
                                      </tbody>
                                  </table>
                                    <h3 class="none text-center" v-if="applicats.length == 0">{{ $t('el.page.nothing') }}</h3>
                              </div>
                              <nav class="text-center">
                                  <div v-if="applicats.length < 1"></div>
                                  <template v-else>
                                      <el-pagination
                                      v-if="!isPhone"
                                      @size-change="handleSizeChange"
                                      @current-change="handleCurrentChange"
                                      :current-page="currentPage"
                                      :page-sizes="[10, 20, 50, 100]"
                                      :page-size="pageSize"
                                      :layout="'total, sizes, prev, pager, next, jumper'"
                                      :total="total">
                                    </el-pagination>

                                    <el-pagination
                                    v-else
                                    :small="true"
                                    @current-change="handleCurrentChange"
                                    :current-page="currentPage"
                                    :layout="'prev, pager, next'"
                                    :total="total">
                                  </el-pagination>
                                </template>
                            </nav>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
</template>

<script>
  import { mapState } from 'vuex'
  export default {
      data() {
          return {
              applicats: [],
              roles: [],
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
      },
      computed: {
          ...mapState([
              'isPhone'
          ]),
      },
      methods: {
          loadData() {
              var url = '/api/applicat?include=roles';

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

<style lang="css">
  .progress-mini, .progress-mini .progress-bar {
      height: 5px;
      margin-bottom: 0px;
  }
  .progress-small, .progress-mini {
      margin-top: 5px;
  }
  .progress-bar {
      background-color: #23b7e5;
  }
  .project-list table tr td {
      border-top: none;
      border-bottom: 1px solid #e7eaec;
      padding: 15px 10px;
      vertical-align: middle;
  }
  .label {
    background-color: #d1dade;
    color: #5e5e5e;
    font-size: 10px;
    font-weight: 600;
    padding: 3px 8px;
    text-shadow: none;
  }
  .label-primary, .badge-primary {
      background-color: #23b7e5;
      color: #FFFFFF;
  }
  .project-title a {
      font-size: 14px;
      color: #676a6c;
      font-weight: 600;
  }
  .btn-white {
      color: inherit;
      background: white;
      border: 1px solid #e7eaec;
  }
  .project-actions {
      text-align: right;
  }
  .el-step__description {
      margin-top: 5px;
  }
</style>
