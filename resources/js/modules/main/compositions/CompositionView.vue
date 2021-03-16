<template>
    <div class="my-3">
        <div class="row">
            <div class="col-lg-8">
                <template v-if="load">
                    <b-card class="shadow">
                        <b-card-body>
                            <div class="text-center">
                                <b-spinner style="width: 3rem; height: 3rem;"></b-spinner>
                            </div>
                        </b-card-body>
                    </b-card>
                </template>
                <template v-else>
                    <b-card class="shadow" footer-tag="footer">
                        <template v-if="!item">
                            <b-card-body>
                                <div class="text-center">
                                    <p><span class="fa fa-sad-cry fa-10x"></span></p>
                                    <p>Я не знаю такой страницы, попробуйте пройти по другим ссылкам, например <router-link to="/workbench">сюда</router-link></p>
                                </div>
                            </b-card-body>
                        </template>
                        <template v-else>
                            <b-card-title>
                                {{ item.name }} <small>{{ item.user.name }}</small>
                            </b-card-title>
                            <div v-if="$auth.user() && (item.user_id !== $auth.user().id)">
                                <b-link @click="copyComposition" v-if="!item.copy" class="btn btn-primary">
                                    <b-icon icon="files"></b-icon> Скопировать
                                </b-link>
                                <template v-if="!item.favourite">
                                    <b-link @click="toFavourite" class="btn btn-outline-warning text-dark">
                                        <b-icon-star></b-icon-star> В избранное
                                    </b-link>
                                </template>
                                <template v-else>
                                    <b-link @click="removeFavourite" class="btn btn-warning text-dark">
                                        <b-icon-star-fill></b-icon-star-fill> Удалить из избранного
                                    </b-link>
                                </template>
                            </div>
                            <div class="mt-3">
                                <router-link to="/gambling" class="btn btn-success m-1">
                                    <b-icon-box></b-icon-box> Хочу выбить
                                </router-link>
                                <router-link to="/roulette" class="btn btn-success m-1">
                                    <b-icon-bar-chart></b-icon-bar-chart> Хочу выиграть
                                </router-link>
                                <router-link to="/markets" class="btn btn-success m-1">
                                    <b-icon-cash></b-icon-cash> Хочу купить
                                </router-link>
                            </div>
                            <div class="row mx-1 mt-3">
                                <div class="col-12 col-md-3 px-0 m-1" v-for="skin of item.skins" v-bind:key="skin.id">
                                    <div class="img-thumbnail" :class="getSkinRarityClass(skin)">
                                        <div class="row">
                                            <div class="ml-auto mr-3">
                                                <b-badge variant="success">{{ skin.cost }}</b-badge>

                                                <b-badge v-b-popover.hover="getQualityName(skin.quality)">
                                                    {{ getQualityShortcut(skin.quality) }}</b-badge>

                                                <b-badge variant="danger" v-if="skin.stat_track" v-b-popover.hover="'StatTrak'">
                                                    ST</b-badge>

                                                <b-badge variant="warning" v-if="skin.souvenir" v-b-popover.hover="'Сувенирный'">
                                                    S</b-badge>
                                            </div>
                                        </div>
                                        <b-img-lazy :src="skin.image" thumbnail></b-img-lazy>
                                        <div class="text-white"><b>{{ skin.name }}</b></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template #footer class="bg-white">
                            <ItemFooter :item="item" :entity_table="'compositions'"/>
                        </template>
                    </b-card>
                </template>
            </div>
            <div class="col-lg-4 d-none d-md-block">
                <Banners/>
            </div>
        </div>
        <div class="my-3" v-if="!load">
            <Comments :entity_id="item.id" :entity_table="'compositions'" :entity_author="item.user.id"/>
        </div>
    </div>
</template>

<script>
import Comments from "../components/Comments";
import ItemFooter from "../components/ItemFooter";
import Rating from "../components/Rating";
import Banners from "../components/Banners";
import axios from "axios";

export default {
    name: "CompositionView",
    components: {Comments, ItemFooter, Rating, Banners},
    data: () => ({
        load: true,
        item: {}
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
        },
        copyComposition: function () {
            let that = this
            axios.post('/composition/copy', {
                id: that.item.id
            }).then(function (response) {
                that.item.copy = response.data.data
                that.$toast.success('Сет скопирован')
            })
        },
        toFavourite: function () {
            let that = this

            axios.post('/favourites', {
                id: that.item.id
            }).then(function (response) {
                that.item.favourite = response.data.data
                that.$toast.success('Добавлено в избранное')
            })
        },
        removeFavourite: function () {
            let that = this

            axios.delete('/favourites', {
                params: {
                    id: that.item.id
                }
            }).then(function (response) {
                that.item.favourite = null
                that.$toast.success('Удалено из избранного')
            })
        }
    },
    mounted() {
        let that = this, id = this.$route.params.id
        axios.get('/composition/' + id).then(function (response) {
            that.item = response.data.data
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
