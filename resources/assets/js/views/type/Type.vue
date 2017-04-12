<template>
  <div class="row">
      <div class="ibox">
          <div class="ibox-title">
              <small class="pull-right" style="margin-top: 7px;">
                  <router-link :to="$route.path+'/create'" class="btn btn-info btn-xs"  style="margin-bottom:2px">
                    {{ $t('el.page.create') }}
                  </router-link>
              </small>
              <h5>场地列表</h5>
          </div>
          <div class="ibox-content">
            <div class="header">
                <div class="btn-group">
                    <a class="btn btn-primary btn-sm" @click="toogle">
                      <i :class="listToggle ? 'ion-minus-circled' : 'ion-plus-circled'">
                      </i> {{ listToggle ? '收缩' : '展开'}}
                    </a>
                </div>
                <div class="btn-group">
                    <a class="btn btn-info btn-sm" @click="save"><i class="ion-document-text"></i> 保存</a>
                </div>
                <div class="btn-group">
                    <el-button type="primary" size="small" style="padding:8px 9px" :loading="showLoading" icon="ion-refresh" @click="refresh">
                      <i class="ion-refresh" v-if="!showLoading"></i> 刷新
                    </el-button>
                </div>
            </div>
            <div class="dd" >
              <ol class="dd-list" v-if="show" >
                <type-item v-for="type in types" :type="type"></type-item>
              </ol>
            </div>
          </div>
      </div>
  </div>
</template>

<script>
  import Nestable from 'Nestable'
  import server from '../../config/api';
  import { mapState, mapMutations } from 'vuex'

  export default {
      data() {
          return {
              showLoading: false,
              listToggle: true,
              oldtypes: {},
              types: [],
              show: true,
          }
      },
      created() {
          this.loadData();
      },
      methods: {
          ...mapMutations([
              'SET_TYPES'
          ]),
          createNestable() {
            $('.dd').nestable();
          },
          loadData() {
              let vm = this;
              if(!this.$store.state.types.length > 0){
                axios.get(server.api.type + "?structure=tree").then( response => {
                    this.SET_TYPES(response.data);
                    this.types = response.data;
                    // this.createNestable();
                    setTimeout(function(){
                      vm.createNestable();
                    },2)
                })
              }else{
                  let vm = this;
                  vm.types = this.$store.state.types
                  setTimeout(function(){
                    vm.createNestable();
                  },2)
              }
              // this.refresh();
          },
          toogle() {
              //展开/收缩
              if(this.listToggle){
                $('.dd').nestable('collapseAll');
              }else{
                $('.dd').nestable('expandAll');
              }
              this.listToggle = !this.listToggle;
          },
          refresh() {
              //刷新
              let vm = this;
              vm.show = false;
              vm.showLoading = true;
              axios.get(server.api.type + "?structure=tree").then( response => {
                  vm.showLoading = false;
                  vm.show = true;
                  vm.types = response.data;
                  vm.SET_TYPES(response.data);
                  setTimeout(function(){
                    vm.createNestable();
                  },2)
              })
          },
          save() {
              axios.put(server.api.type + '/sorts', $('.dd').nestable('serialize')).then( response => {
                  toastr.success('The type has been reordered !')

                  this.SET_TYPES(response.data);
              }, (error) => {
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
</script>

<style>
.dd { position: relative; display: block; margin: 0; padding: 0; list-style: none; font-size: 13px; line-height: 20px; }

.dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
.dd-list .dd-list { padding-left: 30px; }
.dd-collapsed .dd-list { display: none; }

.dd-item,
.dd-empty,
.dd-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 13px; line-height: 20px; }

.dd-handle {
    display: block;
    margin: 1px 0;
    padding: 15px 10px;
    color: #333;
    text-decoration: none;
    border: 1px solid #ccc;
    background: #fff;
    /*background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:         linear-gradient(top, #fafafa 0%, #eee 100%);*/
    -webkit-border-radius: 3px;
            border-radius: 3px;
    box-sizing: border-box; -moz-box-sizing: border-box;
}
.dd-handle:hover { color: #2ea8e5; background: #fff; }

.dd-item > button {
   display: block;
   position: relative;
   cursor: pointer;
   float: left;
   width: 25px;
   height: 40px;
   margin: 5px 0;
   padding: 0;
   text-indent: 100%;
   white-space: nowrap;
   overflow: hidden;
   border: 0;
   background: transparent;
   font-size: 12px;
   line-height: 1;
   text-align: center;
   font-weight: bold;
 }
.dd-item > button:before {
   content: '+';
   display: block;
   position: absolute;
   width: 100%;
   text-align: center;
   text-indent: 0;
 }
.dd-item > button[data-action="collapse"]:before {
   content: '-';
 }

.dd-placeholder,
.dd-empty { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; -moz-box-sizing: border-box; }
.dd-empty { border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5;
    background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                      -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-image:    -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                         -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-image:         linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                              linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-size: 60px 60px;
    background-position: 0 0, 30px 30px;
}

.dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }
.dd-dragel > .dd-item .dd-handle { margin-top: 0; }
.dd-dragel .dd-handle {
    -webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
            box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
}

/**
 * Nestable Extras
 */

.nestable-lists { display: block; clear: both; padding: 30px 0; width: 100%; border: 0; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd; }

.pull-right {
    float: right;
}
.dd-nodrag {
    color: #529ac3;
}
.dd-handle span i:hover {
    color: #81c7f7;
}
.header {
    margin-bottom: 10px;
}
.dd-nodrag .type-i {
  font-size: 18px;
  cursor: pointer;
}
</style>
