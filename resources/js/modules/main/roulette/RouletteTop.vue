<template>
    <b-card v-if="!load" class="shadow">
        <template v-if="items.length">
            <b-card-title>
                <div class="row mx-2">
                    Лучшие сайты с рулетками
                    <div class="ml-auto">
                        <router-link to="/roulette">
                            <small>
                                Полный список <b-icon-arrow-right></b-icon-arrow-right>
                            </small>
                        </router-link>
                    </div>
                </div>
            </b-card-title>
            <b-media v-for="roulette of items" v-bind:key="roulette.id">
                <template #aside>
                    <b-img :src="roulette.logo" width="64" :alt="roulette.name_canonical"></b-img>
                </template>

                <b-media-body class="ml-3 my-3">
                    <router-link :to="{ name: 'View Roulette', params: {id: roulette.id, name: roulette.name_canonical } }">
                        <h5 class="mt-0">{{ roulette.name }}</h5>
                    </router-link>
                    <div class="ml-3">
                        <div class="row">
                            <div class="col col-auto px-1">
                                <p>Наша оценка: {{ roulette.assessment }} <i class="fa fa-star text-warning"></i></p>
                            </div>
                            <div class="col col-auto px-1">
                                <p>Лайков: {{ roulette.likes_count }} <i class="fa fa-heart text-danger"></i></p>
                            </div>
                            <div class="col col-auto px-1">
                                <p>Просмотров: {{ roulette.views_count }} <i class="fa fa-eye text-success"></i></p>
                            </div>
                            <div class="col col-auto px-1">
                                <p>Комментариев: {{ roulette.comments_count }} <i class="fa fa-comments text-info"></i></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="ml-auto mr-3">
                                <router-link
                                    :to="{ name: 'View Roulette', params: {id: roulette.id, name: roulette.name_canonical } }"
                                    class="btn btn-primary m-1">
                                    Обсуждение <i class="fa fa-comment"></i>
                                </router-link>
                                <a :href="roulette.ref_link" target="_blank" class="btn btn-danger m-1">
                                    На сайт <i class="fa fa-globe-europe"></i>
                                </a>
                                <button
                                    class="btn btn-success m-1"
                                    :id="'roulette-promo-' + roulette.id"
                                    @click="copyToClipboard(roulette.promo_code)">
                                    {{ roulette.promo_code }} <i class="fa fa-qrcode"></i>
                                </button>
                                <b-popover :target="'roulette-promo-' + roulette.id" triggers="hover">
                                    {{ roulette.promo_code_description }}
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
</template>

<script>
import rouletteApi from '../../roulette/api'

export default {
    name: "RouletteTop",
    data: () => ({
        items: [],
        load: true
    }),
    methods: {
        copyToClipboard: function (data) {
            let that = this
            this.$copyText(data).then(function () {
                that.$message.success('Скопировано в буфер обмена!')
            }, function () {
                that.$message.error('Не удаётся скопировать')
            })
        }
    },
    mounted() {
        var that = this
        rouletteApi.top().then(function (response) {
            that.items = response.data.data
            that.load = false;
        })
    }
}
</script>

<style scoped>

</style>
