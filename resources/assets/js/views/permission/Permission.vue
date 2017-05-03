<template lang="html">
  <div class="row">
      <vue-table :title="$t('el.page.permission')" :itemActions="itemActions" :fields="fields" api-url="/api/permission" @table-action="tableActions" show-paginate>
          <div slot="buttons">
              <router-link to="/permissions/create"
               class="btn btn-info btn-xs"
                :class="{ disabled: !createPermission }"
                  style="margin-bottom:2px">
                {{ $t('el.page.create') }}
              </router-link>
          </div>
      </vue-table>
  </div>
</template>

<script>
    import { mapState } from 'vuex'
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
                      trans: 'el.table.permission'
                  },
                  {
                      name: 'display_name',
                      trans: 'el.table.display_name'
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
              ],
              itemActions: [
                  { name: 'delete-item', icon: 'ion-trash-b', class: 'btn btn-danger disabled' },
                  { name: 'edit-item', icon: 'ion-edit', class: 'btn btn-info disabled' }
              ],
              createPermission: false
          }
      },
      created() {
          let vm = this;
          for (var i = 0; i < vm.permissions.length; i++) {
              if(vm.permissions[i].name == 'create-permission') {
                  vm.createPermission = true;
                  continue;
              }
              if(vm.permissions[i].name == 'delete-permission') {
                  vm.itemActions[0].class = 'btn btn-danger';
                  continue;
              }
              if(vm.permissions[i].name == 'update-permission') {
                  vm.itemActions[1].class = 'btn btn-info'
                  continue;
              }
          }
      },
      computed: {
          ...mapState([
              'permissions'
          ])
      },
      methods: {
            tableActions(action, data) {
                if (action == 'edit-item') {
                    this.$router.push('/permissions/' + data.id + '/edit')
                } else if (action == 'delete-item') {
                    axios.delete('/api/permission/' + data.id).then((response) => {
                            toastr.success(this.$t('el.notification.delete_permission'))

                            this.$emit('reload')
                        }, (error) => {
                            toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                        })
                }
            },
        }
    }
</script>
