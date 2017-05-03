<template lang="html">
  <div class="row">
           <div class="col-sm-3">
               <div class="ibox float-e-margins">
                   <div class="ibox-content">
                       <div class="file-manager">
                         <!-- 存储空间使用情况 -->
                           <div class="my-progress">
                              <div class="my-progress-bar">
                                  <div class="my-progress-bar__outer">
                                      <div class="my-progress-bar__inner" :style="{width : proportion + '%'}">
                                          <div class="my-progress-bar__innerText">{{ proportion_text }}</div>
                                      </div>
                                  </div>
                              </div>
                           </div>
                           <h5>{{ $t('el.page.display') }}：</h5>
                           <a href="javascript:;" class="file-control" :class="{active:index == showType}" @click="updateShowType(index)" v-for="(type, index) in types">{{ type }}</a>
                           <div class="hr-line-dashed"></div>
                           <button class="btn btn-primary btn-block" @click="showFile = true">{{ $t('el.form.upload_file') }}</button>
                           <button class="btn btn-primary btn-block" @click="showFolder = true">{{ $t('el.form.create_folder') }}</button>
                           <div class="hr-line-dashed"></div>
                           <h5>{{ $t('el.page.folder') }}</h5>
                           <ul class="folder-list" style="padding: 0">
                              <li v-show="upload.breadcrumbs && upload.folderName != 'root'">
                                 <a href="javascript:;" @click="getFileInfo(ParentDirectory(upload.breadcrumbs))">
                                 <i class="ion-folder"></i> ../</a>
                              </li>
                              <li v-for="(name,index) in upload.subfolders">
                                 <a href="javascript:;" @click="getFileInfo(name)">
                                 <i class="ion-folder"></i> {{name}}</a>
                                 <i class="delete-folder ion-trash-a" @click="deleteFolder(name)"></i>
                              </li>
                           </ul>
                           <!-- <h5 class="tag-title">标签</h5>
                           <ul class="tag-list" style="padding: 0">
                               <li><a href="file_manager.html">爱人</a>
                               </li>
                               <li><a href="file_manager.html">工作</a>
                               </li>
                               <li><a href="file_manager.html">家庭</a>
                               </li>
                               <li><a href="file_manager.html">孩子</a>
                               </li>
                               <li><a href="file_manager.html">假期</a>
                               </li>
                               <li><a href="file_manager.html">音乐</a>
                               </li>
                               <li><a href="file_manager.html">照片</a>
                               </li>
                               <li><a href="file_manager.html">电影</a>
                               </li>
                           </ul> -->
                           <div class="clearfix"></div>
                       </div>
                   </div>
               </div>
           </div>
           <modal :show="showFolder" @confirm="newFolder" @cancel="showFolder = false" show-footer>
               <div slot="title">{{ $t('el.form.create_folder') }}</div>
               <form class="form-horizontal" role="form">
                   <div class="form-group">
                       <label for="folder" class="control-label col-sm-3">{{ $t('el.form.folder_name') }}</label>
                       <div class="col-sm-8">
                           <input type="text" id="folder" class="form-control" v-model="folder" :placeholder="$t('el.form.folder_name')">
                       </div>
                   </div>
               </form>
           </modal>
           <modal :show="showFile" @confirm="uploadFile" @cancel="showFile = false" show-footer>
               <div slot="title">{{ $t('el.form.upload_file') }}</div>
               <form class="form-horizontal" role="form" enctype="multipart/form-data">
                   <div class="form-group">
                       <label for="file" class="control-label col-sm-3">{{ $t('el.form.file') }}</label>
                       <div class="col-sm-8">
                           <input type="file" id="file" name="file" @change="change" class="form-control">
                       </div>
                   </div>
                   <div class="form-group">
                       <label for="file_name" class="control-label col-sm-3">{{ $t('el.form.file_name') }}</label>
                       <div class="col-sm-8">
                           <input type="text" id="file_name" class="form-control" name="file_name" v-model="file_name" :placeholder="$t('el.form.file_name')">
                       </div>
                   </div>
               </form>
           </modal>
           <!-- 文件信息 -->
           <div class="col-sm-9 animated fadeInRight" style="margin-top: -15px;">
               <div class="row">
                   <div class="col-sm-12">
                       <div class="file-box"
                        v-for="(file, index) in upload.files"
                        v-if="isShow(file.mimeType)">
                           <div class="file">
                               <a :href="file.webPath" target="_blank">
                                   <span class="corner"></span>
                                   <div class="icon">
                                      <!-- 文件预览 -->
                                       <template v-if="file.mimeType">
                                         <div class="image" v-if="file.mimeType.indexOf('image') > -1">
                                           <img alt="image" class="img-responsive" :src="file.webPath">
                                         </div>
                                          <i class="img-responsive ion-ios-film-outline" v-if="file.mimeType.indexOf('video') > -1"></i>
                                          <i class="img-responsive ion-music-note" v-else-if="file.mimeType.indexOf('audio') > -1"></i>
                                          <i class="img-responsive ion-stats-bars" v-else-if="file.mimeType.indexOf('excel') > -1"></i>
                                          <i class="img-responsive ion-document" v-else></i>
                                       </template>
                                       <template v-else>
                                         <i class="img-responsive ion-document"></i>
                                       </template>
                                   </div>
                                 </a>
                                   <div class="file-name">
                                       <p v-show="!showRemoveFileName" @dblclick="renameFileName($event)">{{file.name}}</p>
                                       <input
                                       class="form-control"
                                       style="height:20px"
                                       @blur="renameFileNameBlur($event, file)"
                                       @keyup.enter="blur($event)"
                                       @keyup.esc="revoked(file)"
                                       v-show="false"
                                       v-focus
                                       type="text"
                                       name=""
                                       v-model="file.name">
                                       <br/>
                                       <small>{{$t('el.page.add_at') + ':' + file.modified.split(' ')[0]}}</small>
                                       <div class="i-btn">
                                         <i class="delete-file ion-trash-a" @click="deleteFile(file, index)"></i>
                                       </div>
                                   </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
