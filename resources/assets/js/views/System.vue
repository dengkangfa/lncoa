<template>
    <div class="row">
        <div class="ibox">
            <div class="ibox-title">
                <h5>{{ $t('el.page.systems') }}</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-2">
                        <div class="sidebar">
                            <ul>
                                <li aria-expanded="false" :class="[type == 'system' ? 'active' : '']" @click="type = 'system'">
                                    <a> <i class="ion-ios-toggle"></i>{{ $t('el.page.system') }}</a>
                                </li>
                                <li aria-expanded="true" :class="[type == 'php' ? 'active' : '']" @click="type = 'php'">
                                    <a><i class="ion-code"></i> PHP</a>
                                </li>
                                <li aria-expanded="false" :class="[type == 'database' ? 'active' : '']" @click="type = 'database'">
                                    <a><i class="ion-social-buffer"></i> {{ $t('el.page.database') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <ul id="tab-content" class="col-md-10">
                        <li aria-hidden="true" v-if="type == 'system'">
                            <h2>{{ $t('el.page.system') }}</h2>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="pk-table-width-150">{{ $t('el.page.key') }}</th>
                                        <th>{{ $t('el.page.value') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ $t('el.page.server') }}</td>
                                    <td>{{ system.server }}</td>
                                </tr>
                                    <tr>
                                        <td>{{ $t('el.page.domain') }}</td>
                                        <td>{{ system.http_host }}</td>
                                    </tr>
                                    <tr>
                                        <td>IP</td>
                                        <td>{{ system.remote_host }}</td>
                                    </tr>
                                    <tr>
                                        <td>User Agent</td>
                                        <td>{{ system.user_agent }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>
                        <li aria-hidden="false" v-if="type == 'php'">
                            <h2>PHP</h2>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ $t('el.page.key') }}</th>
                                        <th>{{ $t('el.page.value') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $t('el.page.version') }}</td>
                                        <td>{{ system.php }}</td>
                                    </tr>
                                    <tr>
                                        <td>Handler</td>
                                        <td>{{ system.sapi_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $t('el.page.extension') }}</td>
                                        <td>{{ system.extensions }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>
                        <li aria-hidden="true" v-if="type == 'database'">
                            <h2>{{ $t('el.page.database') }}</h2>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ $t('el.page.key') }}</th>
                                        <th>{{ $t('el.page.value') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $t('el.page.driver') }}</td>
                                        <td>{{ system.db_connection }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $t('el.page.database') }}</td>
                                        <td>{{ system.db_database }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $t('el.page.version') }}</td>
                                        <td>{{ system.db_version }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        data() {
            return {
                system: {},
                type: 'system'
            }
        },
        created() {
            axios.get('/api/system', {
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.access_token
                }
            }).then((response) => {
                    this.system = response.data
                })
        }
    }
</script>

<style scoped>
    h2 {
        font-size: 20px;
        line-height: 30px;
        margin-top: 0;
    }
    ul {
        padding-left: 0;
    }
    ul li{
        list-style: none;
    }
    .content {
        margin-top: 30px;
    }
    .table thead th {
        vertical-align: bottom;
    }
    .table th {
        border-bottom: 1px solid #e5e5e5;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: normal;
        color: #aaa;
    }
    .table :not(:last-child)>td {
        border-bottom: 1px solid #e5e5e5;
    }
    .table td {
        vertical-align: top;
    }
    .table th, .table td {
        padding: 16px 12px;
    }
    .table-hover tbody tr:hover{
        background:#fff
    }
    .sidebar ul li a{
        font-size: 12px;
        display: block;
        text-decoration: none;
        padding: 8px 14px;
        cursor: pointer;
        color: #666;
    }
    .sidebar ul li a:hover, .active {
        background: #eee;
        color: #666;
    }
    .sidebar ul li a i{
        font-size: 22px;
        margin-right: 10px;
        vertical-align: middle;
    }
</style>
