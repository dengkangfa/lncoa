<template>
  <div class="row">
      <div class="ibox">
          <div class="ibox-title">
              <small class="pull-right" style="margin-top: 7px;">
                  <router-link to="/sites/create" class="btn btn-info btn-xs"  style="margin-bottom:2px">
                    {{ $t('el.page.create') }}
                  </router-link>
              </small>
              <h5>场地列表</h5>
          </div>
          <div class="ibox-content">
            <div class="dd">
              <ol class="dd-list">
                  <li class="dd-item" data-id="1">
                      <div class="dd-handle">Item 1</div>
                  </li>
                  <li class="dd-item" data-id="2">
                      <div class="dd-handle">Item 2</div>
                  </li>
                  <li class="dd-item" data-id="3">
                      <div class="dd-handle">Item 3</div>
                      <ol class="dd-list">
                          <li class="dd-item" data-id="4">
                              <div class="dd-handle">Item 4</div>
                          </li>
                          <li class="dd-item" data-id="5">
                              <div class="dd-handle">Item 5</div>
                          </li>
                      </ol>
                  </li>
              </ol>
            </div>
          </div>
      </div>
  </div>
</template>

<script>
  import Nestable from 'Nestable'
  export default {
      components: { Nestable },
      mounted() {
        this.createNestable();
      },
      methods: {
        createNestable() {
          // let Nestable = new Nestable('.dd');
          // Nestable.nestable();
        }
      }
  }
</script>

<style>
</style>
