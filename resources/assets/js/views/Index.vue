<template lang="html">
  <div class="dashboard">
    <!-- 卡片 -->
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
    <!-- 卡片END -->
    <!-- chart -->
    <div class="row" style="margin-top:15px">
        <div class="col-md-8 col-xs-12" v-show="chart_show">
            <div class="panel panel-default chart">
                <div class="panel-heading">
                  <h3 class="panel-title">{{ $t('el.page.line_chart') }}</h3>
                    <div class="actions pull-right">
                        <i :class="chart_hide ? 'el-icon-arrow-up' : 'el-icon-arrow-down'" @click='chartToggle()'></i>
                        <!-- <i class="el-icon-arrow-up"></i> -->
                    </div>
                </div>
                <div id="chart-body" class="panel-body" style="mix-height:160px" v-show="!chart_hide">
                  <chart :width="600" :height="300" :data="data" type="line"></chart>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xs-12" v-show="access_log_show">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $t('el.page.access_log') }}</h3>
                    <div class="actions pull-right">
                        <i :class="access_log_hide ? 'el-icon-arrow-up' : 'el-icon-arrow-down'" @click='accessToggle'></i>
                        <!-- <i class="el-icon-arrow-up"></i> -->
                    </div>
                </div>
                <div id="access-body" class="panel-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>{{ $t('el.table.user') }}</th>
                                <th>{{ $t('el.table.position') }}</th>
                                <th>{{ $t('el.table.created_at') }}</th>
                            </tr>
                            <tr v-for="loginLog in loginlogs">
                                <td><el-tag type="primary">{{ loginLog.user_name }}</el-tag></td>
                                <template v-if="loginLog.iplookup.country">
                                  <td>{{ loginLog.iplookup.country+' '+loginLog.iplookup.province+' '+loginLog.iplookup.city }}</td>
                                </template>
                                <template v-else>
                                  <td>{{ loginLog.ip }}</td>
                                </template>
                                <td>
                                  <timeInterval :date="loginLog.create_at"></timeInterval>
                                </td>
                                <!-- <td>{{ formatMsgTime(loginLog.create_at) }}</td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- chart END -->
    <!-- 站点通告 -->
    <div class="row">
      <div class="col-md-8"  v-show="notice_show">
          <div class="panel panel-default">
              <div class="panel-heading">
                  <h3 class="panel-title">{{ $t('el.page.site_announcements') }}</h3>
                  <div class="actions pull-right">
                      <i :class="notice_hide ? 'el-icon-arrow-up' : 'el-icon-arrow-down'" @click='noticeToggle'></i>
                      <!-- <i class="el-icon-arrow-up"></i> -->
                  </div>
              </div>
              <div id="notice" class="panel-body" style="mix-height:160px">

              </div>
          </div>
      </div>
    <!-- 站点通告END -->
    <!-- 天气预报 -->
    <div class="col-md-4" id="weather">
            <div class="panel panel-solid-info">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$t('el.page.weather')}}</h3>
                    <div class="actions pull-right">
                        <i :class="weather_hide ? 'el-icon-arrow-up' : 'el-icon-arrow-down'" @click="weatherToggle"></i>
                        <i class="ion-close-round"></i>
                    </div>
                </div>
                <div class="panel-body" id="weather-body">
                    <div class="row" style="margin-left: -15px; margin-right: -15px;">
                        <div class="col-md-6 col-xs-6" v-show="weathers.length">
                            <!-- 当前白天/夜间<存在实时>天气情况 -->
                            <template v-if="isNight">
                                <!-- 夜间 -->
                                <h3 class="text-center small-thin uppercase">{{$t('el.page.night')}}</h3>
                                <div class="text-center">
                                    <!-- 天气动画图标 -->
                                    <canvas id="today-night" width="110" height="110"></canvas>
                                    <!-- 天气动画图标END -->
                                    <!-- 夜间的温度范围 -->
                                    <h4 id="night-temperature" v-if="weathers.length">{{weathers[0].temperature}}</h4>
                                    <!-- 夜间的温度范围END -->
                                    <!-- 当前城市 -->
                                    <h5 class="current-city" v-if="weathers.length">{{weathers[0].citynm}}</h5>
                                    <!-- 当前城市END -->
                                </div>
                                <!-- 夜间 -->
                            </template>
                            <template v-else>
                                <!-- 白天 -->
                                <h3 class="text-center small-thin uppercase">{{$t('el.page.day')}}</h3>
                                <div class="text-center">
                                    <!-- 天气动画图标 -->
                                    <canvas id="today" class="clear-day" width="110" height="110"></canvas>
                                    <!-- 天气动画图标END -->
                                    <!-- 白天的温度范围 -->
                                    <h4 id="today-temperature" v-if="weathers.length">{{weathers[0].temperature}}</h4>
                                    <!-- 白天的温度范围END -->
                                    <!-- 当前城市 -->
                                    <h5 class="current-city" v-if="weathers.length">{{weathers[0].citynm}}</h5>
                                    <!-- 当前城市 -->
                                </div>
                                <!-- 白天END -->
                            </template>
                        </div>
                        <div class="col-md-6 col-xs-6" v-if="weathers.length">
                            <template v-if="weathers[0].temperature_curr">
                              <h3>{{$t('el.page.real_time')}}</h3>
                              <div class="today-info">
                                <!-- 当前温度 -->
                                <div>
                                  <span>{{weathers[0].temperature_curr}}</span>
                                </div>
                                <!-- 当前温度 -->
                                <!-- 当前天气文字描述 -->
                                <div>
                                  <span>{{weathers[0].weather_curr}}</span>
                                </div>
                                <!-- 当前天气文字描述END -->
                                <!-- 风力 -->
                                <div>
                                  {{weathers[0].wind + weathers[0].winp}}
                                </div>
                                <!-- 风力END -->
                                <!-- 湿度 -->
                                <div>
                                  {{$t('el.page.considerable_humidity')}} {{weathers[0].humidity}}
                                </div>
                                <!-- 湿度END -->
                              </div>
                            </template>
                            <template v-else>
                              <h3 class="text-center small-thin uppercase">Tonight</h3>
                              <div class="text-center">
                                  <canvas id="today-night" width="110" height="110"></canvas>
                                  <h4 id="night-temperature" v-if="weathers.length">{{weathers[0].temperature}}</h4>
                              </div>
                            </template>
                        </div>
                    </div>
                </div>
                <!-- 未来四天天气状况 -->
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-3 col-xs-3">
                            <h6 class="text-center small-thin uppercase" v-if="weathers.length">{{weathers[1].week}}</h6>
                            <div class="text-center">
                                <div>
                                  <canvas id="today1" width="32" height="32"></canvas>
                                </div>
                                <span id="today-temperature1" v-if="weathers.length">{{weathers[1].temperature}}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <h6 class="text-center small-thin uppercase" v-if="weathers.length">{{weathers[2].week}}</h6>
                            <div class="text-center">
                                <div>
                                  <canvas id="today2" width="32" height="32"></canvas>
                                </div>
                                <span id="today-temperature1" v-if="weathers.length">{{weathers[2].temperature}}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <h6 class="text-center small-thin uppercase" v-if="weathers.length">{{weathers[3].week}}</h6>
                            <div class="text-center">
                                <div class="">
                                  <canvas id="today3" width="32" height="32"></canvas>
                                </div>
                                <span id="today-temperature1" v-if="weathers.length">{{weathers[3].temperature}}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <h6 class="text-center small-thin uppercase" v-if="weathers.length">{{weathers[4].week}}</h6>
                            <div class="text-center">
                                <div class="">
                                  <canvas id="today4" width="32" height="32"></canvas>
                                </div>
                                <span id="today-temperature1" v-if="weathers.length">{{weathers[4].temperature}}</span>
                            </div>
                        </div>
                        <!-- <div class="col-md-2">
                            <h6 class="text-center small-thin uppercase" v-if="weathers.length">{{weathers[5].week}}</h6>
                            <div class="text-center">
                                <canvas id="today5" width="32" height="32"></canvas>
                                <span id="today-temperature1" v-if="weathers.length">{{weathers[5].temperature}}</span>
                            </div>
                        </div> -->
                    </div>
                    <!-- 未来四天天气状况END -->
                </div>
            </div>
        </div>
        <!-- 天气预报END -->
      </div>
  </div>
