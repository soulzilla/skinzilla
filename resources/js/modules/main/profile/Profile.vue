<template>
    <section id="profile" v-if="$auth.check()">
        <div class="row">
            <div class="col-md-4 mt-3">
                <b-list-group class="shadow">
                    <b-list-group-item
                        v-for="item of sections"
                        v-bind:key="item.name"
                        @click="toggleSection(item.name)"
                        :class="section === item.name ? 'settings-menu-item selected' : 'settings-menu-item'">
                        <b-icon :icon="item.icon"></b-icon> {{ item.label }}
                    </b-list-group-item>
                </b-list-group>
            </div>
            <div class="col-md-8 mt-3">
                <b-card class="pb-0 shadow" title="Настройки" v-if="section === 'settings'">
                    <b-card-body>
                        <ProfileForm/>
                    </b-card-body>
                </b-card>

                <b-card class="pb-0 shadow" title="Безопасность" v-else-if="section === 'security'">
                    <b-card-body>
                        <PasswordForm/>
                    </b-card-body>
                </b-card>
            </div>
        </div>
    </section>
</template>

<script>
import ProfileForm from "./ProfileForm";
import PasswordForm from "./PasswordForm";

export default {
    name: "Profile",
    components: {ProfileForm, PasswordForm},
    data: () => ({
        section: 'settings',
        sections: [],
    }),
    methods: {
        toggleSection: function (name) {
            this.section = name;
        },
    },
    mounted() {
        this.sections = [
            {
                name: 'settings',
                icon: 'gear',
                label: 'Настройки'
            },
            {
                name: 'security',
                icon: 'lock',
                label: 'Безопасность'
            }
        ];
    }
}
</script>

<style lang="scss" scoped>
.settings-menu-item {
    cursor: pointer;
    background-color: #fff;
}

.settings-menu-item:hover, .settings-menu-item.selected {
    background-color: #fffbdb;
}
</style>
