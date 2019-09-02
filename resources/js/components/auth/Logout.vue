<template>

</template>

<script>
    export default {
        name: "Logout",
        mounted() {
            this.axios.post('api/logout').then(({data})=>{
                this.$router.push('/');
                localStorage.clear();
//TODO: change auth.check to false
                console.log(data);

            });
        }
    }
</script>

<style scoped>

</style>