</template>

<script>
  import Skycons from 'skycons'
  import { mapState } from 'vuex'
  import Chart from '../components/Chartjs.vue'
  import TimeInterval from '../components/TimeInterval.vue'

  export default {
     data () {
          return {
              //是否显示站点通告
              notice_show: false,
              //用于站点通告延迟渲染作用
              notice_hide: true,
              //是否显示访问历史
              access_log_show: false,
              access_log_hide: true,
              card_show: false,
              chart_show: false,
              chart_hide: true,
              weather_hide: true,
              loginlogs: [],
              weathers: [],
              //天气动画图标对象
              skyconsObj: {},
              //天气动画图标实现
              icons: {},
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
                          label: 'login history',
                          fill: false,
                          borderColor: "rgba(75,192,192,1)",
                          pointStrokeColor : "#fff",
                          data : [65,59,90,81,56,55,40]
                      },
                      {
                          label: 'applicat number',
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
        ]),
        isNight() {
            let now = new Date(),hour = now.getHours();
            if(hour < 6 || hour > 19) {
                return true;
            }
            return false;
        }
      },
      components: {
            Chart,
            TimeInterval
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
                  vm.getAccessLog();
                  continue;
              }
          }
          //去除第一个','
          show = show.substr(1);
          this.getWeather();
          this.getStatistics(show);
      },
      methods: {
        chartToggle() {
            $('#chart-body').slideToggle("false", () => {
                this.chart_hide = !this.chart_hide
            })
        },
        accessToggle() {
            $('#access-body').slideToggle("false", () => {
                this.access_log_hide = !this.access_log_hide
            })
        },
        noticeToggle() {
            $('#notice').slideToggle("false", () => {
                this.notice_hide = !this.notice_hide
            })
        },
        weatherToggle() {
           $('#weather-body').slideToggle("fast", () => {
                this.weather_hide = !this.weather_hide
           });
        },
        //获取最近访问记录
        getAccessLog() {
            axios.get('/api/loginlogs').then( response => {
                this.loginlogs = response.data;
            }, error => {
                toastr.error(this.$t('el.notification.loginlogs_api_error'));
            })
        },
        getWeather() {
          axios.get('/api/weather')
          .then(response => {
              this.weathers = response.data;
              this.skyconsObj = Skycons(window);
              this.icons = new this.skyconsObj({"color": "white"});
              for (var i = 0; i < response.data.length; i++) {
                  if(i == 0) {
                      this.weatherSkycons('today', response.data[i]['weatid'], false)
                      this.weatherSkycons('today-night', response.data[i]['weatid1'], true)
                  }else{
                      this.weatherSkycons('today'+i,response.data[i]['weatid'],false)
                  }
              }
              this.icons.play();
          },error => {
              toastr.error(this.$t('el.notification.weather_api_error'));
          })
        },
        getStatistics(data) {
          let vm = this;
          axios.get('/api/statistics', {
                  params: {
                    show: data
                  }
                })
              .then(response => {
                  vm.statistics = response.data
                  //站点公告
                  $("#notice").html(response.data.notice);
                  if(vm.chart_show){
                    //图表横坐标
                    vm.data.labels = response.data.accessCountLogs.labels;
                    //访问量
                    vm.data.datasets[0].data = response.data.accessCountLogs.access;
                    //申请量
                    vm.data.datasets[1].data = response.data.accessCountLogs.apply;
                    //显示图表
                    vm.chart_hide = false;
                  }
                  if(vm.notice_show) {
                    //获取数据后渲染
                    if(response.data.notice){
                      vm.notice_hide = false;
                    }else{
                      vm.notice_show = false;
                    }
                  }
                  vm.weather_hide = false;
              }, error => {
                  toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
              })
        },
        //天气代码转动画
        weatherSkycons(id,weatid,night) {
            //判断是否是夜间
            if(night == true) {
                if(weatid == 1) {
                    this.icons.add(id,this.skyconsObj.CLEAR_NIGHT);
                    return;
                } else if(weatid == 2) {
                    this.icons.add(id,this.skyconsObj.PARTLY_CLOUDY_NIGHT);
                    return;
                }
            }
            if(weatid == 1) {
                this.icons.add(id,this.skyconsObj.CLEAR_DAY);
            } else if(weatid == 2) {
                this.icons.add(id,this.skyconsObj.PARTLY_CLOUDY_DAY);
            } else if(weatid == 3) {
                this.icons.add(id, this.skyconsObj.CLOUDY);
            } else if(weatid == 8 || weatid == 9) {
                this.icons.add(id, this.skyconsObj.RAIN);
            } else if(weatid == 19 || weatid == 33) {
                this.icons.add(id, this.skyconsObj.FOG);
            } else if(weatid > 13 && weatid < 19 || weatid > 26 && weatid < 30) {
                this.icons.add(id, this.skyconsObj.SNOW);
            } else if(weatid > 29 && weatid < 33) {
                this.icons.add(id, this.skyconsObj.WIND);
            } else {
                this.icons.add(id, this.skyconsObj.SLEET);
            }
        },
        formatMsgTime (timespan) {
          //php时间戳转换成js时间戳
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
          let day_str= this.$t('el.table.day');
          let hour_str= this.$t('el.table.hour');
          let minute_str= this.$t('el.table.minute');
          let second_str= this.$t('el.table.second');
          if (days >= 1) {
              time = days + day_str;
          } else if (hours >= 1) {
              time = hours + hour_str;
          } else if (minutes >= 1) {
              time = minutes + minute_str;
          } else {
              time = seconds + second_str;
          }
          return time;
        }
          // getIpLookup(ip) {
          //     axios.get('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' + ip).then( response => {
          //         console.log(response);
          //     })
          // }
      }
  }
</script>

<style lang="scss" scoped>
  .hide {
      display: none;
  }
  .dashboard {
    margin-left: -15px;
    margin-right: -15px;
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

  .panel>.panel-footer {
      font-size: 13px;
      font-weight: 400;
      text-transform: uppercase;
      padding: 15px;
  }

  .panel.chart {
      margin-bottom: 15px
  }

  .panel-solid-info>.panel-body, .panel-solid-info>.panel-footer, .panel-solid-info>.panel-heading {
      background: #3598db;
      color: #fff;
      border: none;
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
  .panel .actions i {
      font-size: 1em;
      margin: 0 3px;
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

  #weather{
      h1, h2, h3, h4, h5, h6 {
          font-family: 'Source Sans Pro',Arial,sans-serif;
      }
      h3 {
          font-size: 24px;
      }
      h4 {
          font-size: 18px;
      }
      h6{
          font-size: 12px;
      }
      .row {
        margin-left: -15px;
        margin-right: -15px;
      }
      .today-info {
          font-size: 18px;
      }
      .panel-body {
        padding-bottom: 0px;
      }
      .current-city {
        margin-bottom: 0;
        font-size: 18px;
      }
  }


  @media (max-width:570px) {
      .ishide {
          display: none;
      }
  }
</style>
