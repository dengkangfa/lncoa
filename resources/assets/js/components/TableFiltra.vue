value<template>
  <span>
      <!-- <el-button type="primary" size="mini" @click="exportUserInfo"><i class="ion-archive"></i> {{ $t('el.page.export') }}</el-button> -->
    <el-button-group style="margin-bottom:2px">
      <el-button type="primary" icon="search" size="mini" @click="dialogVisible=true">{{ $t('el.page.search') }}</el-button>
      <el-button type="primary" size="mini" @click="refresh"><i class="ion-refresh"></i></el-button>
    </el-button-group>
    <el-dialog title="过滤" v-model="dialogVisible" :size="isPhone ? 'full' : 'tiny'">
    <!-- <modal :show="dialogVisible"
     @cancel="dialogVisible = false"
      @confirm="determine"
      :showFooter="true"
       :full="true"> -->
      <hr>
      <form>
        <div class="input-group input-group-sm" v-for="fiterField in fiterFields">
            <!-- <template v-if="fiterField.type == 'select'">
              <span class="input-group-addon">{{ $t( fiterField.trans ) }}</span>
              <el-select v-model="fiterField.value" multiple :placeholder="$t( 'el.select.placeholder' )" size="large">
                <el-option
                 style="border-radius: 0"
                  v-for="item in roles"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>
            </template> -->
            <template v-if="fiterField.type == 'radio'">
              <el-radio-group v-model="fiterField.value">
                <el-radio-button label="所有"></el-radio-button>
                <el-radio-button label="激活"></el-radio-button>
                <el-radio-button label="未激活"></el-radio-button>
              </el-radio-group>
            </template>
            <template v-else-if="fiterField.type == 'datetime'">
              <template v-if="isPhone">
                <el-col :span="11">
                    <el-date-picker
                       type="datetime"
                       placeholder="开始时间"
                       v-model="fiterField.value[0]"
                       style="width: 100%;">
                     </el-date-picker>
                </el-col>
                <el-col class="line" :span="2" style="text-align: center;">-</el-col>
                <el-col :span="11">
                    <el-date-picker
                      type="datetime"
                      placeholder="结束时间"
                      v-model="fiterField.value[1]"
                      style="width: 100%;">
                     </el-date-picker>
                </el-col>
              </template>
              <template v-else>
                <span class="input-group-addon">{{ $t( fiterField.trans ) }}</span>
                <el-date-picker
                  v-model="fiterField.value"
                  type="datetimerange"
                  style="width: 100%;"
                  :placeholder="$t( 'el.datepicker.selectDateRange' )">
                </el-date-picker>
              </template>
            </template>
            <template v-else>
              <span class="input-group-addon">{{ $t( fiterField.trans ) }}</span>
              <input type="text" class="form-control" :name="fiterField.name" v-model="fiterField.value" :placeholder="$t( fiterField.trans )">
            </template>
        </div>
      </form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="determine">确 定</el-button>
      </span>
    </el-dialog>
    <!-- </modal> -->
  </span>

</template>

<script>
  import server from '../config/api'
  import Modal from '../components/Modal'
  export default {
      props: {
          fiterFields: {
              type: Array,
              required: true
          }
      },
      data() {
          return {
              roles: [],
              radio: '所有',
              isPhone: false,
              datatime: {},
              dialogVisible: false,
          };
      },
      created() {
          let vm = this;
          vm.isPhone = vm.$store.state.isPhone;
          //将路由的参数赋值到fiterFields数组中。
          vm.fiterFields.forEach( function(e){
              let key = e.name
              if(key == 'status') {
                  //账号激活状态过滤条件
                  let status = vm.$route.query[key];
                  if(status == 1) {
                      e.value = '激活'
                  }else if(status == '') {
                      e.value = '所有';
                  }else {
                      e.value = '未激活';
                  }
              }else if(key == 'created_at') {
                  //格式化时间,默认开始时间#结束时间
                  //如果路由存在时间参数，则以#分隔时间字符串成数组
                  if(vm.$route.query[key] == '') {
                    e.value = vm.$route.query[key].split("#");
                  }
              }else {
                  e.value = vm.$route.query[key];
              }
          })
          //获取所有的角色组数据
          // axios.get(server.api.roles).then( response => {
          //   console.log(response);
          //     response.data.data.forEach(function (e){
          //         vm.roles.push({value: e.name, label: e.display_name});
          //     });
          // })
      },
      components: {
          Modal
      },
      methods: {
          determine() {
              let str = '';
              let queryVal = {};
              let vm = this;
              vm.fiterFields.forEach( function(e){
                  //如果是角色筛选，则将数组拼接成字符串
                if( e.name == 'status') {
                      let statusId;
                      if(e.value == '激活') {
                          statusId = 1;
                      }else if(e.value == '未激活') {
                          statusId = 0;
                      }else {
                          statusId = '';
                      }
                        queryVal[e.name] = statusId;
                  }else if( e.name == 'created_at'){
                      if(e.value != ''){
                        console.log(e.value);
                        let startTime = e.value[0] ? vm.formatDataTime(e.value[0]) : null;
                        let endTime = e.value[1] ? vm.formatDataTime(e.value[1]) : null;
                        // let created_at = startTime + "#" + endTime;
                        queryVal['startTime'] = startTime;
                        queryVal['endTime'] = endTime;
                      }else{
                        queryVal[e.name] = '';
                      }
                  }else {
                      //筛选名 -> 筛选值
                      queryVal[e.name] = e.value;
                  }
              })
              vm.$router.push({ path: vm.$route.fullPath, query: queryVal});
              vm.dialogVisible = false;
          },
          refresh() {
              this.$router.push({ path: this.$route.fullPath, query: { id: '', name: '', email: '', status: '', startTime: '', endTime: '' }})
          },
          formatDataTime (date) {
            //时间格式化
            if(date.getFullYear){
               var y = date.getFullYear();
               var m = date.getMonth() + 1;
               m = m < 10 ? ('0' + m) : m;
               var d = date.getDate();
               d = d < 10 ? ('0' + d) : d;
               var h = date.getHours();
               var minute = date.getMinutes();
               minute = minute < 10 ? ('0' + minute) : minute;
               return y + '-' + m + '-' + d+' '+h+':'+minute;
             }
             return date;
         },
         exportUserInfo() {
            axios.get('/api/user/export').then(response => {
                console.log(response);
            }, error => {
                console.log(error);
            })
         }
      }
  }
</script>

<style scoped>
    hr {
        margin-top: -12px;
    }
    .input-group {
        margin-top: 15px;
    }
    .el-select,.el-date-picker{
      width: 100%
    }
    .el-input__inner {
        border-radius: 0;
    }
    .el-radio-button__inner{
        color: #b5bdd1;
    }
</style>
