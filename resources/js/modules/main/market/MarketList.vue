<template>
    <div class="my-3">
        <h1 class="font-weight-bold">Сайты с кейсами</h1>
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
                        <b-media v-for="market of items" v-bind:key="market.id">
                            <template #aside>
                                <b-img :src="market.logo" width="64" :alt="market.name_canonical"></b-img>
                            </template>

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
import marketApi from '../../market/api'

export default {
    name: "MarketList",
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

            marketApi.list({sort: key}).then(function (response) {
                that.items = response.data.data
                that.load = false
            })
        }
    },
    mounted() {
        var that = this;
        marketApi.list().then(function (response) {
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
