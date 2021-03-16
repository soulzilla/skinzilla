<template>
    <div class="py-3">
        <b-card class="shadow" title="Верстак">
            <template v-if="load">
                <div class="text-center">
                    <b-spinner style="width: 3rem; height: 3rem;"></b-spinner>
                </div>
            </template>
            <template v-else>
                <div class="alert alert-warning d-md-none">
                    Для лучшего опыта, воспользуйтесь версией для компьютерного браузера
                </div>
                <div>
                    <b-link
                        v-for="composition of userCompositions"
                        @click="currentComposition = composition"
                        v-bind:key="composition.id"
                        class="mx-1 btn" :class="currentComposition && currentComposition.id === composition.id ? 'btn-primary' : 'btn-outline-primary'">
                        {{ composition.name }}
                    </b-link>
                    <b-link class="mx-1 btn btn-success" @click="showForm = true">
                        Создать сет
                    </b-link>
                </div>
                <div v-if="currentComposition">
                    <div class="mt-3">
                        <b-link class="btn btn-outline-danger" @click="handleDeleteComposition">Удалить сет</b-link>
                        <b-link class="btn btn-outline-warning" @click="handleClearComposition">Очистить сет</b-link>
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
                    <Stave :composition="currentComposition"/>
                </div>
            </template>
        </b-card>

        <el-dialog
            :visible.sync="showForm"
            close-on-click-modal
            v-on:close-dialog="showForm = false"
            :modalAppendToBody="false">
            <el-form>
                <el-form-item prop="name">
                    <el-input v-model="form.name" placeholder="Придумайте название сета">
                    </el-input>
                </el-form-item>
                <el-button @click="submitForm" :disabled="form.name.length === 0">
                    Сохранить
                </el-button>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
import axios from "axios";
import Stave from "../workbench/Stave";

export default {
    name: "Workbench",
    components: {Stave},
    data: () => ({
        load: true,
        showForm: false,
        form: {
            name: ''
        },
        userCompositions: [],
        currentComposition: null
    }),
    methods: {
        submitForm: function () {
            let that = this
            axios.post('/compositions', {
                name: this.form.name
            }).then(function (response) {
                that.showForm = false
                that.userCompositions.push(response.data.data)
                that.currentComposition = response.data.data
            })
        },
        handleDeleteComposition: function () {
            let that = this
            that.load = true

            axios.post('/composition/delete/' + this.currentComposition.id, {
                _method: 'DELETE'
            }).then(function () {
                that.currentComposition = null
                axios.get('/compositions/my').then(function (response) {
                    that.userCompositions = response.data.data ? response.data.data : []
                    that.load = false
                    that.$toast.success('Сет удалён')
                })
            })
        },
        handleClearComposition: function () {
            let that = this
            axios.post('/composition/clear/' + this.currentComposition.id, {
                _method: 'DELETE'
            }).then(function () {
                that.currentComposition.skins = []
                that.$toast.success('Сет очищен')
            })
        }
    },
    mounted() {
        let that = this
        axios.get('/compositions/my').then(function (response) {
            that.userCompositions = response.data.data ? response.data.data : []
            that.load = false
        })
    }
}
</script>

<style scoped>

</style>
