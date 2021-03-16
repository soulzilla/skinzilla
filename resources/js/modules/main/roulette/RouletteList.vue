<template>
    <div class="my-3">
        <h1 class="font-weight-bold">Сайты с рулетками</h1>
        <div class="row">
            <div class="col-lg-4">
                <b-list-group class="shadow my-3">
                    <b-list-group-item
                        v-for="item of filters"
                        v-bind:key="item.key"
                        @click="applyFilter(item.key)"
                        :class="currentFilter === item.key ? 'filter-menu-item selected' : 'filter-menu-item'">
                        <b-icon :icon="item.icon"></b-icon> {{ item.label }}
                    </b-list-group-item>
                </b-list-group>
            </div>
            <div class="col-lg-8">
                <b-card v-if="!load" class="shadow my-3">
                    <template v-if="items.length">
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
                <b-card v-else>
                    <div class="text-center">
                        <b-spinner style="width: 3rem; height: 3rem;"></b-spinner>
                    </div>
                </b-card>
            </div>
        </div>
    </div>
</template>

<script>
import rouletteApi from '../../roulette/api'

export default {
    name: "RouletteList",
    data: () => ({
        load: true,
        items: [],
        filters: [],
        currentFilter: 1
    }),
    methods: {
        copyToClipboard: function (data) {
            let that = this
            this.$copyText(data).then(function () {
                that.$toast.success('Скопировано в буфер обмена')
            }, function () {
                that.$toast.error('Не удаётся скопировать')
            })
        },
        applyFilter: function (key) {
            var that = this
            this.currentFilter = key
            this.load = true

            rouletteApi.list({sort: key}).then(function (response) {
                that.items = response.data.data
                that.load = false
            })
        }
    },
    mounted() {
        var that = this;
        rouletteApi.list().then(function (response) {
            that.items = response.data.data
            that.load = false
        })

        that.filters = [
            {
                key: 1,
                label: 'Рейтинг сайта',
                icon: 'bar-chart-steps'
            },
            {
                key: 2,
                label: 'По популярности',
                icon: 'star'
            },
            {
                key: 3,
                label: 'По просмотрам',
                icon: 'eye'
            },
            {
                key: 4,
                label: 'По обсуждаемости',
                icon: 'chat-dots'
            },
            {
                key: 5,
                label: 'Народный рейтинг',
                icon: 'people'
            }
        ];
    }
}
</script>

<style scoped>
.filter-menu-item {
    cursor: pointer;
    background-color: #fff;
}

.filter-menu-item:hover, .filter-menu-item.selected {
    background-color: #fffbdb;
}
</style>

