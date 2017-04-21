<template lang="html">
  <div class="dashboard">
    <div class="row">
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
                    <h1 class="text-left timer" data-to="105" data-speed="2500">212</h1>
                    <p>New Sales</p>
                </div>
                <div class="icon"><i class="fa fa-bar-chart-o"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top:15px">
        <div class="col-md-8 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                  <h3 class="panel-title">最近一星期的近况</h3>
                    <div class="actions pull-right">
                        <i :class="chart_show ? 'el-icon-arrow-up' : 'el-icon-arrow-down'" @click='chart_show = !chart_show'></i>
                        <!-- <i class="el-icon-arrow-up"></i> -->
                    </div>
                </div>
                <div class="panel-body animated fadeInDown" style="mix-height:160px"  v-show="chart_show">
                  <chart :width="600" :height="300" :data="data" type="line"></chart>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">最近访问记录</h3>
                    <div class="actions pull-right">
                        <i :class="access_log_show ? 'el-icon-arrow-up' : 'el-icon-arrow-down'" @click='access_log_show = !access_log_show'></i>
                        <!-- <i class="el-icon-arrow-up"></i> -->
                    </div>
                </div>
                <div class="panel-body  animated fadeInDown table-responsive no-padding" v-show="access_log_show">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>user</th>
                                <th>Position</th>
                                <th>Created at</th>
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
      <div class="col-md-8"  v-if="is_notice" style="max-height:80px">
          <div class="panel panel-default">
              <div class="panel-heading">
                  <h3 class="panel-title">站点通告</h3>
                  <div class="actions pull-right">
                      <i :class="notice_show ? 'el-icon-arrow-up' : 'el-icon-arrow-down'" @click='notice_show = !notice_show'></i>
                      <!-- <i class="el-icon-arrow-up"></i> -->
                  </div>
              </div>
              <div id="notice" class="box-body animated fadeInDown" v-show="notice_show" style="mix-height:160px">

              </div>
          </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Chart from '../components/Chartjs.vue'
  export default {
     data () {
          return {
              is_notice: true,
              access_log_show: true,
              notice_show: true,
              chart_show: false,
              statistics: {
                  users: 0,
                  applicats: 0,
                  files: 0
              },
              data: {
                  labels : ["January","February","March","April","May","June","July"],
                  datasets : [
                          		{
                                label: "login history",
                          			fillColor : "rgba(220,220,220,0.5)",
                          			strokeColor : "rgba(220,220,220,1)",
                          			pointColor : "rgba(220,220,220,1)",
                          			pointStrokeColor : "#fff",
                          			data : [65,59,90,81,56,55,40]
                          		},
                          		{
                                label: "applicat number",
                          			fillColor : "rgba(151,187,205,0.5)",
                          			strokeColor : "rgba(151,187,205,1)",
                          			pointColor : "rgba(151,187,205,1)",
                          			pointStrokeColor : "#fff",
                          			data : [28,48,40,19,96,27,100]
                          		}
                          	]
              }
          }
      },
      components: {
            Chart
      },
      created() {
          axios.get('/api/statistics')
              .then(response => {
                  this.statistics = response.data
                  $("#notice").html(response.data.notice);
                  this.data.labels = response.data.accessCountLogs.labels;
                  this.data.datasets[0].data = response.data.accessCountLogs.date;
                  this.data.datasets[1].data = response.data.accessCountLogs.apply;
                  this.chart_show = true;
                  if(!response.data.notice) this.is_notice = false;
              }, error => {
                  toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
              })
      },
      // created() {
      // },
      methods: {
        formatMsgTime (timespan) {
          var d = new Date(parseInt(timespan) * 1000);

          var date = (d.getFullYear()) + "-" + (d.getMonth() + 1) + "-" + (d.getDate()) + "-" + (d.getHours()) + ":" + (d.getMinutes()) + ":" + (d.getSeconds());
          //当前时间
          var now = new Date();

          var startTime = date;
          startTime = startTime.replace(/\-/g, "/");
          var sTime = new Date(startTime);
          var totalTime = now.getTime() - sTime.getTime();

          var days = parseInt(totalTime / parseInt(1000 * 60 * 60 * 24));
          totalTime = totalTime % parseInt(1000 * 60 * 60 * 24);
          var hours = parseInt(totalTime / parseInt(1000 * 60 * 60));
          totalTime = totalTime % parseInt(1000 * 60 * 60);
          var minutes = parseInt(totalTime / parseInt(1000 * 60));
          totalTime = totalTime % parseInt(1000 * 60);
          var seconds = parseInt(totalTime / parseInt(1000));
          var time = "";
          if (days >= 1) {
              time = days + "天" + hours + "时" + minutes + "分" + seconds + "秒";
          } else if (hours >= 1) {
              time = hours + "时" + minutes + "分" + seconds + "秒";
          } else if (minutes >= 1) {
              time = minutes + "分" + seconds + "秒";
          } else {
              time = seconds + "秒";
          }
          return time + "前";
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
