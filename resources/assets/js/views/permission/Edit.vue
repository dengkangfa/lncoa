<template lang="html">
  <vue-form :title="$t('el.form.edit_permission')">
        <div slot="buttons">
            <router-link to="/permissions" class="btn btn-default" exact>{{ $t('el.form.back') }}</router-link>
        </div>
        <div slot="content">
            <div class="row">
                <form class="form col-md-4 col-md-offset-4" role="form" @submit.prevent="edit">
                    <div class="form-group">
                        <label for="role">{{ $t('el.form.permission') }}</label>
                        <input type="text" class="form-control" id="role" :placeholder="$t('el.form.permission')" name="name" v-model="permission.name" disabled>
                    </div>
                    <div class="form-group">
                        <label for="display_name">{{ $t('el.form.display_name') }}</label>
                        <input type="text" class="form-control" id="display_name" :placeholder="$t('el.form.display_name')" name="display_name" v-model="permission.display_name">
                    </div>
                    <div class="form-group">
                        <label for="description">{{ $t('el.form.description') }}</label>
                        <textarea class="form-control" name="description" id="description" :placeholder="$t('el.form.description')" v-model="permission.description"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info">{{ $t('el.form.edit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </vue-form>
</template>

<script>
export default {
  data() {
      return {
          permission: {}
      }
  },
  created() {
      axios.get('/api/permission/' + this.$route.params.id + '/edit', {
          headers: {
              'Authorization': 'Bearer ' + this.$store.state.access_token
          }
      }).then((response) => {
              this.permission = response.data.data
          })
  },
  methods: {
      edit() {
          axios.put('/api/permission/' + this.$route.params.id, this.permission, {
              headers: {
                  'Authorization': 'Bearer ' + this.$store.state.access_token
              }
          }).then((response) => {
                  toastr.success('You updated a new account information!')

                  this.$router.push('/permissions')
              })
      }
  }
}
</script>
