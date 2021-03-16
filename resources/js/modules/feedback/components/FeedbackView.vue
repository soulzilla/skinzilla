<template>
    <section id="feedback_show">
        <div class="text-center">{{ feedback.content }}</div>
    </section>
</template>

<script>

    import feedbackApi from "../api"

    export default {
        name: "FeedbackView",
        components: {},
        data() {
            return {
                feedback: {},
            }
        },
        methods: {
            async fetchFeedback() {
                const response = await feedbackApi.find(this.$route.params.id)
                this.feedback = response.data.data
            },
        },
        mounted() {
            this.fetchFeedback()
        },
    }
</script>

<style lang="scss" scoped>

</style>
