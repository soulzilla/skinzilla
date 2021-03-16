<template>
    <section id="skin_show">
        <div class="text-center">{{ skin.name }}</div>
    </section>
</template>

<script>

    import skinApi from "../api"

    export default {
        name: "SkinView",
        components: {},
        data() {
            return {
                skin: {},
            }
        },
        methods: {
            async fetchSkin() {
                const response = await skinApi.find(this.$route.params.id)
                this.skin = response.data.data
            },
        },
        mounted() {
            this.fetchSkin()
        },
    }
</script>

<style lang="scss" scoped>

</style>
