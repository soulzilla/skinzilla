<template>
    <div>
        <b-tabs content-class="mt-3">
            <b-tab title="Войти" active>
                <Login v-on="$listeners" />
            </b-tab>
            <b-tab title="Регистрация">
                <Register v-on="$listeners" />
            </b-tab>
        </b-tabs>
    </div>
</template>

<script>
import Login from "../../modules/auth/components/Login";
import Register from "../../modules/auth/components/Register";

export default {
    name: "Auth",
    components: {Login, Register}
}
</script>

<style scoped>

</style>
