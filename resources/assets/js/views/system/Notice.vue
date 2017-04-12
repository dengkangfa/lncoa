<template lang="html">
  <vue-form :title="$t('el.form.edit_notice')">
      <div slot="buttons" class='pull-right'>
        <el-switch
          v-model="status"
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
              isEdit: true
          }
      },
      mounted() {
            this.simplemde = new SimpleMDE({
                element: document.getElementById("editor"),
                placeholder: 'Please input the comment content.',
                autoDownloadFontAwesome: true
            })
            axios.get('/api/notice').then(response => {
                this.simplemde.value(response.data);
                this.isEdit = Boolean(response.data);
            })
        },
        methods: {
            edit() {
                let vm = this;
                vm.comment.content = vm.simplemde.value()

                if (vm.comment.content == '') {
                  toastr.error('The content is required!')
                  return
                }
                axios.post('/api/notice', vm.comment).then(response => {
                    toastr.success('站点通告已更新');
                    vm.$router.push('/');
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
