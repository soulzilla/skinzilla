<template>
    <div class="py-3">
        <h1>Инвентарь</h1>

        <div class="alert alert-warning d-md-none">
            Для лучшего опыта, воспользуйтесь версией для компьютерного браузера
        </div>

        <b-card class="shadow mt-3">
            <template v-if="load">
                <div class="text-center">
                    <b-spinner style="width: 3rem; height: 3rem;"></b-spinner>
                </div>
            </template>
            <template v-else>
                <div class="row">
                    <div class="ml-auto">
                        <b-link @click="handleSwitchSide">
                            <img :src="selectedSideIcon" height="30" alt="ct" @mouseover="handleHoverSide" @mouseout="handleOutSide">
                        </b-link>
                    </div>
                </div>
                <b-card-body>
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
                    <div id="ct-inventory">
                        <CtInventory :preset="ctItemsPreset" :side="1"/>
                    </div>
                    <div id="t-inventory" class="d-none">
                        <CtInventory :preset="tItemsPreset" :side="2"/>
                    </div>
                </b-card-body>
            </template>
        </b-card>
    </div>
</template>

<script>
import ct_preset from './ct_preset'
import t_preset from './t_preset'
import CtInventory from "./CtInventory";
import axios from "axios";

export default {
    name: "Inventory",
    components: {CtInventory},
    data: () => ({
        load: true,
        inventory: [],
        ctItemsPreset: [],
        tItemsPreset: [],
        selectedSideIcon: '/images/weapons/ct.png',
        selectedSide: 1 // ct = 1, t = 2
    }),
    methods: {
        handleHoverSide: function () {
            if (this.selectedSide === 1) {
                this.selectedSideIcon = '/images/weapons/t.png'
            } else {
                this.selectedSideIcon = '/images/weapons/ct.png'
            }
        },
        handleOutSide: function () {
            if (this.selectedSide === 1) {
                this.selectedSideIcon = '/images/weapons/ct.png'
            } else {
                this.selectedSideIcon = '/images/weapons/t.png'
            }
        },
        handleSwitchSide: function () {
            let ctInv = document.getElementById('ct-inventory')
            let tInv = document.getElementById('t-inventory')
            if (this.selectedSide === 1) {
                this.selectedSide = 2
                ctInv.classList.add('disappear')
                setTimeout(function () {
                    ctInv.classList.add('d-none')
                    ctInv.classList.remove('disappear')
                    tInv.classList.remove('d-none')
                }, 400)
            } else {
                this.selectedSide = 1
                tInv.classList.add('disappear')
                setTimeout(function () {
                    tInv.classList.add('d-none')
                    tInv.classList.remove('disappear')
                    ctInv.classList.remove('d-none')
                }, 400)
            }
        }
    },
    mounted() {
        let that = this
        that.ctItemsPreset = ct_preset.ct_preset
        that.tItemsPreset = t_preset.t_preset

        axios.get('/inventory').then(function (response) {
            if (response.data.data) {
                for (let item of response.data.data) {
                    let preset = that.ctItemsPreset
                    if (item.side === 2) {
                        preset = that.tItemsPreset
                    }

                    if (item.preset === 1) {
                        preset = preset.pistols
                    } else if (item.preset === 2) {
                        preset = preset.heavy
                    } else if (item.preset === 3) {
                        preset = preset.smg
                    } else if (item.preset === 4) {
                        preset = preset.rifles
                    } else {
                        preset = preset.additional
                    }

                    for (let weapon of preset.items) {
                        if (item.group.includes(weapon.gun_type)) {
                            weapon.skin = item.skin
                            break
                        }
                    }
                }
            }

            that.load = false
        })
    }
}
</script>

<style scoped>
    .disappear {
        visibility: hidden;
        transition: visibility .3s;
    }
</style>
