<template lang="html">
  <div class="">
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
    <div class="row">
        <div class="col-md-8">
            <div class="box">

            </div>
        </div>
        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                    <div class="pull-right"></div>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>IP</th>
                                <th>Position</th>
                                <th>Created at</th>
                            </tr>
                            <tr v-for="loginLog in statistics.loginLogs">
                                <td><el-tag type="primary">{{ loginLog.ip }}</el-tag></td>
                                <td>{{ }}</td>
                                <td>{{ loginLog.create_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">

                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
   data () {
        return {
            statistics: {}
        }
    },
    mounted() {
        axios.get('/api/statistics')
            .then((response) => {
              console.log(response);
                this.statistics = response.data
            })
    }
}
</script>

<style lang="css" scoped>
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

  .box {
      position: relative;
      border-radius: 3px;
      background: #ffffff;
      border-top: 3px solid #d2d6de;
      margin-bottom: 20px;
      width: 100%;
      box-shadow: 0 1px 1px rgba(0,0,0,0.1);
  }
</style>
