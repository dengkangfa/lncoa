<template lang="html">
      <div class="row">
          <vue-table :title="$t('page.role')" :fields="fields" api-url="/api/role" @table-action="tableActions" show-paginate>
              <div slot="buttons">
                  <router-link to="/roles/create" class="btn btn-success">{{ $t('page.create') }}</router-link>
              </div>
          </vue-table>
      </div>
</template>

<script>
export default {
    data() {
        return {
            fields: [
                {
                    name: 'id',
                    trans: 'table.id',
                    titleClass: 'width-5-percent text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'name',
                    trans: 'table.role'
                },
                {
                    name: 'display_name',
                    trans: 'table.display_name'
                },
                {
                    name: 'description',
                    trans: 'table.description'
                },
                {
                    name: 'created_at',
                    trans: 'table.created_at'
                },
                {
                    name: '__actions',
                    trans: 'table.action',
                    titleClass: 'text-center',
                    dataClass: 'text-center'
                }
            ]
        }
    },
    methods: {
          tableActions(action, data) {
              if (action == 'edit-item') {
                  this.$router.push('/roles/' + data.id + '/edit')
              } else if (action == 'delete-item') {
                  this.$http.delete('/api/role/' + data.id, {
                      headers: {
                          'Authorization': 'Bearer ' + this.$store.state.access_token
                      }
                   }).then((response) => {
                          toastr.success('You delete the tag success!')

                          this.$emit('reload')
                      }, (response) => {
                          toastr.error(response.status + ' : Resource ' + response.statusText)
                      })
              }
          }
        }
}
</script>

<style lang="css">
</style>
