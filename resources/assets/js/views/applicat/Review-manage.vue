<template lang="html">
  <div class="row" id="applicat-manage">
      <div class="ibox">
          <div class="ibox-title">
              <h5>{{ $t('el.page.applicat_review') }}</h5>
          </div>
          <div class="ibox-content">
            <el-table
               :data="applicats"
               style="width: 100%"
               :default-sort = "{prop: 'created_at', order: 'descending'}">
               <el-table-column type="expand">
                 <template slot-scope="props">
                   <el-form label-position="left" inline class="demo-table-expand">
                     <el-form-item :label="$t('el.form.principal')">
                       <span>{{ props.row.principal }}</span>
                     </el-form-item>
                     <el-form-item :label="$t('el.form.applay_type')">
                       <span>{{ props.row.type }}</span>
                     </el-form-item>
                     <el-form-item :label="$t('el.form.mechanism')">
                       <span>{{ props.row.mechanism }}</span>
                     </el-form-item>
                     <el-form-item :label="$t('el.form.mobile')">
                       <span>{{ props.row.mobile }}</span>
                     </el-form-item>
                     <el-form-item :label="$t('el.form.borrow_period')">
                       <span>{{ props.row.startTime + "~" +  props.row.endTime}}</span>
                     </el-form-item>
                     <el-form-item :label="$t('el.form.participate_number')">
                       <span>{{ props.row.number }}</span>
                     </el-form-item>
                     <el-form-item :label="$t('el.form.applay_reason')" style="width: 100%;">
                       <span>{{ props.row.reason }}</span>
                     </el-form-item>
                   </el-form>
                 </template>
               </el-table-column>
               <el-table-column
                   prop="created_at"
                   :label="$t('el.table.date')"
                   sortable
                  :width="isPhone ? 180 : ''">
                 <template slot-scope="scope">
                    <el-icon name="time"></el-icon>
                    <span style="margin-left: 10px">{{ scope.row.created_at.split(' ')[0] }}</span>
                  </template>
               </el-table-column>
               <el-table-column
                 prop="status"
                 :label="$t('el.table.status')"
                 :filters="filters"
                 :filter-method="filterStatus"
                 :width="isPhone ? 180 : ''">
                 <template slot-scope="scope">
                      <status :status="scope.row.status" :statusClass="false"></status>
                  </template>
               </el-table-column>
               <el-table-column
                 :label="$t('el.table.type')"
                 sortable
                 prop="type"
                 :width="isPhone ? 180 : ''">
               </el-table-column>
               <el-table-column
                 :label="$t('el.form.mechanism')"
                 prop="mechanism"
                 sortable
                 :width="isPhone ? 180 : ''">
               </el-table-column>
               <el-table-column
                :label="$t('el.table.action')"
                :width="isPhone ? 180 : ''">
                <template slot-scope="scope">
                  <router-link :to="'/applicat-manage/details/' + scope.row.id">
                      <el-button  type="text" size="small">{{ $t('el.form.look') }}</el-button>
                  </router-link>
                </template>
              </el-table-column>
              </el-table>
              <nav class="text-center" style="width: 100%; margin-top: 20px;">
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
  import Status from '../../components/Status'
  import { mapState } from 'vuex'
  const filters = [{text: '待审核',value: '待审核'},
                  {text: '待评价',value: '待评价'},
                  {text: '进行中',value: '进行中'},
                  {text: '审核中',value: '审核中'},
                  {text: '审核通过',value: '审核通过'},
                  {text: '审核不通过',value: '审核不通过'},
                  {text: '已取消',value: '已取消'},
                  {text: '已结束',value: '已结束'},
                  {text: '已删除',value: '已删除'},]

  export default {
    data() {
       return {
         applicats: [],
         total: 0,
         totalPage: 0,
         currentPage: 0,
         pageSize: 10,
         filters: filters,
       }
     },
     created() {
         this.currentPage = this.$route.query.page;
         this.pageSize = parseInt(this.$route.query.pageSize);
         this.loadData()
     },
     components: {
         Status
     },
     computed: {
         ...mapState([
             'isPhone'
         ])
     },
     methods: {
         loadData() {
             //获取当前全路由路径
             let fullpath = this.$route.fullPath
             //获取相应的api地址
             let url = '/api/applicats?condition=type_id';

             //判断是否存在
             if(fullpath.indexOf("?") != -1)
               if(url.indexOf('?') >= 0){
                   url += '&' + fullpath.slice(fullpath.indexOf("?") + 1);
               }else{
                   url += '?' + fullpath.slice(fullpath.indexOf("?") + 1);
               }

             axios.get(url).then(response => {
                 // this.pagination = response.data.meta.pagination
                 this.applicats = response.data.data
                 this.totalPage = response.data.meta.pagination.total_pages
                 this.currentPage = response.data.meta.pagination.current_page
                 this.total = response.data.meta.pagination.total
             })
         },
         handleSizeChange(val) {
             this.pageSize = val;
             this.$router.push({ path: this.$route.fullPath, query: { pageSize: val }});
             // this.loadData();
        },
        handleCurrentChange(val) {
            //currentPage 改变时会触发
            this.currentPage =val;
            this.$router.push({ path: this.$route.fullPath, query: { page: val }});
           //  this.loadData();
        },
        filterStatus(value, row) {
            return row.status === value;
        }
     }
  }
</script>

<style >
  #applicat-manage .demo-table-expand {
    font-size: 0;
  }
  #applicat-manage .demo-table-expand label {
    width: 90px;
    color: #99a9bf;
  }
  #applicat-manage .demo-table-expand .el-form-item {
    margin-right: 0;
    margin-bottom: 0;
    width: 50%;
  }
  #applicat-manage .el-form--inline .el-form-item {
      display: inline-block;
      margin-right: 0;
      vertical-align: top;
  }
</style>
