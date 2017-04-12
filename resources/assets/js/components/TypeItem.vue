<template lang="html">
    <li class="dd-item" v-if="type.children" :data-id="type.id">
        <!-- <button type="button" data-action="collapse">"Collapse"</button>
        <button type="button" data-action="expand" style="display: none">"Expand"</button> -->
        <div class="dd-handle">
          {{ type.name }}
          <span class="pull-right dd-nodrag">
              <i class="ion-compose type-i" @click="typeActions('edit-item', type)"></i>
              <i class="ion-trash-a type-i" @click="typeActions('delete-item', type)"></i>
          </span>
        </div>
        <ol  class="dd-list">
          <type-item v-for="children in type.children" :type="children"></type-item>
        </ol>
    </li>
    <li class="dd-item" :data-id="type.id" v-else>
        <div class="dd-handle">
          {{ type.name }}
          <span class="pull-right dd-nodrag">
              <i class="ion-compose type-i" @click="typeActions('edit-item', type)"></i>
              <i class="ion-trash-a type-i" @click="typeActions('delete-item', type)"></i>
          </span>
        </div>
    </li>
</template>

<script>
  import server from '../config/api';
  import { mapMutations } from 'vuex'

  export default {
      props: {
          type:{
            type: Object,
            default() {
              return {}
            }
          }
      },
      methods: {
          typeActions(action, data) {
              let vm = this;
              if (action == 'edit-item') {
                  vm.$router.push(this.$route.path + '/' + data.id + '/edit')
              } else if (action == 'delete-item') {
                  axios.delete(server.api.type + '/' + data.id).then((response) => {
                          toastr.success('You delete the type success!')
                          vm.SET_TYPES(response.data)
                          // vm.$parent.loadData();
                          // vm.$dispatch('loadData')
                      }, (response) => {
                        console.log(response.response);
                          if ((typeof response.data.error !== 'string') && response.data.error) {
                              toastr.error(response.data.error.message)
                          } else {
                              toastr.error(response.status + ' : Resource ' + response.statusText)
                          }
                      })
              }
          },
          ...mapMutations([
            'SET_TYPES'
          ])
      },

  }
</script>
