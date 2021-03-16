<template>
    <div class="text-center pt-3">
        <b-card class="shadow">
            <div class="text-center">
                <template v-if="load">
                    <b-spinner style="width: 3rem; height: 3rem;"></b-spinner>
                </template>
                <template v-else>
                    <p><span class="fa fa-sad-cry fa-10x"></span></p>
                    <p>Я не знаю такой страницы, попробуйте пройти по другим ссылкам, например <router-link :to="randomUrl">сюда</router-link></p>
                </template>
            </div>
        </b-card>
    </div>
</template>

<script>
    export default {
        name: 'NotFound',
        data: () => ({
            load: true,
            urls: [],
            randomUrl: ''
        }),
        methods: {
            getRandomUrl: function () {
                return this.urls[Math.floor(Math.random() * this.urls.length)]
            }
        },
        mounted() {
            this.urls = [
                '/workbench',
                '/gambling',
                '/markets',
                '/roulette',
                '/compositions'
            ]

            this.randomUrl = this.getRandomUrl()

            this.load = false
        }
    }
</script>
