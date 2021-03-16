<template>
    <div>
        <b-card v-if="!load" class="shadow mb-3">
            <template v-if="items.length">
                <b-card-title>
                    <div class="row mx-2">
                        Лучшие сайты с кейсами
                        <div class="ml-auto">
                            <router-link to="/gambling">
                                <small>
                                    Полный список <b-icon-arrow-right></b-icon-arrow-right>
                                </small>
                            </router-link>
                        </div>
                    </div>
                </b-card-title>

                <b-media v-for="gambling of items" v-bind:key="gambling.id">
                    <template #aside>
                        <b-img :src="gambling.logo" width="64" :alt="gambling.name_canonical"></b-img>
                    </template>

                    <b-media-body class="ml-3 my-3">
                        <router-link :to="{ name: 'View Gambling', params: {id: gambling.id, name: gambling.name_canonical } }">
                            <h5 class="mt-0">{{ gambling.name }}</h5>
                        </router-link>
                        <div class="ml-3">
                            <div class="row">
                                <div class="col col-auto px-1">
                                    <p>Наша оценка: {{ gambling.assessment }} <i class="fa fa-star text-warning"></i></p>
                                </div>
                                <div class="col col-auto px-1">
                                    <p>Лайков: {{ gambling.likes_count }} <i class="fa fa-heart text-danger"></i></p>
                                </div>
                                <div class="col col-auto px-1">
                                    <p>Просмотров: {{ gambling.views_count }} <i class="fa fa-eye text-success"></i></p>
                                </div>
                                <div class="col col-auto px-1">
                                    <p>Комментариев: {{ gambling.comments_count }} <i class="fa fa-comments text-info"></i></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ml-auto mr-3">
                                    <router-link
                                        :to="{ name: 'View Gambling', params: {id: gambling.id, name: gambling.name_canonical } }"
                                        class="btn btn-primary m-1">
                                        Обсуждение <i class="fa fa-comment"></i>
                                    </router-link>
                                    <a :href="gambling.ref_link" target="_blank" class="btn btn-danger m-1">
                                        На сайт <i class="fa fa-globe-europe"></i>
                                    </a>
                                    <button
                                        class="btn btn-success m-1"
                                        :id="'gambling-promo-' + gambling.id"
                                        @click="copyToClipboard(gambling.promo_code)">
                                        {{ gambling.promo_code }} <i class="fa fa-qrcode"></i>
                                    </button>
                                    <b-popover :target="'gambling-promo-' + gambling.id" triggers="hover">
                                        {{ gambling.promo_code_description }}
                                    </b-popover>
                                </div>
                            </div>
                        </div>
                    </b-media-body>
                </b-media>
            </template>
            <template v-else>
                <div class="text-center">
                    <p><span class="fa fa-sad-cry fa-10x"></span></p>
                    <p>К сожалению, ничего не найдено</p>
                </div>
            </template>
        </b-card>
        <b-card class="shadow" v-else>
            <div class="text-center">
                <b-spinner style="width: 3rem; height: 3rem;" label="Large Spinner"></b-spinner>
            </div>
        </b-card>
    </div>
</template>

<script>
import gamblingApi from '../../gambling/api'

export default {
    name: "GamblingTop",
    data: () => ({
        load: true,
        items: [],
    }),
    methods: {
        copyToClipboard: function (data) {
            let that = this
            this.$copyText(data).then(function () {
                that.$toast.success('Скопировано в буфер обмена!')
            }, function () {
                that.$toast.error('Не удаётся скопировать')
            })
        }
    },
    mounted() {
        var that = this;
        gamblingApi.top().then(function (response) {
            that.items = response.data.data
            that.load = false
        })
    }
}
</script>

<style scoped>

</style>
