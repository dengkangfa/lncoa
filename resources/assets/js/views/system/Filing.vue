<template>
    <div class="row">
        <div class="ibox">
            <div class="ibox-title">
                <div class="row no-margin">
                    <div class="col-md-6">
                        <h4 class="pull-left">{{ $t('el.page.files') }}&nbsp;&nbsp;</h4>
                        <div class="pull-left">
                            <ul class="breadcrumb">
                                <li v-for="(disp, path) in upload.breadcrumbs">
                                    <a href="javascript:;" @click="getFileInfo(path)">
                                        {{ disp }}
                                    </a>
                                </li>
                                <li class="active">{{ upload.folderName }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <small class="pull-right" style="margin-top: 7px;">
                            <button type="button" class="btn btn-success btn-sm" @click="showFolder = true">
                                <i class="ion-ios-plus"></i> {{ $t('el.table.new_folder') }}
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" @click="showFile = true">
                                <i class="ion-ios-filing-outline"></i> {{ $t('el.table.upload') }}
                            </button>
                        </small>
                    </div>
                </div>
            </div>
            <div class="ibox-content table-responsive no-padding">
                <table id="uploads-table" class="table table-striped table-hover">
                    <tbody>
                    <tr>
                        <th>{{ $t('el.table.name') }}</th>
                        <th>{{ $t('el.table.type') }}</th>
                        <th>{{ $t('el.table.date') }}</th>
                        <th>{{ $t('el.table.size') }}</th>
                        <th>{{ $t('el.table.action') }}</th>
                    </tr>

                    <!--Sub Directory-->
                    <tr v-for="(name, path) in upload.subfolders">
                        <td>
                            <a href="javascript:;" @click="getFileInfo(path)">
                                <i class="ion-filing"></i>
                                {{ name }}
                            </a>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                            <button type="button" class="btn btn-danger" @click="deleteFolder(name)">
                                <i class="ion-trash-b"></i>
                            </button>
                        </td>
                    </tr>

                    <!--All Files-->
                    <tr v-for="(file, index) in upload.files">
                        <td>
                            <a target="_blank" :href="file.webPath">
                                <i class="ion-image" v-if="file.mimeType?checkImageType(file.mimeType):false"></i>
                                <i class="ion-document-text" v-else></i>
                                {{ file.name }}
                            </a>
                        </td>
                        <td>{{ file.mimeType }}</td>
                        <td>{{ file.modified }}</td>
                        <td>{{ file.size }}</td>
                        <td>
                            <button type="button" class="btn btn-info" v-if="file.mimeType?checkImageType(file.mimeType):false" @click="preview(file.webPath)">
                                <i class="ion-eye"></i>
                            </button>
                            <button type="button" class="btn btn-danger" @click="deleteFile(file, index)">
                                <i class="ion-trash-b"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <modal :show="showFolder" @confirm="confirm" @cancel="showFolder = false" show-footer>
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
        <modal :show="showImage" @confirm="confirm" @cancel="showImage = false">
            <div slot="title">{{ $t('el.form.image') }}</div>
            <form class="form-horizontal" role="form">
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <img :src="preview_image" class="preview-size">
                    </div>
                </div>
            </form>
        </modal>
    </div>
</template>

<script>
    import Modal from '../../components/Modal.vue'

    export default {
        components: {
            Modal
        },
        data() {
            return {
                folder: '',
                files: null,
                file_name: '',
                path: '',
                upload: {},
                showFolder: false,
                showFile: false,
                showImage: false,
                preview_image: '',
                fields: [
                    {
                        name: 'name',
                        title: 'ID',
                        titleClass: 'text-center',
                        dataClass: 'text-center'
                    },
                    {
                        name: 'user',
                        title: 'User Name',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'username'
                    },
                    {
                        name: 'title',
                        title: 'Title'
                    },
                    {
                        name: "content",
                        title: 'Content',
                        callback: 'content'
                    },
                    {
                        name: 'status',
                        title: 'Status',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'status'
                    },
                    {
                        name: 'created_at',
                        title: 'Created At'
                    },
                    {
                        name: '__actions',
                        dataClass: 'text-center'
                    }
                ]
            }
        },
        mounted() {
            this.getFileInfo(this.$route.query.folder)
        },
        methods: {
            preview(path) {
                this.showImage = true
                this.preview_image = path
            },
            confirm() {
                //判断
                if (!this.folder) {
                    toastr.error('The folder name must be required!')
                    return
                }

                const path = (this.upload.folder == '/') ? '' : this.upload.folder

                this.path = path + '/' + this.folder

                axios.post('/api/folder', { folder: this.path })
                    .then((response) => {
                        toastr.success('You create a new folder success!')

                        this.showFolder = false
                        this.$set(this.upload.subfolders, this.path, this.folder)
                        this.folder = ''
                    }, (error) => {
                        toastr.error(error.response.status + ' : ' + error.response.statusText)
                    })

            },
            change(event) {
              this.files = event.target.files.length ? event.target.files : '';
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

                axios.post('/api/upload', formData)
                    .then((response) => {
                        toastr.success('You upload a file success!')

                        this.upload.files.push(response.data)
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
            //删除目录
            deleteFolder(name) {
                const path = (this.upload.folder == '/') ? '' : this.upload.folder
                axios.post('/api/folder/delete', { del_folder: name, folder: this.upload.folder })
                    .then((response) => {
                        toastr.success('You delete a folder success!')

                        this.$delete(this.upload.subfolders, path + '/' + name)
                    }, (error) => {
                        toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                    })
            },
            //删除文件
            deleteFile(file, index) {
                axios.post('/api/file/delete', { path: file.fullPath })
                    .then((response) => {
                        toastr.success('You delete a file success!')

                        this.upload.files.splice(index, 1)
                    }, (response) => {
                        toastr.error(response.status + ' : Resource ' + response.statusText)
                    })
            },
            getFileInfo(path) {
                var url = '/api/upload'

                if (path) {
                    url = url + '?folder=' + path
                } else {
                    path = '/'
                }

                axios.get(url)
                    .then((response) => {
                      console.log(response);
                        this.upload = response.data.data
                        if (this.upload.subfolders instanceof Array) {
                            this.upload.subfolders = {}
                        }
                        this.$router.push(this.$route.path + '?folder=' + path)
                    })
            },
            checkImageType(fileType) {
                if (fileType != '') {
                    return fileType.split('/')[0] == 'image'
                }

                return false
            }
        }
    }
</script>

<style scoped>
    .box-body button, .box-body button:hover {
        padding: 0;
        border-radius: 50%;
        width: 2.5em;
        height: 2.5em;
        outline: none;
    }
    /*图片显示大小*/
    .preview-size {
        max-width: 500px;
    }
    @media (max-width: 768px){
      .preview-size {
          max-width: 250px;
      }
    }
    input[type="file"] {
        display: block;
    }
</style>
