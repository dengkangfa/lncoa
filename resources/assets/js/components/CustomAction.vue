<template>
    <div>
        <span :style="{color: color}" @click="setStatus(rowData)"><i class="ion-record"></i></span>
    </div>
</template>

<script>
    export default{
        props: {
            rowData: {
                type: Object,
                required: true
            },
            apiUrl: {
                type: String,
                required: true
            }
        },
        computed: {
            color() {
                return this.rowData.status ? '#8eb4cb' : '#bf5329'
            }
        },
        methods: {
            setStatus(rowData) {
                let index = this
                swal({
                    title: "Change the record status",
                    text: "The action may affect some data, Please think twice!",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, changed it!",
                },
                function () {
                    index.postData(rowData)
                })
            },
            postData(rowData) {
                axios.post('/api/user/' + rowData.id + '/status', {status: !rowData.status})
                    .then(response => {
                          if (!response.data) {
                              this.rowData.status = !this.rowData.status
                              if (this.rowData.status) {
                                  toastr.success(this.$t('el.notification.user_status_activat'));
                              } else {
                                  toastr.warning(this.$t('el.notification.user_status_abandon'));
                              }
                          }
                    }, error => {
                        if ((typeof error.response.data.error !== 'string') && error.response.data.error) {
                            toastr.error(error.response.data.error.message)
                        } else {
                            toastr.error(error.response.status + ' : Resource ' + error.response.statusText)
                        }
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>
    span {
        cursor:pointer;
    }
</style>
