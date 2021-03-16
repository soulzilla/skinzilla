<template>
    <div>
        <template v-if="items.length">
            <b-carousel
                id="banners-carousel"
                v-model="slide"
                :interval="4000"
                controls
                background="#ababab"
                img-width="1024"
                img-height="480"
                style="text-shadow: 1px 1px 2px #333;"
                @sliding-start="onSlideStart"
                @sliding-end="onSlideEnd">

                <template v-for="item of items">
                    <b-carousel-slide
                        v-bind:key="item.id"
                        :caption="item.title"
                        :img-src="item.image">
                        <div v-html="item.content"></div>
                        <div>
                            <template v-if="item.blank">
                                <a :href="item.url" target="_blank" class="btn btn-danger">Перейти</a>
                            </template>
                            <template v-else>
                                <router-link :to="item.url" class="btn btn-danger">Перейти</router-link>
                            </template>
                        </div>
                    </b-carousel-slide>
                </template>
            </b-carousel>
        </template>
    </div>
</template>

<script>
import bannerApi from "../../banner/api";

export default {
    name: "Banners",
    data: () => ({
        items: [],
        slide: 0,
        sliding: null
    }),
    methods: {
        onSlideStart(slide) {
            this.sliding = true
        },
        onSlideEnd(slide) {
            this.sliding = false
        }
    },
    mounted() {
        bannerApi.top().then(response => (this.items = response.data.data))
    }
}
</script>

<style scoped>

</style>
