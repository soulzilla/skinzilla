<template>
    <div class="row">
        <div class="ml-3">
            <b-link class="text-decoration-none text-danger" @click="toggleLike">
                <template v-if="like">
                    <b-icon-heart-fill></b-icon-heart-fill>
                </template>
                <template v-else>
                    <b-icon-heart></b-icon-heart>
                </template>
                <span>
                    {{ likes_count }}
                </span>
            </b-link>
        </div>
        <div class="ml-auto mr-3">
            <b-link class="text-decoration-none text-info">
                <b-icon-eye></b-icon-eye>
                <span>{{ item.views_count }}</span>
            </b-link>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "ItemFooter",
    props: {
        item: {},
        entity_table: ''
    },
    methods: {
        toggleLike: function () {
            if (this.like) {
                this.dislikeIt()
            } else {
                this.likeIt()
            }
        },
        dislikeIt: function () {
            let data = {
                entity_table: this.entity_table,
                entity_id: this.item.id
            }
            var that = this
            axios.post('/dislike', data).then(function (response) {
                that.like = null
                that.likes_count = response.data.likes_count
            })
        },
        likeIt: function () {
            let data = {
                entity_table: this.entity_table,
                entity_id: this.item.id
            }
            var that = this
            axios.post('/like', data).then(function (response) {
                that.like = response.data.data
                that.likes_count = response.data.likes_count
            })
        }
    },
    data: () => ({
        like: null,
        likes_count: 0
    }),
    mounted() {
        this.like = this.item.like
        this.likes_count = this.item.likes_count
    }
}
</script>

<style scoped>

</style>
