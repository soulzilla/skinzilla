<template>
    <div>
        <div class="row justify-content-center">
            <div class="col col-auto my-1">
                <b-link @click="changeGroup(1)" :class="getClass(1)">
                    Пистолеты
                </b-link>
            </div>
            <div class="col col-auto my-1">
                <b-link @click="changeGroup(2)" :class="getClass(2)">
                    Тяжёлые оружия
                </b-link>
            </div>
            <div class="col col-auto my-1">
                <b-link @click="changeGroup(3)" :class="getClass(3)">
                    Пистолеты-пулемёты
                </b-link>
            </div>
            <div class="col col-auto my-1">
                <b-link @click="changeGroup(4)" :class="getClass(4)">
                    Винтовки
                </b-link>
            </div>
            <div class="col col-auto my-1">
                <b-link @click="changeGroup(5)" :class="getClass(5)">
                    Дополнительно
                </b-link>
            </div>
        </div>

        <div class="mt-3">
            <div class="row" v-if="!load">
                <div class="col-12 col-md-2 px-0 m-1" v-for="item of currentPreset" v-bind:key="item.key">
                    <template v-if="item.skin">
                        <div class="img-thumbnail" :class="getSkinRarityClass(item.skin)">
                            <div class="row">
                                <div class="ml-auto mr-3">
                                    <b-link @click="removeItemFromInventory(item)">
                                        <b-badge variant="light">
                                            <b-icon-x></b-icon-x>
                                        </b-badge>
                                    </b-link>

                                    <b-badge variant="success">{{ item.skin.cost }}</b-badge>

                                    <b-badge v-b-popover.hover="getQualityName(item.skin.quality)">
                                        {{ getQualityShortcut(item.skin.quality) }}</b-badge>

                                    <b-badge variant="danger" v-if="item.skin.stat_track" v-b-popover.hover="'StatTrak'">
                                        ST</b-badge>

                                    <b-badge variant="warning" v-if="item.skin.souvenir" v-b-popover.hover="'Сувенирный'">
                                        S</b-badge>
                                </div>
                            </div>
                            <b-img-lazy :src="item.skin.image" thumbnail></b-img-lazy>
                            <div class="text-white"><b>{{ item.skin.name }}</b></div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="img-thumbnail">
                            <div class="row justify-content-center">
                                <b-img-lazy :src="item.icon" :height="item.height"></b-img-lazy>
                            </div>
                            <div class="text-center p-5">
                                <b-link @click="handleAdd(item)">
                                    <i class="fa fa-plus fa-3x"></i>
                                </b-link>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="text-center" v-else>
                <b-spinner style="width: 3rem; height: 3rem;"></b-spinner>
            </div>
        </div>

        <div class="mt-3" v-if="showSearch">
            <template v-if="loadSearch">
                <b-card>
                    <b-card-body>
                        <div class="text-center">
                            <b-spinner style="width: 3rem; height: 3rem;"></b-spinner>
                        </div>
                    </b-card-body>
                </b-card>
            </template>
            <template v-else>
                <b-card>
                    <b-card-body>
                        <div class="row">
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
                                <b-link class="text-decoration-none" @click="addSkinToInventory(skin)">
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
                    </b-card-body>
                </b-card>
            </template>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "CtInventory",
    props: {
        preset: null,
        side: null
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
        load: true,
        selectedGroup: 1,
        currentPreset: [],
        resultNext: null,
        filter: {
            gun_type: null,
            rarity: null,
            quality: null,
            sort: 'desc',
            name: null,
            min_cost: null,
            max_cost: null,
        },
        showSearch: false,
        loadSearch: true,
        groupLoading: true,
        result: [],
    }),
    methods: {
        changeGroup: function (key) {
            this.groupLoading = true
            this.selectedGroup = key
            switch (this.selectedGroup) {
                case 1: this.currentPreset = this.preset.pistols.items
                    break
                case 2: this.currentPreset = this.preset.heavy.items
                    break
                case 3: this.currentPreset = this.preset.smg.items
                    break
                case 4: this.currentPreset = this.preset.rifles.items
                    break
                case 5: this.currentPreset = this.preset.additional.items
                    break
            }
            this.groupLoading = false
            this.showSearch = false
        },
        getClass: function (key) {
            if (key === this.selectedGroup) {
                return 'btn btn-primary'
            }

            return 'btn btn-outline-primary'
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
        handleAdd: function (item) {
            this.showSearch = true
            this.loadSearch = true
            this.filter.gun_type = item.gun_type
            var that = this

            axios.get('/search', {
                params: {
                    gun_type: item.gun_type
                }
            }).then(function (response) {
                that.result = response.data.data
                that.loadSearch = false
                that.resultNext = response.data.links.next
                that.filter.gun_type = item.gun_type
            })
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
        addSkinToInventory: function (skin) {
            let data = {
                side: this.side,
                skin_id: skin.id,
                gun_type: skin.gun_type,
            }, that = this

            that.load = true

            axios.post('/inventory', data).then(function (response) {
                let preset = response.data.preset,
                    group = response.data.group

                if (preset === 1) {
                    preset = that.preset.pistols
                } else if (preset === 2) {
                    preset = that.preset.heavy
                } else if (preset === 3) {
                    preset = that.preset.smg
                } else if (preset === 4) {
                    preset = that.preset.rifles
                } else {
                    preset = that.preset.additional
                }

                for (let item of preset.items) {
                    if (group.includes(item.gun_type)) {
                        item.skin = skin
                        break
                    }
                }

                that.load = false
                that.$toast.success('Предмет добавлен в инвентарь')
            })
        },
        removeItemFromInventory: function (item) {
            this.load = true
            let that = this,
                data = {
                    side: that.side,
                    skin_id: item.skin.id
                }

            axios.delete('/inventory', {
                params: data
            }).then(function (response) {
                item.skin = null
                that.load = false
                that.$toast.success('Предмет удалён из инвентаря')
            })
        }
    },
    mounted() {
        this.selectedGroup = 1;
        this.currentPreset = this.preset.pistols.items
        this.groupLoading = false
        this.load = false
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
