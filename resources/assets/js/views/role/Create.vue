<template lang="html">
  <vue-form :title="$t('el.form.create_role')">
      <div slot="buttons">
          <router-link to="/roles" class="btn btn-default" exact>{{ $t('el.form.back') }}</router-link>
      </div>
      <div slot="content">
          <div class="row">
              <form class="form col-md-4 col-md-offset-4" role="form" @submit.prevent="create">
                  <div class="form-group">
                      <label for="name">{{ $t('el.form.role') }}</label>
                      <input type="text" class="form-control" id="name" :placeholder="$t('el.form.role')" name="name">
                  </div>
                  <div class="form-group">
                      <label for="display_name">{{ $t('el.form.display_name') }}</label>
                      <input type="text" class="form-control" id="display_name" :placeholder="$t('el.form.display_name')" name="display_name">
                  </div>
                  <div class="form-group">
                      <label for="description">{{ $t('el.form.description') }}</label>
                      <textarea class="form-control" name="description" id="description" :placeholder="$t('el.form.description')"></textarea>
                  </div>
                  <div class="form-group">
                      <button type="submit" class="btn btn-primary">{{ $t('el.form.create') }}</button>
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

            axios.post('/api/role', formData , {
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
