<template lang="html">

</template>

<script>
export default {
  name: 'remote-js',
  components: {
   'remote-js': {
    render(createElement) {
      return createElement('script', { attrs: { type: 'text/javascript', src: this.src }});
    },
    props: {
      src: { type: String, required: true },
    },
  },
  },
}
</script>

<style lang="css">
</style>
