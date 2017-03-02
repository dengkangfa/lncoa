<template lang="html">
  <vue-form :title="$t('form.edit_role')">
        <div slot="buttons">
            <router-link to="/roles" class="btn btn-default" exact>{{ $t('form.back') }}</router-link>
        </div>
        <div slot="content">
            <div class="row">
                <form class="form col-md-4 col-md-offset-4" role="form" @submit.prevent="edit">
                    <div class="form-group">
                        <label for="role">{{ $t('form.role') }}</label>
                        <input type="text" class="form-control" id="role" :placeholder="$t('form.role')" name="name" v-model="role.name" disabled>
                    </div>
                    <div class="form-group">
                        <label for="display_name">{{ $t('form.display_name') }}</label>
                        <input type="text" class="form-control" id="display_name" :placeholder="$t('form.display_name')" name="display_name" v-model="role.display_name">
                    </div>
                    <div class="form-group">
                        <label for="description">{{ $t('form.description') }}</label>
                        <textarea class="form-control" name="description" id="description" :placeholder="$t('form.description')" v-model="role.description"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info">{{ $t('form.edit') }}</button>
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
          role: {}
      }
  },
  created() {
      this.$http.get('/api/role/' + this.$route.params.id + '/edit')
          .then((response) => {
              this.role = response.data.data
          })
  },
  methods: {
      edit() {
          this.$http.put('/api/role/' + this.$route.params.id, this.role)
              .then((response) => {
                  toastr.success('You updated a new account information!')

                  this.$router.push('/roles')
              })
      }
  }
}
</script>
