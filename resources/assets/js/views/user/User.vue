<template>
    <div class="row">
        <vue-table :title="$t('el.page.users')" :fields="fields" api-url="/api/users?include=roles" @table-action="tableActions" show-paginate>
            <div slot="buttons">
                <vue-table-filtra :fiterFields="fiterFields"></vue-table-filtra>
                <router-link to="/users/create" class="btn btn-info btn-xs"  style="margin-bottom:2px">
                  {{ $t('el.page.create') }}
                </router-link>
            </div>
        </vue-table>
    </div>
</template>

<script>
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
                        val: ''
                    },
                    {
                        name: 'name',
                        trans: 'el.table.name',
                        type: 'text',
                        val: ''
                    },
                    {
                        name: 'email',
                        trans: 'el.table.email',
                        type: 'text',
                        val: ''
                    },
                    {
                        name: 'role',
                        trans: 'el.table.role',
                        type: 'select',
                        val: ''
                    },
                    {
                        name: 'status',
                        trans: 'el.table.status',
                        type: 'radio',
                        val: '所有'
                    },
                    {
                        name: 'created_at',
                        trans: 'el.table.created_at',
                        type: 'datetime',
                        val: ''
                    }
                ]
            }
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
                    }).then((response) => {
                            toastr.success('You delete the user success!')

                            this.$emit('reload')
                        }, (response) => {
                            if ((typeof response.data.error !== 'string') && response.data.error) {
                                toastr.error(response.data.error.message)
                            } else {
                                toastr.error(response.status + ' : Resource ' + response.statusText)
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
