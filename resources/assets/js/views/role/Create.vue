<template lang="html">
  <vue-form :title="$t('form.create_role')">
      <div slot="buttons">
          <router-link to="/roles" class="btn btn-default" exact>{{ $t('form.back') }}</router-link>
      </div>
      <div slot="content">
          <div class="row">
              <form class="form col-md-4 col-md-offset-4" role="form" @submit.prevent="create">
                  <div class="form-group">
                      <label for="name">{{ $t('form.role') }}</label>
                      <input type="text" class="form-control" id="name" :placeholder="$t('form.role')" name="name">
                  </div>
                  <div class="form-group">
                      <label for="display_name">{{ $t('form.display_name') }}</label>
                      <input type="text" class="form-control" id="display_name" :placeholder="$t('form.display_name')" name="display_name">
                  </div>
                  <div class="form-group">
                      <label for="description">{{ $t('form.description') }}</label>
                      <textarea class="form-control" name="description" id="description" :placeholder="$t('form.description')"></textarea>
                  </div>
                  <div class="form-group">
                      <button type="submit" class="btn btn-primary">{{ $t('form.create') }}</button>
                  </div>
              </form>
          </div>
      </div>
  </vue-form>
</template>

<script>
import { stack_error } from '../../config/helper.js'

export default {
    methods: {
        create(event) {
            var formData = new FormData(event.target)

            this.$http.post('/api/role', formData , {
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.access_token
                }
            }).then((response) => {
                    toastr.success('You created a new tag success!')

                    this.$router.push('/roles')
                }, (response) => {
                    stack_error(response.data)
                })
        }
    }
}
</script>

<style lang="css">
</style>
