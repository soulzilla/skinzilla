<template>
    <section id="user_show">
        <div class="text-center">{{ user.name }}</div>
    </section>
</template>

<script>

    import userApi from "../api"

    export default {
        name: "UserView",
        components: {},
        data() {
            return {
                user: {},
            }
        },
        methods: {
            async fetchUser() {
                const response = await userApi.find(this.$route.params.id)
                this.user = response.data.data
            },
        },
        mounted() {
            this.fetchUser()
        },
    }
</script>

<style lang="scss" scoped>

</style>
