<template>
    <section id="banner_show">
        <div class="text-center">{{ banner.title }}</div>
        <div v-html="banner.content"></div>
        <div>URL {{ banner.url }}</div>
        <div>Image {{ banner.image }}</div>
        <div>Target blank {{ banner.blank }}</div>
        <div>Published {{ banner.published }}</div>
    </section>
</template>

<script>

    import bannerApi from "../api"

    export default {
        name: "BannerView",
        components: {},
        data() {
            return {
                banner: {},
            }
        },
        methods: {
            async fetchBanner() {
                const response = await bannerApi.find(this.$route.params.id)
                this.banner = response.data.data
            },
        },
        mounted() {
            this.fetchBanner()
        },
    }
</script>

<style lang="scss" scoped>

</style>
