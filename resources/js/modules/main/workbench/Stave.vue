<template>
    <div>
        <div class="row mx-1 mt-3" v-if="!load">
            <div class="col-12 col-md-2 px-0 m-1" v-for="item of composition.skins" v-bind:key="item.id">
                <div class="img-thumbnail" :class="getSkinRarityClass(item)">
                    <div class="row">
                        <div class="ml-auto mr-3">
                            <b-link @click="removeSkinFromSet(item)">
                                <b-badge variant="light">
                                    <b-icon-x></b-icon-x>
                                </b-badge>
                            </b-link>

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
            <div class="col-12 col-md-2 px-0 m-1" v-if="composition.skins.length < 10">
                <div class="img-thumbnail">
                    <div class="text-center p-5">
                        <b-link @click="handleAdd()">
                            <i class="fa fa-plus fa-3x"></i>
                        </b-link>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showSearch">


            <div class="row mt-3">
                <div class="col col-auto">
                    <b-input-group class="mb-3">
                        <b-form-input placeholder="Название" @change="applyFilter" v-model="filter.name"></b-form-input>
                        <b-input-group-append>
                            <b-button size="sm" variant="danger" @click="resetName">
                                <b-icon-x></b-icon-x>
                            </b-button>
                        </b-input-group-append>
                    </b-input-group>
                </div>
                <div class="col col-auto">
                    <b-input-group class="mb-3">
                        <b-form-input placeholder="Минимальная цена" @change="applyFilter" v-model="filter.min_cost" type="number"></b-form-input>
                        <b-input-group-append>
                            <b-button size="sm" variant="danger" @click="resetMinVal">
                                <b-icon-x></b-icon-x>
                            </b-button>
                        </b-input-group-append>
                    </b-input-group>
                </div>
                <div class="col col-auto">
                    <b-input-group class="mb-3">
                        <b-form-input placeholder="Максимальная цена" @change="applyFilter" v-model="filter.max_cost" type="number"></b-form-input>
                        <b-input-group-append>
                            <b-button size="sm" variant="danger" @click="resetMaxVal">
                                <b-icon-x></b-icon-x>
                            </b-button>
                        </b-input-group-append>
                    </b-input-group>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <b-form-group label="Редкость">
                        <b-form-checkbox-group stacked>
                            <b-form-checkbox
                                v-for="rarity of rarities"
                                @change="toggleRarityFilter"
                                v-bind:key="'rar-' + rarity.value"
                                v-model="filter.rarity"
                                :value="rarity.value">
                                {{ rarity.label }}
                            </b-form-checkbox>
                        </b-form-checkbox-group>
                    </b-form-group>
                </div>
                <div class="col-12 col-md-6">
                    <b-form-group label="Качество" class="mb-0">
                        <b-form-checkbox-group stacked>
                            <b-form-checkbox
                                v-for="quality of qualities"
                                v-bind:key="'qual-' + quality.value"
                                @change="toggleQualityFilter"
                                v-model="filter.quality"
                                :value="quality.value">
                                {{ quality.label }}
                            </b-form-checkbox>
                        </b-form-checkbox-group>
                    </b-form-group>
                    <b-form-group label="Цена">
                        <b-form-radio value="desc" v-model="filter.sort" @change="descSort">Сначала дорогие</b-form-radio>
                        <b-form-radio value="asc" v-model="filter.sort" @change="ascSort">Сначала дешёвые</b-form-radio>
                    </b-form-group>
                </div>
            </div>

            <div class="row" v-if="result.length">
                <div class="col-12 col-md-2 px-0 m-1" v-for="skin of result" v-bind:key="'skin-' + skin.id">
                    <b-link class="text-decoration-none" @click="addSkinToComposition(skin)">
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
                    </b-link>
                </div>
                <div class="col-12" v-if="resultNext">
                    <b-link class="btn btn-block btn-outline-primary" @click="loadMore">
                        Загрузить ещё
                    </b-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Stave",
    props: {
        composition: null,
    },
    data: () => ({
        rarities: [
            {value: 1, label: 'Ширпотреб'},
            {value: 2, label: 'Промышленное'},
            {value: 3, label: 'Армейское'},
            {value: 4, label: 'Запрещённое'},
            {value: 5, label: 'Засекреченное'},
            {value: 6, label: 'Тайное'},
            {value: 7, label: 'Редкое'},
            {value: 8, label: 'Контрабанда'},
        ],
        qualities: [
            {value: 1, label: 'Прямо с завода'},
            {value: 2, label: 'Немного поношенное'},
            {value: 3, label: 'После полевых испытаний'},
            {value: 4, label: 'Поношенное'},
            {value: 5, label: 'Закалённое в боях'},
        ],
        filter: {
            gun_type: null,
            rarity: null,
            quality: null,
            sort: 'desc',
            name: null,
            min_cost: null,
            max_cost: null,
            skin_id: []
        },
        load: true,
        showSearch: false,
        loadSearch: true,
        result: [],
        resultNext: null
    }),
    methods: {
        handleAdd: function () {
            this.showSearch = true
            this.loadSearch = true

            this.applyFilter()
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
        getSkinTypeClass: function (skin) {
            if (skin.stat_track) {
                return 'weapon-stat-track';
            }
            if (skin.souvenir) {
                return 'weapon-souvenir';
            }

            return '';
        },
        loadMore: function () {
            let that = this
            axios.get(this.resultNext, {
                params: this.filter
            }).then(function (response) {
                for (let item of response.data.data) {
                    that.result.push(item)
                }
                that.resultNext = response.data.links.next
            })
        },
        toggleRarityFilter: function (values) {
            this.filter.rarity = values
            this.applyFilter()
        },
        toggleQualityFilter: function (values) {
            this.filter.quality = values
            this.applyFilter()
        },
        ascSort: function () {
            this.filter.sort = 'asc'
            this.applyFilter()
        },
        descSort: function () {
            this.filter.sort = 'desc'
            this.applyFilter()
        },
        resetName: function () {
            this.filter.name = null
            this.applyFilter()
        },
        resetMinVal: function () {
            this.filter.min_cost = null
            this.applyFilter()
        },
        resetMaxVal: function () {
            this.filter.max_cost = null
            this.applyFilter()
        },
        applyFilter: function () {
            let that = this

            axios.get('/search', {
                params: this.filter
            }).then(function (response) {
                that.result = response.data.data
                that.loadSearch = false
                that.resultNext = response.data.links.next
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
        addSkinToComposition: function (skin) {
            let data = {
                skin_id: skin.id,
                composition_id: this.composition.id
            }, that = this

            that.load = true

            axios.post('/compositions/add', data).then(function (response) {
                that.composition.skins.push(response.data.data)

                that.load = false
                that.$toast.success('Предмет добавлен в сет')
                that.filter.skin_id.push(response.data.data.id)

                if (that.composition.skins.length === 10) {
                    that.showSearch = false
                    setTimeout(function () {
                        that.$toast.success('Сет собран!')
                    }, 1000)
                } else {
                    that.applyFilter()
                }
            })
        },
        removeSkinFromSet: function (item) {
            this.load = true
            let that = this,
                data = {
                    skin_id: item.id,
                    composition_id: this.composition.id
                }

            axios.delete('/compositions/remove', {
                params: data
            }).then(function (response) {
                that.load = false
                that.composition.skins = response.data.data
                that.filter.skin_id = []
                for (let item of that.composition.skins) {
                    that.filter.skin_id.push(item.id)
                }
                that.applyFilter()
                that.$toast.success('Предмет удалён из сета')
            })
        }
    },
    mounted() {
        this.load = false
        if (!this.composition.skins) {
            this.composition.skins = []
        } else {
            for (let skin of this.composition.skins) {
                this.filter.skin_id.push(skin.id)
            }
        }
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
