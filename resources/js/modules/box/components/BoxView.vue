<template>
    <section id="box_show">
        <div class="text-center">{{ box.name }}</div>
    </section>
</template>

<script>

    import boxApi from "../api"

    export default {
        name: "BoxView",
        components: {},
        data() {
            return {
                box: {},
            }
        },
        methods: {
            async fetchBox() {
                const response = await boxApi.find(this.$route.params.id)
                this.box = response.data.data
            },
        },
        mounted() {
            this.fetchBox()
        },
    }
</script>

<style lang="scss" scoped>

</style>
