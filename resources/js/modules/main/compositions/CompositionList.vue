<template>

    <div class="my-3">
        <h1 class="font-weight-bold">Пользовательские сеты</h1>
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
                        <div v-for="composition of items" v-bind:key="composition.id" class="mb-3">
                            <router-link :to="{ name: 'View Composition', params: { id: composition.id } }">
                                <h5 class="mt-0">{{ composition.name }} <small>{{ composition.user.name }}</small></h5>
                            </router-link>
                            <div class="row mx-1 mt-3">
                                <div class="col-12 col-md-3 px-0 m-1" v-for="item of composition.skins" v-bind:key="item.id">
                                    <div class="img-thumbnail" :class="getSkinRarityClass(item)">
                                        <div class="row">
                                            <div class="ml-auto mr-3">
                                                <b-badge variant="success">{{ item.cost }}</b-badge>

                                                <b-badge v-b-popover.hover="getQualityName(item.quality)">
                                                    {{ getQualityShortcut(item.quality) }}</b-badge>

                                                <b-badge variant="danger" v-if="item.stat_track" v-b-popover.hover="'StatTrak'">
                                                    ST</b-badge>

                                                <b-badge variant="warning" v-if="item.souvenir" v-b-popover.hover="'Сувенирный'">
                                                    S</b-badge>
                                            </div>
                                        </div>
                                        <b-img-lazy :src="item.image" thumbnail></b-img-lazy>
                                        <div class="text-white"><b>{{ item.name }}</b></div>
                                    </div>
                                </div>
                                <div v-if="composition.skins_count > 3" class="col-12 col-md-2 m-1">
                                    <div class="img-thumbnail">
                                        <div class="text-center py-5 px-3">
                                            <router-link :to="{ name: 'View Composition', params: { id: composition.id } }">
                                                <b-icon icon="three-dots-vertical"></b-icon>
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="nextPage" class="mb-3">
                            <div class="text-center" v-if="moreLoading">
                                <b-spinner style="width: 3rem; height: 3rem;"></b-spinner>
                            </div>
                            <b-link @click="loadMore" class="btn btn-block btn-outline-primary">
                                Загрузить ещё
                            </b-link>
                        </div>
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
import axios from "axios";

export default {
    name: "CompositionList",
    data: () => ({
        load: true,
        items: [],
        filters: [],
        currentFilter: 1,
        nextPage: null,
        moreLoading: false
    }),
    methods: {
        applyFilter: function (key) {
            var that = this
            this.currentFilter = key
            this.load = true

            axios.get('/compositions', {params:{sort: key}}).then(function (response) {
                that.items = response.data.data
                that.load = false
                that.nextPage = response.data.links.next
            })
        },
        getQualityShortcut: function (quality) {
            switch (quality) {
                case 1: return 'FN';
                case 2: return 'MW';
                case 3: return 'FT';
                case 4: return 'WW';
                case 5: return 'BS';
            }
        },
        getQualityName: function (quality) {
            switch (quality) {
                case 1: return 'Прямо с завода';
                case 2: return 'Немного поношенное';
                case 3: return 'После полевых испытаний';
                case 4: return 'Поношенное';
                case 5: return 'Закалённое в боях';
            }
        },
        getSkinRarityClass: function (skin) {
            if (skin.gun_type === 35 || skin.gun_type === 36) {
                return 'weapon-legendary'
            }

            switch (skin.rarity) {
                case 1: return 'weapon-consumer';
                case 2: return 'weapon-industrial';
                case 3: return 'weapon-mil-spec';
                case 4: return 'weapon-restricted';
                case 5: return 'weapon-classified';
                case 6: return 'weapon-covert';
                case 7: return 'weapon-contraband';
                case 8: return 'weapon-legendary';
            }
        },
        loadMore: function () {
            this.moreLoading = true
            let that = this
            axios.get(this.nextPage).then(function (response) {
                for (let item of response.data.data) {
                    that.items.push(item)
                }
                that.nextPage = response.data.links.next
                that.moreLoading = false
            })
        }
    },
    mounted() {
        let that = this;
        axios.get('/compositions').then(function (response) {
            that.items = response.data.data
            that.nextPage = response.data.links.next
            that.load = false
        })

        that.filters = [
            {
                key: 1,
                label: 'Сначала новые',
                icon: 'clock'
            },
            {
                key: 2,
                label: 'По популярности',
                icon: 'heart'
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
                label: 'По стоимости',
                icon: 'cash'
            },
            {
                key: 6,
                label: 'Избранное',
                icon: 'star-fill'
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
.weapon {
    width: 18rem;
}
.weapon-stat-track {
    border: 1px solid #f89406;
}
.weapon-souvenir {
    border: 1px solid gold;
}
.weapon-consumer {
    background-color: #b0c3d9;
    font-size: 1rem;
}
.weapon-industrial {
    background-color: #5e98d9;
    font-size: 1rem;
}
.weapon-mil-spec {
    background-color: #4b69ff;
    font-size: 1rem;
}
.weapon-restricted {
    background-color: #8847ff;
    font-size: 1rem;
}
.weapon-classified {
    background-color: #d32ee6;
    font-size: 1rem;
}
.weapon-covert {
    background-color: #eb4b4b;
    font-size: 1rem;
}
.weapon-contraband {
    background-color: #ffae39;
    font-size: 1rem;
}
.weapon-legendary {
    background-color: gold;
    font-size: 1rem;
}
</style>
