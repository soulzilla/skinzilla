<template>
    <section id="market_show">
        <div class="text-center">{{ market.name }}</div>
    </section>
</template>

<script>

    import marketApi from "../api"

    export default {
        name: "MarketView",
        components: {},
        data() {
            return {
                market: {},
            }
        },
        methods: {
            async fetchMarket() {
                const response = await marketApi.find(this.$route.params.id)
                this.market = response.data.data
            },
        },
        mounted() {
            this.fetchMarket()
        },
    }
</script>

<style lang="scss" scoped>

</style>
