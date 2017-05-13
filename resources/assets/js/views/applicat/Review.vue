<template lang="html">
  <div class="row">
      <div class="ibox">
          <div class="ibox-title">
              <h5>{{ $t('el.page.applicat_review') }}</h5>
          </div>
          <div class="ibox-content">
            <div class="row" style="margin-bottom: 10px">
                <div class="col-md-1 col-xs-3">
                  <div class="btn-group">
                      <el-button type="primary" size="small" style="padding:8px 9px" :loading="showLoading" icon="ion-refresh" @click="loadData">
                        <i class="ion-refresh" v-if="!showLoading"></i> {{ loading_text }}
                      </el-button>
                  </div>
                    <!-- <button type="button" @click="loadData" id="loading-example-btn" class="btn btn-white btn-sm"><i class="fa fa-refresh"></i> 刷新</button> -->
                </div>
                <div class="col-md-11 col-xs-9">
                    <div class="input-group">
                        <input type="text" v-model="keyWord" :placeholder="$t('el.form.review_filter_placeholder')" class="input-sm form-control"> <span class="input-group-btn">
                            <button type="button" class="btn btn-sm btn-primary"> {{ $t('el.page.search') }}</button> </span>
                    </div>
                </div>
            </div>
                <table class="table table-hover">
                    <tbody>
                        <tr v-for="applicat in applicatlist">
                            <td><Status :status="applicat.status"></Status></td>
                            <td class="project-title">
                              <router-link :to="'/review/details/'+applicat.id">
                                {{ applicat.mechanism }} - {{ applicat.type }}
                              </router-link>
                              <br/>
                              <small>
                                <div class="">
                                  {{$t('el.table.created_at') + ' '}}
                                  <timeInterval :date="applicat.created_at"></timeInterval>
                                </div>
                              </small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h3 class="none text-center" v-if="applicats.length == 0">{{ $t('el.page.nothing') }}</h3>
                    <div slot="footer" style="text-align:center">
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
</template>

<script>
    import { mapState } from 'vuex'
    import Status from '../../components/Status'
    import TimeInterval from '../../components/TimeInterval'
    export default {
        data() {
          return {
            // cards: [
            //     {number:18,content:'待审核数据'},
            //     {number:5,content:'今日提交'},
            //     {number:2,content:'审核结果'},
            //     {number:0,content:'最终反馈'},
            // ],
            dialogTableVisible: false,
            applicats: [],
            total: 0,
            totalPage: 0,
            currentPage: 0,
            pageSize: 10,
            showLoading: false,
            loading_text: '',
            keyWord: ''
          }
        },
        created() {
            this.currentPage = this.$route.query.page;
            this.pageSize = parseInt(this.$route.query.pageSize);
            this.loadData();
        },
        components: {
            Status,
            TimeInterval
        },
        computed: {
            ...mapState([
                'isPhone'
            ]),
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
            }
        },
        methods: {
          loadData() {
              this.showLoading = true;
              this.loading_text = this.$t('el.form.loading');
              let url = '/api/applicats';

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
                      this.showLoading = false;
                      this.loading_text = this.$t('el.form.refresh');
                      // this.pagination = response.data.meta.pagination
                      this.applicats = response.data.data
                      this.totalPage = response.data.meta.pagination.total_pages
                      this.currentPage = response.data.meta.pagination.current_page
                      this.total = response.data.meta.pagination.total
                  })
          },
          // loading() {
          //   axios.get('/api/applicat').then(response => {
          //       this.applicats = response.data.data;
          //       console.log(response);
          //   })
          // },
          formatter(row, column) {
            return row.address;
          },
          rowClick(row, event, column) {
              this.dialogTableVisible = true;
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

<style lang="css" scoped>
    .height {
        height: 50%;
    }
    .split-line {
        border-top: 1px solid #E9E9E9
    }
    .info-container {
        font-size: 12px;
        width:250px;
        float: right;
    }
    .el-row {
      text-align: center;
      line-height: 36px;
    margin-bottom: 20px;
    &:last-child {
      margin-bottom: 0;
    }
  }
  .row-eq-height {
    /*display: flex;*/
    word-break: break-all;
    word-wrap: break-word;
  }
  .el-col {
    border-radius: 4px;
  }
  .bg-purple-dark {
    background: #99a9bf;
  }
  .bg-purple {
    font-weight: bold;
    background: #d3dce6;
  }
  .bg-purple-light {
    background: #e5e9f2;
  }
  .grid-content {
    min-height: 36px;
  }
  .row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
  }
  .el-row {
      margin: 0px;
  }
  .el-dialog__header {
    border-bottom: 1px solid #E9E9E9;
    padding-bottom: 20px;
  }
  /*.el-pager li.active {
    border-color: #324b57 !important;
    background-color: #324b57 !important;
  }*/
  .none {
      color: #ECF0F1;
      padding-bottom: 20px;
  }
</style>
