<template lang="html">
      <div class="row">
          <vue-table :title="$t('el.page.role')" :fields="fields" api-url="/api/role?include=menus" @table-action="tableActions" show-paginate>
              <div slot="buttons">
                  <router-link to="/roles/create" class="btn btn-info btn-xs"  style="margin-bottom:2px">
                    {{ $t('el.page.create') }}
                  </router-link>
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
                    trans: 'el.table.id',
                    titleClass: 'width-5-percent text-center',
                    dataClass: 'text-center'
                },
                {
                    name: 'name',
                    trans: 'el.table.role'
                },
                {
                    name: 'display_name',
                    trans: 'el.table.display_name'
                },
                {
                    name: '__permission_menu',
                    trans: 'el.table.permission_menu'
                },
                {
                    name: 'description',
                    trans: 'el.table.description'
                },
                {
                    name: 'created_at',
                    trans: 'el.table.created_at'
                },
                {
                    name: '__actions',
                    trans: 'el.table.action',
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
                  axios.delete('/api/role/' + data.id, {
                      headers: {
                          'Authorization': 'Bearer ' + this.$store.state.access_token
                      }
                   }).then((response) => {
                          toastr.success('You delete the tag success!')

                          this.$emit('reload')
                      }, (error) => {
                          toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                      })
              }
          }
        }
}
</script>

<style lang="css">
</style>
