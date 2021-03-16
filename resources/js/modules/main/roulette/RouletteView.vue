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
                                <div class="row">
                                    <div class="col-2">
                                        <b-img-lazy :src="item.logo" height="65"></b-img-lazy>
                                    </div>
                                    <div class="col-auto">
                                        {{ item.name }}
                                    </div>
                                </div>
                                <b-link target="_blank" :href="item.website" class="btn btn-info btn-block mt-3 text-white">
                                    На сайт
                                </b-link>
                            </b-card-title>
                            <b-card-body>
                                <Rating v-if="$auth.check()" :item="item" :entity_table="'roulettes'"/>
                                <div class="row border-top border-bottom mt-3 px-1">
                                    <div class="col col-4 text-center border-right">
                                        <p>Наша оценка</p>
                                        <p>{{ item.assessment }}</p>
                                    </div>
                                    <div class="col col-4 text-center border-right px-1">
                                        <p>Народная оценка</p>
                                        <p>{{ rounded(item.ratings_avg_rate) }}</p>
                                    </div>
                                    <div class="col col-4 text-center px-1">
                                        <p>Всего оценок</p>
                                        <p>{{ item.ratings_count }}</p>
                                    </div>
                                </div>
                                <template v-if="item.modes.length">
                                    <div class="mt-3">
                                        <h3>Режимы</h3>
                                        <div class="accordion" role="tablist">
                                            <template v-for="mode of item.modes">
                                                <b-card no-body class="mb-1">
                                                    <b-card-header header-tag="header" class="p-1" role="tab">
                                                        <b-button block v-b-toggle="'accordion-' + mode.id" variant="white">{{ mode.name }}</b-button>
                                                    </b-card-header>
                                                    <b-collapse :id="'accordion-' + mode.id" :accordion="item.name + '-accordion'" role="tabpanel">
                                                        <b-card-body>
                                                            <b-card-text>{{ mode.description }}</b-card-text>
                                                        </b-card-body>
                                                    </b-collapse>
                                                </b-card>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                <div class="row mt-3">
                                    <div class="col-6 px-1 border-right text-center">
                                        <p>Для новых пользователей</p>
                                        <a :href="item.ref_link"
                                           class="btn btn-primary"
                                           :id="'roulette-bonus-' + item.id">
                                            Регистрация
                                        </a>
                                        <b-popover :target="'roulette-bonus-' + item.id" triggers="hover">
                                            Зарегистрируйтесь по нашей ссылке и получите бонус!
                                        </b-popover>
                                    </div>
                                    <div class="col-6 px-1 text-center">
                                        <p>Для всех пользователей</p>
                                        <button class="btn btn-primary" @click="copyToClipboard(item.promo_code)" :id="'roulette-promo-' + item.id">{{ item.promo_code }}</button>
                                        <b-popover :target="'roulette-promo-' + item.id" triggers="hover">
                                            {{ item.promo_code_description }}
                                        </b-popover>
                                    </div>
                                </div>
                            </b-card-body>
                        </template>
                        <template #footer class="bg-white">
                            <ItemFooter :item="item" :entity_table="'roulettes'"/>
                        </template>
                    </b-card>
                </template>
            </div>
            <div class="col-lg-4 d-none d-md-block">
                <Banners/>
            </div>
        </div>
        <div class="my-3" v-if="!load">
            <Comments :entity_id="item.id" :entity_table="'roulettes'"/>
        </div>
    </div>
</template>

<script>
import rouletteApi from '../../roulette/api'
import Banners from "../components/Banners"
import Rating from "../components/Rating"
import ItemFooter from "../components/ItemFooter"
import Comments from "../components/Comments"

export default {
    name: "RouletteView",
    components: {Comments, ItemFooter, Rating, Banners},
    data: () => ({
        load: true,
        item: {}
    }),
    methods: {
        rounded: function (number) {
            if (!number) {
                return 0;
            }

            return parseFloat(number).toFixed(2)
        },
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
        rouletteApi.one(this.$route.params.id).then(function (response) {
            that.item = response.data.data
            that.load = false
        })
    }
}
</script>

<style scoped>

</style>