</template>

<script>
  import Modal from '../../components/Modal.vue'
  import env from '../../config/.env.js'
  const size_suffix = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];

  export default {
      components: {
          Modal
      },
      data() {
          return {
              showFile: false,
              showFolder: false,
              showRemoveFileName: false,
              files: null,
              file_name: '',
              folder: '',
              upload: {},
              showType: 'all',
              oldValue: '',
              digital: 0,
              proportion: '0',
              proportion_text: '',
              file_system_digital_size: 0,
              types: {
                  'all': this.$t('el.page.display_all'),
                  'image': this.$t('el.page.display_img'),
                  'video': this.$t('el.page.display_video'),
                  'audio': this.$t('el.page.display_audio'),
                  'excel': this.$t('el.page.display_document'),
              }
          }
      },
      mounted() {
          this.getFileInfo(this.$route.query.folder)
          this.getFileSystemSize();
      },
      // 自定义指令
      directives: {
        focus: {
          update: function (el) {
            el.focus();
          }
        }
      },
      methods: {
          getFileSystemSize() {
              this.file_system_digital_size = this.humanTurnDigital(env.user.filesystem);
              axios.get('/api/user/filesystemsize').then( response => {
                  this.digital = response.data.digital;
                  this.proportion = response.data.digital / this.file_system_digital_size * 100;
                  this.proportion_text = response.data.human + '/' + env.user.filesystem
              }, error => {
                  toastr.error(error.response.status + ' : Resource ' + error.response.statusText);
              });
          },
          // 上传文件
          uploadFile() {
              //判断文件是否为空
              if (!this.files) {
                  toastr.error('The file must be required')
                  return
              }

              //获取表单数据
              const formData = new FormData()

              formData.append('file', this.files[0])
              formData.append('name', this.file_name)
              formData.append('folder', this.upload.folder)

              axios.post('/api/user/upload', formData)
                  .then((response) => {
                      toastr.success(this.$t('el.notification.create_file'));

                      this.upload.files.push(response.data)
                      this.digital += this.humanTurnDigital(response.data.size);
                      this.proportion = this.digital / this.file_system_digital_size * 100;
                      this.proportion_text = this.digitalTurnHuman(this.digital) + '/' + env.user.filesystem;
                      this.file_name = ''
                      this.showFile = false
                  }, (error) => {
                      if (error.response.data.error) {
                          toastr.error(error.response.data.error.message)
                      } else {
                          toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                      }
                  })
          },
          change(event) {
            this.files = event.target.files.length ? event.target.files : '';
          },
          getFileInfo(path) {
              //获取当前目录下的文件信息
              var url = '/api/user/upload'

              if (path) {
                  url = url + '?folder=' + path
              } else {
                  path = '/'
              }

              axios.get(url)
                  .then((response) => {
                      this.upload = response.data.data
                      if (this.upload.subfolders instanceof Array) {
                          this.upload.subfolders = {}
                      }
                      this.$router.push(this.$route.path + '?folder=' + path)
                  })
          },
          newFolder() {
            //判断文件夹名称是否为空
            if (!this.folder) {
                toastr.error(this.$t('el.notification.folder_required'));
                return
            }
            //root为每个用户的根目录，子目录不可使用该名称
            if (this.folder == 'root') {
                toastr.error(this.$t('el.notification.folder_reserved'));
                return
            }

            //不能在创建文件夹的同时创建子文件夹
            if (this.folder.indexOf('/') > -1 || this.folder.indexOf('\\') > -1) {
                toastr.error(this.$t('el.notification.folder_no_subdirectory'));
                return
            }

            //创建新文件夹的路径
            this.path = this.upload.folder + '/' + this.folder

            axios.post('/api/user/folder', { folder: this.path })
                .then((response) => {
                    toastr.success(this.$t('el.notification.create_folder'));

                    this.showFolder = false
                    this.$set(this.upload.subfolders, this.path, this.folder)
                    this.folder = ''
                }, (error) => {
                    toastr.error(error.response.status + ' : ' + error.response.statusText)
                })
          },
          //获取父级目录名称
          ParentDirectory(data) {
              let arr = [];
              for (let i in data) {
                  arr.push(data[i]);
              }
              if(arr[arr.length-1] == 'root'){
                  return '/';
              }
              return arr[arr.length-1];
          },
          //修改显示类型
          updateShowType(type) {
              this.showType = type;
          },
          //是否当前想要显示的类型
          isShow(type) {
            if(this.showType == 'all'){
               return true;
            }else if(type && type.indexOf(this.showType) > -1){
                return true;
            }else{
                return false;
            }
          },
          //删除目录
          deleteFolder(name) {
              axios.post('/api/folder/delete', { del_folder: name, folder: this.upload.folder })
                  .then(response => {
                      toastr.success(this.$t('el.notification.delete_folder'))

                      //从subfolders数组中删除
                      this.$delete(this.upload.subfolders, this.upload.folder + '/' + name)
                  }, error => {
                      toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                  })
          },
          //删除文件
          deleteFile(file, index) {
              axios.post('/api/user/file/delete', { path: file.fullPath })
                  .then(response => {
                      toastr.success(this.$t('el.notification.delete_file'))

                      this.upload.files.splice(index, 1)
                      this.digital -= this.humanTurnDigital(file.size);
                      this.proportion = this.digital / this.file_system_digital_size * 100;
                      this.proportion_text = this.digitalTurnHuman(this.digital) + '/' + env.user.filesystem;
                  }, error => {
                      toastr.error(error.status + ' : Resource ' + error.statusText)
                  })
          },
          //双击触发文件重命名控件
          renameFileName(el) {
              let inputElement = el.target.parentNode.children[1];
              inputElement.style.display = 'inline-block';
              this.oldValue = inputElement.value;
              inputElement.focus();
              el.currentTarget.style.display = 'none';
          },
          blur(el) {
            let pElement = el.target.parentNode.children[0];
            pElement.style.display = 'inline-block';
            el.currentTarget.style.display = 'none';
          },
          //文件重命名控件失去焦点调用函数
          renameFileNameBlur(el, file) {
              let pElement = el.target.parentNode.children[0];
              pElement.style.display = 'inline-block';
              el.currentTarget.style.display = 'none';
              if(file.name != this.oldValue) {
                  this.rename(file);
              }
              this.oldValue = '';
          },
          //撤销更改
          revoked(file) {
              file.name = this.oldValue;
          },
          //文件重命名
          rename(file) {
              let formData = new FormData();
              let newfilename = this.upload.folder + '/' + file.name;
              formData.append('oldfilename', file.fullPath);
              formData.append('newfilename', newfilename);
              axios.post('api/user/file/rename', formData).then( response => {
                  file.fullPath = newfilename;
                  toastr.success(this.$t('el.notification.file_rename'))
              }, error => {
                  toastr.error(error.response.status + ' : ' + error.response.statusText)
              })
          },
          //获取当前存储大小对应的后缀下标
          sizeIndex(current_suffix) {
              for (var i = 0; i < size_suffix.length; i++) {
                    if(size_suffix[i] == current_suffix){
                        return i;
                    }
              }
          },
          //human转digital
          humanTurnDigital(size) {
              //转成浮动型(目的是要当前存储大小的单位)
              let number_size = parseFloat(size);
              //数值存在浮动型以及整型，获取其单位后缀
              let current_suffix = size.indexOf('.') > -1 ? size.substring(size.indexOf('.')+3) : size.replace(number_size,'');
              //后缀对应的下标
              let i = this.sizeIndex(current_suffix);
              return number_size * Math.pow(1024, i);
          },
          //digital转human
          digitalTurnHuman(size) {
              //保留整数部分
              let intSize = parseInt(size);
              let floor = Math.floor((String(intSize).length-1)/3);
              return (intSize/Math.pow(1024, floor)).toFixed(2) + size_suffix[floor];
          }
      }
  }
