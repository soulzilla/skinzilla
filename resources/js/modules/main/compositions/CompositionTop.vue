<template>
    <div class="mb-3">
        <b-card class="shadow">
            <template v-if="load">
                <div class="text-center">
                    <b-spinner style="width: 3rem; height: 3rem;"></b-spinner>
                </div>
            </template>
            <template v-else>
                <b-card-title>
                    <div class="row mx-2">
                        Новые сборки
                        <div class="ml-auto">
                            <router-link to="/compositions">
                                <small>
                                    Полный список <b-icon-arrow-right></b-icon-arrow-right>
                                </small>
                            </router-link>
                        </div>
                    </div>
                </b-card-title>
                <template v-if="compositions.length">
                    <div v-for="composition of compositions" v-bind:key="composition.id" class="mb-3">
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
                </template>
                <template v-else>
                    <div class="text-center">
                        <p><span class="fa fa-sad-cry fa-10x"></span></p>
                        <p>К сожалению, ничего не найдено</p>
                    </div>
                </template>
            </template>
        </b-card>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "CompositionTop",
    data: () => ({
        load: true,
        compositions: []
    }),
    methods: {
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
        }
    },
    mounted() {
        let that = this
        axios.get('/compositions/latest').then(function (response) {
            that.compositions = response.data.data
            that.load = false
        })
    }
}
</script>

<style scoped>
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
