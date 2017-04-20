<template>
    <div :class="isPhone ? '' : 'row'">
        <vue-table :title="$t('el.page.users')"
         :itemActions="itemActions"
          :fields="fields"
           api-url="/api/users?include=roles"
            @table-action="tableActions"
             show-paginate>
            <div slot="buttons">
                <vue-table-filtra :fiterFields="fiterFields"></vue-table-filtra>
                <router-link to="/users/create"
                 class="btn btn-info btn-xs"
                  :class="{ disabled: !createUser }"
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
        data () {
            return {
                fields: [
                    {
                        name: 'id',
                        trans: 'el.table.id',
                        titleClass: 'width-5-percent text-center',
                        dataClass: 'text-center'
                    },
                    {
                        name: 'avatar',
                        trans: 'el.table.avatar',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'avatar'
                    },
                    {
                        name: '__name',
                        trans: 'el.table.username'
                    },
                    {
                        name: 'mobile',
                        trans: 'el.table.mobile',
                        icon: 'ion-ios-telephone'
                    },
                    {
                        name: 'email',
                        trans: 'el.table.email',
                        icon: 'ion-email',
                        iconStyle:  'padding-right:5px'
                    },
                    {
                        name: '__component',
                        trans: 'el.table.status',
                        titleClass: 'text-center',
                        dataClass: 'text-center'
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
                fiterFields: [
                    {
                        name: 'id',
                        trans: 'el.table.id',
                        type: 'text',
                        value: ''
                    },
                    {
                        name: 'name',
                        trans: 'el.table.name',
                        type: 'text',
                        value: ''
                    },
                    {
                        name: 'email',
                        trans: 'el.table.email',
                        type: 'text',
                        value: ''
                    },
                    // {
                    //     name: 'role',
                    //     trans: 'el.table.role',
                    //     type: 'select',
                    //     val: ''
                    // },
                    {
                        name: 'status',
                        trans: 'el.table.status',
                        type: 'radio',
                        value: "所有"
                    },
                    {
                        name: 'created_at',
                        trans: 'el.table.created_at',
                        type: 'datetime',
                        value: []
                    }
                ],
                itemActions: [
                    { name: 'delete-item', icon: 'ion-trash-b', class: 'btn btn-danger disabled' },
                    { name: 'edit-item', icon: 'ion-edit', class: 'btn btn-info disabled' }
                ],
                createUser: false
            }
        },
        created() {
            let vm = this;
            for (var i = 0; i < vm.$store.state.permissions.length; i++) {
                if(vm.$store.state.permissions[i].name == 'create-user') {
                    vm.createUser = true;
                    continue;
                }
                if(vm.$store.state.permissions[i].name == 'delete-user') {
                    vm.itemActions[0].class = 'btn btn-danger';
                    continue;
                }
                if(vm.$store.state.permissions[i].name == 'update-user') {
                    vm.itemActions[1].class = 'btn btn-info'
                    continue;
                }
            }
        },
        computed: {
            ...mapState([
                'isPhone'
            ]),
        },
        // components: {
        //     TableFiltra
        // },
        methods: {
            avatar(value) {
                return '<img src="' + value + '" class="avatar img-responsive img-circle" />'
            },
            tableActions(action, data) {
                if (action == 'edit-item') {
                    this.$router.push('/users/' + data.id + '/edit')
                } else if (action == 'delete-item') {
                    axios.delete('/api/user/' + data.id, {
                        headers: {
                            'Authorization': 'Bearer ' + this.$store.state.access_token
                        }
                    }).then(response => {
                            toastr.success('You delete the user success!')
                            this.$emit('reload')
                        }, error => {
                          console.log(error.response);
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

<style lang="scss">
    .avatar {
        width: 50px;
        margin: 0 auto;
    }
</style>
