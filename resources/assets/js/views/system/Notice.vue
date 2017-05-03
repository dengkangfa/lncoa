<template lang="html">
  <vue-form :title="$t('el.form.edit_notice')">
      <div slot="buttons" class='pull-right'>
        <el-switch
          v-model="status"
          @change="change"
          :disabled="is_disabled"
          on-text=""
          off-text="">
        </el-switch>
      </div>
      <div slot="content">
          <div class="row">
              <form class="form col-md-10 col-md-offset-1" @submit.prevent="edit">
                  <div class="form-group text-center">
                    <h3>{{ $t('el.form.notice') }}</h3>
                  </div>
                  <div class="form-group">
                      <textarea id="editor" name="content"></textarea>
                  </div>
                  <div class="form-group">
                      <button type="submit" class="btn btn-primary">{{ isEdit ? $t('el.form.edit') : $t('el.form.create') }}</button>
                  </div>
              </form>
          </div>
        </div>
    </vue-form>
</template>

<script>
  import { default as SimpleMDE } from 'simplemde/dist/simplemde.min.js'
  export default {
      data() {
          return {
              simplemde: {},
              comment: {},
              status: true,
              isEdit: true,
              count: 0,
              is_disabled: false,
          }
      },
      mounted() {
            //编辑器初始化
            this.simplemde = new SimpleMDE({
                element: document.getElementById("editor"),
                placeholder: 'Please input the comment content.',
                autoDownloadFontAwesome: true
            })
            axios.get('/api/notice').then(response => {
                this.simplemde.value(response.data.content);
                //没有数据则显示按钮为新增，反之修改
                this.isEdit = Boolean(response.data.content);
                this.status = Boolean(response.data.enabled);
            })
        },
        methods: {
            edit() {
                let vm = this;
                //获取编辑器内容
                vm.comment.content = vm.simplemde.value()

                //判断内容是否为空
                if (vm.comment.content == '') {
                  toastr.error('The content is required!')
                  return
                }
                axios.post('/api/notice', vm.comment).then(response => {
                    toastr.success(vm.$t('el.notification.update_notice'));
                    //跳转至模板
                    vm.$router.push('/');
                })
            },
            change(val) {
                this.count += 1;
                if(this.count == 5) {
                    this.is_disabled = true;
                }
                axios.put('/api/notice/enabled', {'enabled' : val}).then(response => {
                    console.log(response);
                })
            }
        }
  }
</script>

<style lang="css">
  .editor-toolbar.fullscreen {
        z-index: 1000 !important;
    }

  .CodeMirror-fullscreen {
      z-index: 1000 !important;
  }
</style>