</script>

<style lang="scss" scoped>
  a:hover, a:focus {
      text-decoration: none;
  }
  a:focus {
      outline: none;
  }
  a:focus {
      outline: thin dotted;
      outline: 5px auto -webkit-focus-ring-color;
      outline-offset: -2px;
  }
  a:active, a:hover {
      outline: 0;
  }
  a {
      cursor: pointer;
  }
  a {
      color: #337ab7;
      text-decoration: none;
  }
  ol, ul {
      margin-top: 0;
      margin-bottom: 10px;
  }
  h3, h4, h5 {
      margin-top: 5px;
      font-weight: 600;
  }
  h5 {
      font-size: 12px;
  }
  .file-manager {
      list-style: none outside none;
      margin: 0;
      padding: 0;
      .my-progress {
          position: relative;
          line-height: 1;
      }
      .my-progress-bar {
          padding-right: 0;
          margin-right: 0;
          vertical-align: middle;
          width: 100%;
          margin-right: -55px;
          box-sizing: border-box;
      }
      .my-progress-bar__outer {
          height: 18px;
          border-radius: 100px;
          background-color: #e4e8f1;
          overflow: hidden;
          position: relative;
          vertical-align: middle;
      }
      .my-progress-bar__inner {
          position: absolute;
          left: 0;
          top: 0;
          height: 100%;
          background-color: #20a0ff;
          text-align: right;
          border-radius: 100px;
          line-height: 1;
      }
      .my-progress-bar__innerText {
          display: inline-block;
          vertical-align: middle;
          color: #fff;
          font-size: 12px;
          margin: 0 5px;
      }
  }

  .row {
      margin-left: -15px !important;
      margin-right: -15px !important;
  }

  .file-control.active {
      text-decoration: underline;
  }
  .file-control {
      color: inherit;
      font-size: 11px;
      margin-right: 10px;
  }
  .file-manager .hr-line-dashed {
      margin: 15px 0;
  }
  .hr-line-dashed {
      border-top: 1px dashed #e7eaec;
      color: #ffffff;
      background-color: #ffffff;
      height: 1px;
      margin: 20px 0;
  }
  .float-e-margins .btn {
      margin-bottom: 5px;
  }
  .folder-list li {
      height: 30px;
      line-height: 30px;
      border-bottom: 1px solid #e7eaec;
      display: block;
  }
  .folder-list li a {
      color: #666666;
      padding: 5px 0;
  }
  .folder-list li i {
      margin-right: 8px;
      color: #3d4d5d;
  }
  .folder-list li .delete-folder {
      cursor: pointer;
      float: right;
  }
  .file-box .file img{
      height: 100px;
      width: 250px;
  }
  .file-manager h5.tag-title {
      margin-top: 20px;
  }
  ul.notes li, ul.tag-list li {
      list-style: none;
  }
  .tag-list li {
      float: left;
  }
  .tag-list li a {
      font-size: 10px;
      background-color: #f0f3f4;
      padding: 5px 12px;
      color: inherit;
      border-radius: 2px;
      border: 1px solid #e7eaec;
      margin-right: 5px;
      margin-top: 5px;
      display: block;
  }

  .file-box {
    float: left;
    width: 220px;
  }
  .file {
      border: 1px solid #e7eaec;
      padding: 0;
      background-color: #ffffff;
      position: relative;
      margin-bottom: 20px;
      margin-right: 20px;
  }
  /*文件方框右下角*/
  .corner {
      position: absolute;
      display: inline-block;
      width: 0;
      height: 0;
      line-height: 0;
      border: 0.6em solid transparent;
      border-right: 0.6em solid #f1f1f1;
      border-bottom: 0.6em solid #f1f1f1;
      right: 0em;
      bottom: 0em;
  }
  .file .icon {
      text-align: center;
  }
  .file .icon, .file .image {
      height: 100px;
      overflow: hidden;
  }
  .file .file-name {
      padding: 0px 10px 10px 10px;
      background-color: #f8f8f8;
      border-top: 1px solid #e7eaec;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
  }
  .file .file-name p {
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      width: 150px;
      display: inline-block;
      padding: -15px;
      margin: -12px 0;
  }
  .file .icon i {
      font-size: 70px;
      color: #dadada;
  }
  .file .file-name .delete-file {
      cursor: pointer;
      color: #a6aaad;
  }
  .file .file-name .update-file {
      cursor: pointer;
      color: #a6aaad;
  }
  .file .file-name .i-btn {
      display: inline-block;
      float: right;
  }
  input[type="file"] {
      display: block;
  }
</style>
