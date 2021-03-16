<template>
    <section id="roulette_show">
        <div class="text-center">{{ roulette.name }}</div>
    </section>
</template>

<script>

    import rouletteApi from "../api"

    export default {
        name: "RouletteView",
        components: {},
        data() {
            return {
                roulette: {},
            }
        },
        methods: {
            async fetchRoulette() {
                const response = await rouletteApi.find(this.$route.params.id)
                this.roulette = response.data.data
            },
        },
        mounted() {
            this.fetchRoulette()
        },
    }
</script>

<style lang="scss" scoped>

</style>
