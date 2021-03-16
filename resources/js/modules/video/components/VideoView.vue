<template>
    <section id="video_show">
        <div class="text-center">{{ video.url }}</div>
    </section>
</template>

<script>

    import videoApi from "../api"

    export default {
        name: "VideoView",
        components: {},
        data() {
            return {
                video: {},
            }
        },
        methods: {
            async fetchVideo() {
                const response = await videoApi.find(this.$route.params.id)
                this.video = response.data.data
            },
        },
        mounted() {
            this.fetchVideo()
        },
    }
</script>

<style lang="scss" scoped>

</style>
