<template lang="html">

</template>

<script>
export default {
    created() {
        axios.post('/api/signup/confirm/' + this.$route.params.token).then( response => {
            console.log(response);
        })
    }
}
</script>

<style lang="css">
</style>
