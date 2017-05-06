<template lang="html">
  <vue-form :title="$t('el.form.create_role')">
      <div slot="buttons">
          <router-link to="/roles" class="btn btn-default" exact>{{ $t('el.form.back') }}</router-link>
      </div>
      <div slot="content">
          <div class="row">
              <form class="form col-md-4 col-md-offset-4" role="form" @submit.prevent="validateBeforeSubmit">
                  <div class="form-group has-feedback" :class="{'has-error': nameFlags.invalid, 'has-success': nameFlags.valid}">
                      <label for="name">{{ $t('el.form.role') }}</label>
                      <input name="name" v-validate="'required'" type="text" class="form-control" id="name" :placeholder="$t('el.form.role')">
                      <!-- 图标 -->
                      <span v-show="nameFlags.dirty || nameFlags.invalid" class="glyphicon form-control-feedback"
                        :class="{'glyphicon-warning-sign': nameFlags.invalid, 'glyphicon-ok': nameFlags.valid}">
                      </span>
                      <!-- 图标END -->
                      <!-- 错误消息 -->
                      <span v-show="errors.has('name')" class="help-block">
                        <strong>{{ errors.first('name') }}</strong>
                      </span>
                      <!-- 错误消息END -->
                  </div>
                  <div class="form-group has-feedback" :class="{'has-error': displayNameFlags.invalid, 'has-success': displayNameFlags.valid}">
                      <label for="display_name">{{ $t('el.form.display_name') }}</label>
                      <input name="display_name" v-validate="'required|max:20'" type="text" class="form-control" id="display_name" :placeholder="$t('el.form.display_name')">
                      <!-- 图标 -->
                      <span v-show="displayNameFlags.dirty || displayNameFlags.invalid" class="glyphicon form-control-feedback"
                        :class="{'glyphicon-warning-sign': displayNameFlags.invalid, 'glyphicon-ok': displayNameFlags.valid}">
                      </span>
                      <!-- 图标END -->
                      <!-- 错误消息 -->
                      <span v-show="errors.has('display_name')" class="help-block">
                        <strong>{{ errors.first('display_name') }}</strong>
                      </span>
                      <!-- 错误消息END -->
                  </div>
                  <div class="form-group has-feedback" :class="{'has-error': descriptionFlags.invalid}">
                      <label for="description">{{ $t('el.form.description') }}</label>
                      <textarea name="description" v-validate="'max:255'" class="form-control" id="description" :placeholder="$t('el.form.description')"></textarea>
                      <!-- 错误消息 -->
                      <span v-show="errors.has('description')" class="help-block">
                        <strong>{{ errors.first('description') }}</strong>
                      </span>
                      <!-- 错误消息END -->
                  </div>
                  <div class="form-group">
                      <button type="submit" :disabled="!formDirty" class="btn btn-primary">{{ $t('el.form.create') }}</button>
                  </div>
              </form>
          </div>
      </div>
  </vue-form>
</template>

<script>
    import { stack_error } from '../../config/helper.js'
    import { mapFields } from 'vee-validate';

    export default {
        created() {
            //验证字段名称
            this.$validator.updateDictionary({
                  zh_CN: {
                      attributes: {
                          name: '角色名',
                          display_name: '显示名称',
                          description: '描述',
                      }
                  }
            });
        },
        computed: {
            ...mapFields({
                nameFlags: 'name',
                displayNameFlags: 'display_name',
                descriptionFlags: 'description',
            }),
            //表单是否有更改
            formDirty() {
              // are some fields dirty?
              return Object.keys(this.validataFields).some(key => this.validataFields[key].dirty);
            }
        },
        methods: {
          //提交之前验证所有表单信息
          validateBeforeSubmit(event) {
              let vm = this;
              vm.$validator.validateAll().then(() => {
                  vm.create(event);
              }).catch(() => {
                toastr.error(vm.$t('el.notification.submit_data_error'))
              });
            },
            create(event) {
                var formData = new FormData(event.target)

                axios.post('/api/role', formData , {
                    headers: {
                        'Authorization': 'Bearer ' + this.$store.state.access_token
                    }
                }).then((response) => {
                        toastr.success(this.$t('el.notification.create_role'))

                        this.$router.push('/roles')
                    }, (error) => {
                        if(error.response.status == 422){
                          stack_error(error.response.data)
                        }else{
                          toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                        }
                    })
            }
        }
    }
</script>

<style lang="css">
</style>
