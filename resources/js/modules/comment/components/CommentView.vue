<template>
    <section id="comment_show">
        <div class="text-center">{{ comment.name }}</div>
    </section>
</template>

<script>

    import commentApi from "../api"

    export default {
        name: "CommentView",
        components: {},
        data() {
            return {
                comment: {},
            }
        },
        methods: {
            async fetchComment() {
                const response = await commentApi.find(this.$route.params.id)
                this.comment = response.data.data
            },
        },
        mounted() {
            this.fetchComment()
        },
    }
</script>

<style lang="scss" scoped>

</style>
