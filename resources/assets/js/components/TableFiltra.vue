<template>
  <span>
      <el-button type="primary" size="mini"><i class="ion-archive"></i> {{ $t('el.page.export') }}</el-button>
    <el-button-group style="margin-bottom:2px">
      <el-button type="primary" icon="search" size="mini" @click="dialogVisible=true">{{ $t('el.page.search') }}</el-button>
      <el-button type="primary" size="mini"><i class="ion-refresh"></i></el-button>
    </el-button-group>
    <el-dialog title="过滤" v-model="dialogVisible" size="tiny">
      <hr>
      <form>
        <div class="input-group input-group-sm" v-for="fiterField in fiterFields">
            <template v-if="fiterField.type == 'select'">
              <span class="input-group-addon">{{ $t( fiterField.trans ) }}</span>
              <el-select v-model="fiterField.val" multiple :placeholder="$t( 'el.select.placeholder' )" size="large">
                <el-option
                 style="border-radius: 0"
                  v-for="item in roles"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>
            </template>
            <template v-else-if="fiterField.type == 'radio'">
              <el-radio-group v-model="fiterField.val">
                <el-radio-button label="所有"></el-radio-button>
                <el-radio-button label="激活"></el-radio-button>
                <el-radio-button label="未激活"></el-radio-button>
              </el-radio-group>
            </template>
            <template v-else-if="fiterField.type == 'datetime'">
              <span class="input-group-addon">{{ $t( fiterField.trans ) }}</span>
              <el-date-picker
                v-model="fiterField.val"
                type="daterange"
                :placeholder="$t( 'el.datepicker.selectDateRange' )">
              </el-date-picker>
            </template>
            <template v-else>
              <span class="input-group-addon">{{ $t( fiterField.trans ) }}</span>
              <input type="text" class="form-control" :name="fiterField.name" v-model="fiterField.val" :placeholder="$t( fiterField.trans )">
            </template>
        </div>
      </form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="determine">确 定</el-button>
      </span>
    </el-dialog>
  </span>

</template>

<script>
  import server from '../config/api'
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
              dialogVisible: false,
          };
      },
      created() {
          let vm = this;
          axios.get(server.api.roles).then( response => {
            console.log(response);
              response.data.data.forEach(function (e){
                  vm.roles.push({value: e.name, label: e.display_name});
              });
          })
      },
      methods: {
          determine() {
              console.log(this.fiterFields);
              let str = '';
              let queryVal = {};
              this.fiterFields.forEach( function(e){
                  // str = str + '&' + e.name + '=' + e.val;
                  queryVal[e.name] = e.val;
              })
              console.log(queryVal);
              this.$router.push({ path: this.$route.fullPath, query: queryVal});
              console.log(str);
              //获取参数和 hash 的完整路径
              let path = this.$route.fullPath;
              console.log(path);
              // this.$router.push('/users?pageSize=20&id=5&name=&email=&role=&status=%E6%89%80%E6%9C%89&created_at=');
              this.dialogVisible = false;
              // this.$emit('loadData')
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
