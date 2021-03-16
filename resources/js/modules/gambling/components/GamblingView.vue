<template>
    <section id="gambling_show">
        <div class="text-center">{{ gambling.name }}</div>
        <img :src="gambling.logo">
    </section>
</template>

<script>

    import gamblingApi from "../api"

    export default {
        name: "GamblingView",
        components: {},
        data() {
            return {
                gambling: {},
            }
        },
        methods: {
            async fetchGambling() {
                const response = await gamblingApi.find(this.$route.params.id)
                this.gambling = response.data.data
            },
        },
        mounted() {
            this.fetchGambling()
        },
    }
</script>

<style lang="scss" scoped>

</style>
