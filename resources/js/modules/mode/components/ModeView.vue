<template>
    <section id="mode_show">
        <div class="text-center">{{ mode.name }}</div>
    </section>
</template>

<script>

    import modeApi from "../api"

    export default {
        name: "ModeView",
        components: {},
        data() {
            return {
                mode: {},
            }
        },
        methods: {
            async fetchMode() {
                const response = await modeApi.find(this.$route.params.id)
                this.mode = response.data.data
            },
        },
        mounted() {
            this.fetchMode()
        },
    }
</script>

<style lang="scss" scoped>

</style>
