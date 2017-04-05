<template lang="html">
  <div class="row">
              <div class="col-sm-12">

                  <div class="ibox">
                      <div class="ibox-title">
                          <h5>所有项目</h5>
                          <div class="ibox-tools">
                              <a href="projects.html" class="btn btn-primary btn-xs">创建新项目</a>
                          </div>
                      </div>
                      <div class="ibox-content">
                          <div class="row m-b-sm m-t-sm">
                              <div class="col-md-1">
                                  <button type="button" id="loading-example-btn" class="btn btn-white btn-sm"><i class="fa fa-refresh"></i> 刷新</button>
                              </div>
                              <div class="col-md-11">
                                  <div class="input-group">
                                      <input type="text" placeholder="请输入项目名称" class="input-sm form-control"> <span class="input-group-btn">
                                          <button type="button" class="btn btn-sm btn-primary"> 搜索</button> </span>
                                  </div>
                              </div>
                          </div>

                          <div class="project-list">

                              <table class="table table-hover">
                                  <tbody>
                                      <tr>
                                          <td class="project-status">
                                              <span class="label label-primary">进行中
                                          </td>
                                          <td class="project-title">
                                              <a href="project_detail.html">LIKE－一款能够让用户快速获得认同感的兴趣社交应用</a>
                                              <br/>
                                              <small>创建于 2014.08.15</small>
                                          </td>
                                          <td class="project-completion">
                                                  <small>当前进度： 48%</small>
                                                  <div class="progress progress-mini">
                                                      <div style="width: 48%;" class="progress-bar"></div>
                                                  </div>
                                          </td>
                                          <td class="project-people">
                                              <a href="projects.html"><img alt="image" class="img-circle" src="img/a3.jpg"></a>
                                              <a href="projects.html"><img alt="image" class="img-circle" src="img/a1.jpg"></a>
                                              <a href="projects.html"><img alt="image" class="img-circle" src="img/a2.jpg"></a>
                                              <a href="projects.html"><img alt="image" class="img-circle" src="img/a4.jpg"></a>
                                              <a href="projects.html"><img alt="image" class="img-circle" src="img/a5.jpg"></a>
                                          </td>
                                          <td class="project-actions">
                                              <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i> 查看 </a>
                                              <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> 编辑 </a>
                                          </td>
                                      </tr>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
</template>

<script>
export default {
}
</script>

<style lang="css">
</style>
