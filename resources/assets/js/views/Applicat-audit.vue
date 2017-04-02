<template lang="html">
  <div class="row">
      <div class="ibox">
          <div class="ibox-title">
              <h5>{{ $t('el.page.applicat') }}</h5>
          </div>
          <div class="ibox-content">
              <div class="row">
                <el-row :gutter="20">
                    <el-col :span="6" v-for="card in cards">
                      <el-card :body-style="{ padding: '0px' }">
                        <div style="padding: 25px;text-align: center">
                          <span>{{card.number}}</span>
                          <div class="bottom clearfix">
                              {{card.content}}
                          </div>
                        </div>
                      </el-card>
                    </el-col>
                  </el-row>

                  <ul class="data-info ">

                  </ul>
                  <el-table
                    :data="tableData"
                    max-height="300"
                    border
                    style="width: 100%"
                    :default-sort = "{prop: 'date', order: 'descending'}"
                    @row-click="rowClick"
                    >
                    <el-table-column
                      prop="date"
                      label="日期"
                      sortable
                      width="180">
                    </el-table-column>
                    <el-table-column
                      prop="user"
                      label="申请人"
                      sortable
                      width="180">
                    </el-table-column>
                    <el-table-column
                      prop="name"
                      label="负责人"
                      sortable
                      width="180">
                    </el-table-column>
                    <el-table-column
                      prop="type"
                      label="申请类型"
                      sortable
                      width="180">
                    </el-table-column>
                    <el-table-column
                      prop="mechanisms"
                      label="申请机构"
                      sortable
                      width="180">
                    </el-table-column>
                    <el-table-column
                      prop="mobile"
                      label="联系方式"
                      sortable
                      width="180">
                    </el-table-column>
                    <el-table-column
                      prop="date"
                      label="借用时段"
                      sortable
                      width="180">
                    </el-table-column>
                    <el-table-column
                      prop="agency"
                      label="联合机构"
                      sortable
                      width="180">
                    </el-table-column>
                    <el-table-column
                      prop="files"
                      label="相关文件"
                      sortable
                      width="180">
                    </el-table-column>
                    <el-table-column
                      fixed="right"
                      prop="status"
                      label="当前状态"
                      sortable
                      width="180">
                    </el-table-column>
                  </el-table>
                </div>
              </div>
        </div>
        <el-dialog title="申请详情" v-model="dialogTableVisible">
            <!-- <div slot="title" style="display: inline-block;">
                <span>申请详情</span>
            </div> -->
            <div class="row" style="margin:0px">
            <div class="col-md-9">
              <el-row>
                <el-col :span="6"><div class="grid-content bg-purple">申请机构</div></el-col>
                <el-col :span="6"><div class="grid-content bg-purple-light">软件园协会</div></el-col>
                <el-col :span="6"><div class="grid-content bg-purple">申请类型</div></el-col>
                <el-col :span="6"><div class="grid-content bg-purple-light">励能公共厨房</div></el-col>
              </el-row>
              <el-row>
                <el-col :span="6"><div class="grid-content bg-purple">负责人</div></el-col>
                <el-col :span="6"><div class="grid-content bg-purple-light">邓康发</div></el-col>
                <el-col :span="6"><div class="grid-content bg-purple">联系方式</div></el-col>
                <el-col :span="6"><div class="grid-content bg-purple-light">110</div></el-col>
              </el-row>
              <el-row>
                <el-col :span="6"><div class="grid-content bg-purple">联合机构</div></el-col>
                <el-col :span="18"><div class="grid-content bg-purple-light">
                  We’re pleased to announce the open source release of Yarn, a collaboration between Facebook, Exponent, Google, and Tilde. With Yarn, engineers still have access to the npm registry, but can install packages more quickly and manage dependencies consistently across machines or in secure offline environments. Yarn enables engineers to move faster and with confidence when using shared code so they can focus on what matters — building new products and features. Read the full announcement on code.facebook.com.
                </div>
              </el-col>
              <el-row>
                <el-col :span="6"><div class="grid-content bg-purple">申请缘由</div></el-col>
                <el-col :span="18"><div class="grid-content bg-purple-light">
                  We’re pleased to announce the open source release of Yarn, a collaboration between Facebook, Exponent, Google, and Tilde. With Yarn, engineers still have access to the npm registry, but can install packages more quickly and manage dependencies consistently across machines or in secure offline environments. Yarn enables engineers to move faster and with confidence when using shared code so they can focus on what matters — building new products and features. Read the full announcement on code.facebook.com.
                </div>
              </el-col>
              <el-row>
                <el-col :span="6"><div class="grid-content bg-purple">物资申请</div></el-col>
                <el-col :span="18"><div class="grid-content bg-purple-light">
                  We’re pleased to announce the open source release of Yarn, a collaboration between Facebook, Exponent, Google, and Tilde. With Yarn, engineers still have access to the npm registry, but can install packages more quickly and manage dependencies consistently across machines or in secure offline environments. Yarn enables engineers to move faster and with confidence when using shared code so they can focus on what matters — building new products and features. Read the full announcement on code.facebook.com.
                </div>
              </el-col>
              </el-row>
              <el-row>
                <el-col :span="6"><div class="grid-content bg-purple">时段(开始时间-结束时间)</div></el-col>
                <el-col :span="18"><div class="grid-content bg-purple-light">不知道说什么</div></el-col>
              </el-row>
            </div>
            <div class="col-md-3">

            </div>
          </div>
            <div slot="footer" style="text-align:center">
              脚
            </div>
        </el-dialog>
    </div>
</template>

<script>
export default {
    data() {
      return {
        tableData: [{
          date: '2016-05-02',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1518 弄'
        }, {
          date: '2016-05-04',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1517 弄'
        }, {
          date: '2016-05-01',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1519 弄'
        }, {
          date: '2016-05-03',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1516 弄'
        },{
          date: '2016-05-02',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1518 弄'
        }, {
          date: '2016-05-04',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1517 弄'
        }, {
          date: '2016-05-01',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1519 弄'
        }, {
          date: '2016-05-03',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1516 弄'
        },{
          date: '2016-05-02',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1518 弄'
        }, {
          date: '2016-05-04',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1517 弄'
        }, {
          date: '2016-05-01',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1519 弄'
        }, {
          date: '2016-05-03',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1516 弄'
        },{
          date: '2016-05-02',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1518 弄'
        }, {
          date: '2016-05-04',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1517 弄'
        }, {
          date: '2016-05-01',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1519 弄'
        }, {
          date: '2016-05-03',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1516 弄'
        }],
        cards: [
            {number:18,content:'待审核数据'},
            {number:5,content:'今日提交'},
            {number:2,content:'审核结果'},
            {number:0,content:'最终反馈'}
        ],
        dialogTableVisible: false,
      }
    },
    methods: {
      formatter(row, column) {
        return row.address;
      },
      rowClick(row, event, column) {
          console.log(row);
          console.log(event);
          console.log(column);
          this.dialogTableVisible = true;
      }
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
    margin-bottom: 20px;
    &:last-child {
      margin-bottom: 0;
    }
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
</style>
