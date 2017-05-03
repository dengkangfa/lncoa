<template lang="html">
  <div class="dashboard">
    <div class="row" v-show="card_show">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-tile detail tile-red">
                <div class="content">
                    <h1 class="text-left timer" data-from="0" data-to="180" data-speed="2500">{{ statistics.users }}</h1>
                    <p>{{ $t('el.page.all_user') }}</p>
                </div>
                <div class="icon"><i class="ion-ios-people"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-tile detail tile-turquoise">
                <div class="content">
                    <h1 class="text-left timer" data-from="0" data-to="56" data-speed="2500">{{ statistics.applicats }}</h1>
                    <p>{{ $t('el.page.all_applicat') }}</p>
                </div>
                <div class="icon" style="right:20px"><i class="ion-ios-compose"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-tile detail tile-blue">
                <div class="content">
                    <h1 class="text-left timer" data-from="0" data-to="32" data-speed="2500">{{ statistics.files }}</h1>
                    <p>{{ $t('el.page.new_file') }}</p>
                </div>
                <div class="icon"><i class="ion-ios-cloud-upload"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-tile detail tile-purple">
                <div class="content">
                    <h1 class="text-left timer" data-to="105" data-speed="2500">{{ statistics.applicatFulfill }}</h1>
                    <p>{{ $t('el.page.fulfill_applicat') }}</p>
                </div>
                <div class="icon"><i class="ion-clipboard"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top:15px">
        <div class="col-md-8 col-xs-12" v-show="chart_show">
            <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">{{ $t('el.page.line_chart') }}</h3>
                    <div class="actions pull-right">
                        <i :class="chart_hide ? 'el-icon-arrow-up' : 'el-icon-arrow-down'" @click='chart_hide = !chart_hide'></i>
                        <!-- <i class="el-icon-arrow-up"></i> -->
                    </div>
                </div>
                <div class="panel-body animated fadeInDown" style="mix-height:160px"  v-show="!chart_hide">
                  <chart :width="600" :height="300" :data="data" type="line"></chart>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xs-12" v-show="access_log_show">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $t('el.page.access_log') }}</h3>
                    <div class="actions pull-right">
                        <i :class="access_log_hide ? 'el-icon-arrow-up' : 'el-icon-arrow-down'" @click='access_log_hide = !access_log_hide'></i>
                        <!-- <i class="el-icon-arrow-up"></i> -->
                    </div>
                </div>
                <div class="panel-body  animated fadeInDown table-responsive no-padding" v-show="!access_log_hide">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>{{ $t('el.table.user') }}</th>
                                <th>{{ $t('el.table.position') }}</th>
                                <th>{{ $t('el.table.created_at') }}</th>
                            </tr>
                            <tr v-for="loginLog in statistics.loginLogs">
                                <td><el-tag type="primary">{{ loginLog.user_name }}</el-tag></td>
                                <template v-if="loginLog.iplookup.country">
                                  <td>{{ loginLog.iplookup.country+' '+loginLog.iplookup.province+' '+loginLog.iplookup.city }}</td>
                                </template>
                                <template v-else>
                                  <td></td>
                                </template>
                                <td>{{ formatMsgTime(loginLog.create_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-md-8"  v-show="notice_show" style="max-height:80px">
          <div class="panel panel-default">
              <div class="panel-heading">
                  <h3 class="panel-title">{{ $t('el.page.site_announcements') }}</h3>
                  <div class="actions pull-right">
                      <i :class="notice_hide ? 'el-icon-arrow-up' : 'el-icon-arrow-down'" @click='notice_hide = !notice_hide'></i>
                      <!-- <i class="el-icon-arrow-up"></i> -->
                  </div>
              </div>
              <div id="notice" class="box-body animated fadeInDown" v-show="!notice_hide" style="mix-height:160px">

              </div>
          </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  import Chart from '../components/Chartjs.vue'
  export default {
     data () {
          return {
              notice_show: false,
              notice_hide: true,
              access_log_show: false,
              access_log_hide: true,
              card_show: false,
              chart_show: false,
              chart_hide: true,
              statistics: {
                  users: 0,
                  applicats: 0,
                  files: 0,
                  applicatFulfill: 0
              },
              data: {
                  labels : ["January","February","March","April","May","June","July"],
                  datasets : [
                          		{
                                // label: "login history",
                                label: this.$t('el.page.login_history'),
                          			fill: false,
                                borderColor: "rgba(75,192,192,1)",
                          			pointStrokeColor : "#fff",
                          			data : [65,59,90,81,56,55,40]
                          		},
                          		{
                                // label: "applicat number",
                                label: this.$t('el.page.applicat_number'),
                                fill: false,
                                borderColor: "rgba(255,192,70,1)",
                          			pointStrokeColor : "#fff",
                          			data : [28,48,40,19,96,27,100]
                          		}
                          	]
              }
          }
      },
      computed: {
        ...mapState([
            'permissions'
        ])
      },
      components: {
            Chart
      },
      created() {
          let vm = this;
          let show = '';
          for (let i = 0; i < vm.permissions.length; i++) {
              //卡牌可见性
              if(vm.permissions[i].name == 'show-card') {
                  vm.card_show = true;
                  show += ',card';
                  continue;
              }
              //折线图可见性
              if(vm.permissions[i].name == 'show-chart') {
                  vm.chart_show = true;
                  show += ',chart';
                  continue;
              }
              //站点通告可见性
              if(vm.permissions[i].name == 'show-notice') {
                  vm.notice_show = true;
                  show += ',notice';
                  continue;
              }
              //访问历史记录
              if(vm.permissions[i].name == 'show-access-log') {
                  vm.access_log_show = true;
                  vm.access_log_hide = false;
                  show += ',accessLog';
                  continue;
              }
          }
          show = show.substr(1);
          axios.get('/api/statistics?show=' + show)
              .then(response => {
                  this.statistics = response.data
                  //站点公告
                  $("#notice").html(response.data.notice);
                  if(vm.chart_show){
                    //图表横坐标
                    this.data.labels = response.data.accessCountLogs.labels;
                    //访问量
                    this.data.datasets[0].data = response.data.accessCountLogs.access;
                    //申请量
                    this.data.datasets[1].data = response.data.accessCountLogs.apply;
                    //显示图表
                    this.chart_hide = false;
                  }
                  if(vm.notice_show) {
                    if(response.data.notice){
                      this.notice_hide = false;
                    }else{
                      vm.notice_show = false;
                    }
                  }
              }, error => {
                  toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
              })
      },
      methods: {
        formatMsgTime (timespan) {
          //格式化时间戳
          let d = new Date(parseInt(timespan) * 1000);

          let date = (d.getFullYear()) + "-" + (d.getMonth() + 1) + "-" + (d.getDate()) + "-" + (d.getHours()) + ":" + (d.getMinutes()) + ":" + (d.getSeconds());
          //当前时间
          let now = new Date();

          let startTime = date;
          startTime = startTime.replace(/\-/g, "/");
          let sTime = new Date(startTime);
          let totalTime = now.getTime() - sTime.getTime();

          let days = parseInt(totalTime / parseInt(1000 * 60 * 60 * 24));
          totalTime = totalTime % parseInt(1000 * 60 * 60 * 24);
          let hours = parseInt(totalTime / parseInt(1000 * 60 * 60));
          totalTime = totalTime % parseInt(1000 * 60 * 60);
          let minutes = parseInt(totalTime / parseInt(1000 * 60));
          totalTime = totalTime % parseInt(1000 * 60);
          let seconds = parseInt(totalTime / parseInt(1000));
          let time = "";
          let day_str= ' ' + this.$t('el.table.day') + ' ';
          let hour_str= ' ' + this.$t('el.table.hour') + ' ';
          let minute_str= ' ' + this.$t('el.table.minute') + ' ';
          let second_str= ' ' + this.$t('el.table.second') + ' ';
          let before_str= ' ' + this.$t('el.table.before') + ' ';
          if (days >= 1) {
              time = days + day_str + hours + hour_str + minutes + minute_str + seconds + second_str;
          } else if (hours >= 1) {
              time = hours + hour_str + minutes + minute_str + seconds + second_str;
          } else if (minutes >= 1) {
              time = minutes + minute_str + seconds + second_str;
          } else {
              time = seconds + second_str;
          }
          return time + before_str;
        }
          // getIpLookup(ip) {
          //     axios.get('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' + ip).then( response => {
          //         console.log(response);
          //     })
          // }
      }
  }
</script>

<style lang="css" scoped>
  .hide {
      display: none;
  }
  /*#page-content-wrapper .container-fluid .dashboard .row {
       margin: 0px;
  }*/
  .dashboard {
      padding-top: 30px;
  }
  .row{
      /*margin-left: -15px !important;*/
      margin: -15px !important;
  }
  .dashboard-tile.detail, .dashboard-tile.header {
      position: relative;
      overflow: hidden;
  }
  .dashboard-tile.tile-red {
      background-color: #e84c3d;
      color: #fff;
  }
  .dashboard-tile.tile-turquoise {
      background-color: #1abc9c;
      color: #fff;
  }
  .dashboard-tile.tile-blue {
      background-color: #3598db;
      color: #fff;
  }
  .dashboard-tile.tile-purple {
      background-color: #9a59b5;
      color: #fff;
  }
  .dashboard-tile {
      background-color: #fff;
      color: #555;
      margin-bottom: 15px;
      padding: 10px;
      -webkit-border-radius: 3px;
      -moz-border-radius: 3px;
      -ms-border-radius: 3px;
      -o-border-radius: 3px;
      border-radius: 3px;
  }
  .dashboard-tile.detail .icon {
      position: absolute;
  }
  .dashboard-tile.detail .icon {
      display: block;
      float: right;
      height: 80px;
      margin-bottom: 10px;
      padding-left: 15px;
      width: 95px;
      right: 40px;
      bottom: 9px;
  }
  .dashboard-tile.detail .icon i {
      color: rgba(0,0,0,.05);
      font-size: 105px;
      line-height: 65px;
  }
  .dashboard-tile.detail .content {
      background: 0 0;
      padding: 10px 10px 13px;
      display: inline-block;
      position: relative;
      z-index: 5;
  }
  .dashboard-tile .content h1 {
      margin: 0;
      font-weight: 300;
      font-size: 40px;
      padding: 8px;
  }
  .dashboard-tile .content p {
      margin-bottom: 0;
      padding: 10px;
      font-weight: 4 00;
      font-size: 14px;
      clear: both;
  }

  .panel-default > .panel-heading {
      border-color: #eff2f7;
      background: #fafafa;
      color: #767676;
  }

  .panel > .panel-heading {
      font-size: 13px;
      font-weight: 400;
      text-transform: uppercase;
      padding: 15px;
  }
  .panel-heading {
      padding: 10px 15px;
      border-bottom: 1px solid transparent;
      border-top-right-radius: 3px;
      border-top-left-radius: 3px;
  }

  .panel .actions {
      position: absolute;
      right: 30px;
      top: 18px;
  }
  .panel-default .actions i {
      font-size: 1em;
      color: #bdc3c7;
      margin: 0 3px;
  }

  .panel-default .actions i:hover {
      color: #566371;
  }

  .box {
      position: relative;
      border-radius: 3px;
      background: #ffffff;
      border-top: 3px solid #d2d6de;
      margin-bottom: 20px;
      width: 100%;
      box-shadow: 0 1px 1px rgba(0,0,0,0.1);
  }
  @media (max-width:570px) {
      .ishide {
          display: none;
      }
  }
</style>
