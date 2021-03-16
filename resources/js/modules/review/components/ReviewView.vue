<template>
    <section id="review_show">
        <div class="text-center">{{ review.name }}</div>
    </section>
</template>

<script>

    import reviewApi from "../api"

    export default {
        name: "ReviewView",
        components: {},
        data() {
            return {
                review: {},
            }
        },
        methods: {
            async fetchReview() {
                const response = await reviewApi.find(this.$route.params.id)
                this.review = response.data.data
            },
        },
        mounted() {
            this.fetchReview()
        },
    }
</script>

<style lang="scss" scoped>

</style>
