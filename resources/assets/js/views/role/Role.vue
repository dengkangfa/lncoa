<template lang="html">
      <div class="row">
          <vue-table :title="$t('el.page.role')" :itemActions="itemActions" :fields="fields" api-url="/api/role?include=menus,permissions" @table-action="tableActions" show-paginate>
              <div slot="buttons">
                  <router-link to="/roles/create"
                   class="btn btn-info btn-xs"
                    :class="{disabled: !createRole}"
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
                ],
                itemActions: [
                    { name: 'delete-item', icon: 'ion-trash-b', class: 'btn btn-danger disabled' },
                    { name: 'edit-item', icon: 'ion-edit', class: 'btn btn-info disabled' }
                ],
                createRole: false,
            }
        },
        created() {
            let vm = this;
            //如果用户拥有相应的权限，则显示相应的功能按钮
            for (var i = 0; i < vm.permissions.length; i++) {
                if(vm.permissions[i].name == 'create-role') {
                    vm.createRole = true;
                    continue;
                }
                if(vm.permissions[i].name == 'delete-role') {
                    vm.itemActions[0].class = 'btn btn-danger';
                    continue;
                }
                if(vm.permissions[i].name == 'update-role') {
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
                  this.$router.push('/roles/' + data.id + '/edit')
              } else if (action == 'delete-item') {
                  axios.delete('/api/role/' + data.id, {
                      headers: {
                          'Authorization': 'Bearer ' + this.$store.state.access_token
                      }
                   }).then((response) => {
                          toastr.success(this.$t('el.notification.delete_role'));

                          //重新加载数据
                          this.$emit('reload')
                      }, (error) => {
                          if ((typeof error.response.data.error !== 'string') && error.response.data.error) {
                              toastr.error(error.response.data.error.message)
                          } else {
                              toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                          }
                      })
              }
          }
        }
    }
</script>

<style lang="css">
</style>
