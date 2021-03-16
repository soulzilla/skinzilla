<template>
    <div class="mb-3">
        <h3>Отзывы</h3>
        <div v-if="load" class="text-center">
            <b-spinner style="width: 3rem; height: 3rem;"></b-spinner>
        </div>
        <div class="shadow" v-else>
            <template v-if="items.length">
                <b-carousel
                    id="reviews-carousel"
                    v-model="slide"
                    :interval="4000"
                    controls
                    background="#fff"
                    img-width="1024"
                    img-height="200"
                    style="text-shadow: 1px 1px 2px #333;"
                    @sliding-start="onSlideStart"
                    @sliding-end="onSlideEnd">

                    <template v-for="item of items">
                        <b-carousel-slide
                            img-blank
                            v-bind:key="item.id">
                            <div class="text-dark">
                                <h4>{{ item.user.name }}</h4>
                                <p><b-icon-blockquote-left></b-icon-blockquote-left>
                                    {{ item.content }}
                                <b-icon-blockquote-right></b-icon-blockquote-right></p>
                            </div>
                        </b-carousel-slide>
                    </template>
                </b-carousel>
            </template>
            <template v-else>
                <div class="text-center bg-white py-3">
                    <p><span class="fa fa-sad-cry fa-10x"></span></p>
                    <p class="mb-0">К сожалению, ничего не найдено</p>
                </div>
            </template>

            <div class="p-3 bg-white">
                <b-link class="btn btn-outline-info" @click="showForm = true" v-if="$auth.check()">
                    Оставить отзыв
                </b-link>
            </div>
        </div>

        <el-dialog
            :visible.sync="showForm"
            close-on-click-modal
            v-on:close-dialog="showForm = false"
            :modalAppendToBody="false">
            <el-form>
                <el-form-item prop="name">
                    <b-textarea no-resize rows="5" v-model="form.content" placeholder="Ваш отзыв">
                    </b-textarea>
                </el-form-item>
                <el-button @click="submitForm" :disabled="form.content.length === 0">
                    Сохранить
                </el-button>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
import reviewsApi from '../../review/api'

export default {
    name: "Reviews",
    data: () => ({
        items: [],
        load: true,
        showForm: false,
        form: {
            content: ''
        },
        slide: 0,
        sliding: null
    }),
    methods: {
        onSlideStart(slide) {
            this.sliding = true
        },
        onSlideEnd(slide) {
            this.sliding = false
        },
        submitForm: function () {
            let data = {
                content: this.form.content
            }
            let that = this

            reviewsApi.create(data).then(function () {
                that.showForm = false
                that.$toast.success('Спасибо за отзыв!')
                that.form.content = ''
            })
        }
    },
    mounted() {
        let that = this
        reviewsApi.top().then(function (response) {
            that.items = response.data.data
            that.load = false
        })
    }
}
</script>

<style scoped>

</style>
