<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="dark">
            <div class="container">
                <b-navbar-brand href="#">
                    <router-link to="/">
                        <img src="/images/logo.png" alt="logo" height="30px"/>
                    </router-link>
                </b-navbar-brand>

                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

                <b-collapse id="nav-collapse" is-nav>
                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto">
                        <b-nav-item v-if="this.$auth.check()">
                            <router-link class="text-white" to="/workbench">
                                <b-icon-tools></b-icon-tools> Крафт сета
                            </router-link>
                        </b-nav-item>
                        <b-nav-item>
                            <router-link class="text-white" to="/gambling">
                                <b-icon icon="box"></b-icon> Сайты с кейсами
                            </router-link>
                        </b-nav-item>
                        <b-nav-item>
                            <router-link class="text-white" to="/roulette">
                                <b-icon icon="bar-chart"></b-icon> Рулетки
                            </router-link>
                        </b-nav-item>
                        <b-nav-item>
                            <router-link class="text-white" to="/markets">
                                <b-icon icon="arrow-down-up"></b-icon> Трейд площадки
                            </router-link>
                        </b-nav-item>

                        <template v-if="this.$auth.check()">
                            <b-nav-item-dropdown right>
                                <template #button-content>
                                    <span class="text-white"><b-icon-person></b-icon-person> {{ $auth.user().name }}</span>
                                </template>
                                <template>
                                    <b-dropdown-item>
                                        <router-link to="/profile">Настройки</router-link>
                                    </b-dropdown-item>
                                    <b-dropdown-item>
                                        <router-link to="/inventory">Инвентарь</router-link>
                                    </b-dropdown-item>
                                    <b-dropdown-item v-if="$auth.user().is_admin">
                                        <router-link to="/admin">Панель админа</router-link>
                                    </b-dropdown-item>
                                    <b-dropdown-item @click.native="logout" dusk="logout">Выйти</b-dropdown-item>
                                </template>
                            </b-nav-item-dropdown>
                        </template>
                        <template v-else>
                            <b-nav-item @click="handleAuth">
                                <span class="text-white"><b-icon-person></b-icon-person> Войти</span>
                            </b-nav-item>
                        </template>
                    </b-navbar-nav>
                </b-collapse>
            </div>
        </b-navbar>
        <el-dialog
            :visible.sync="authFormVisible"
            close-on-click-modal
            v-on:close-dialog="authFormVisible = false"
            :modalAppendToBody="false">
            <Auth @cancel="authFormVisible = false" @close-dialog="authFormVisible = false"/>
        </el-dialog>
    </div>
</template>

<script>
import Auth from "./Auth";

export default {
    name: "FrontNav",
    data: () => ({
        sysUserName: '',
        authFormVisible: false,
    }),
    components: {Auth},
    methods: {
        logout: function () {
            this.$confirm('Вы уверенны, что хотите выйти?', 'Выход', {
                confirmButtonText: 'Ок',
                cancelButtonText: 'Отмена',
            }).then(() => {
                this.$auth.logout()
            })
        },
        handleAuth() {
            this.authFormVisible = true;
            this.formData = {};
        },
    },
    mounted() {
        const user = this.$auth.user();
        if (user) {
            this.sysUserName = user.name || '';
        }
    }
}
</script>

<style scoped>

</style>
