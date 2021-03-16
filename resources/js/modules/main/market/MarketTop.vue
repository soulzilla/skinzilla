<template>
    <div>
        <b-card v-if="!load" class="shadow mb-3">
            <b-card-title>
                <div class="row mx-2">
                    Лучшие торговые площадки
                    <div class="ml-auto">
                        <router-link to="/markets">
                            <small>
                                Полный список <b-icon-arrow-right></b-icon-arrow-right>
                            </small>
                        </router-link>
                    </div>
                </div>
            </b-card-title>
            <template v-if="markets.length">
                <b-media v-for="market of markets" v-bind:key="market.id">
                    <b-media-body class="ml-3 my-3">
                        <router-link :to="{ name: 'View Market', params: {id: market.id, name: market.name_canonical } }">
                            <h5 class="mt-0">{{ market.name }}</h5>
                        </router-link>
                        <div class="ml-3">
                            <div class="row">
                                <div class="col col-auto px-1">
                                    <p>Наша оценка: {{ market.assessment }} <i class="fa fa-star text-warning"></i></p>
                                </div>
                                <div class="col col-auto px-1">
                                    <p>Лайков: {{ market.likes_count }} <i class="fa fa-heart text-danger"></i></p>
                                </div>
                                <div class="col col-auto px-1">
                                    <p>Просмотров: {{ market.views_count }} <i class="fa fa-eye text-success"></i></p>
                                </div>
                                <div class="col col-auto px-1">
                                    <p>Комментариев: {{ market.comments_count }} <i class="fa fa-comments text-info"></i></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ml-auto mr-3">
                                    <router-link
                                        :to="{ name: 'View Market', params: {id: market.id, name: market.name_canonical } }"
                                        class="btn btn-primary m-1">
                                        Обсуждение <i class="fa fa-comment"></i>
                                    </router-link>
                                    <a :href="market.ref_link" target="_blank" class="btn btn-danger m-1">
                                        На сайт <i class="fa fa-globe-europe"></i>
                                    </a>
                                    <button
                                        class="btn btn-success m-1"
                                        :id="'market-promo-' + market.id"
                                        @click="copyToClipboard(market.promo_code)">
                                        {{ market.promo_code }} <i class="fa fa-qrcode"></i>
                                    </button>
                                    <b-popover :target="'market-promo-' + market.id" triggers="hover">
                                        {{ market.promo_code_description }}
                                    </b-popover>
                                </div>
                            </div>
                        </div>
                    </b-media-body>
                </b-media>
            </template>
            <template v-else>
                <b-card-body>
                    <div class="text-center">
                        <p><span class="fa fa-sad-cry fa-10x"></span></p>
                        <p>К сожалению, ничего не найдено</p>
                    </div>
                </b-card-body>
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
import marketApi from '../../market/api'

export default {
    name: "MarketTop",
    data: () => ({
        markets: [],
        load: true
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
        var that = this
        marketApi.top().then(function (response) {
            that.markets = response.data.data
            that.load = false
        })
    }
}
</script>

<style scoped>

</style>
