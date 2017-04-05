<template lang="html">
  <el-tabs>
   <el-tab-pane label="错误日志" name="first">

   </el-tab-pane>
   <el-tab-pane label="访问日志" name="second">配置管理</el-tab-pane>
   <el-tab-pane label="操作日志" name="third">角色管理</el-tab-pane>
  </el-tabs>
</template>

<script>
export default {
  created() {
      axios.get('api/errorlog').then( response => {
          console.log(response);
      })
  }
}
</script>

<style lang="css">
</style>
