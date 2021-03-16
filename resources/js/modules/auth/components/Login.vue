<template>
    <div>
        <login-form @submit="onSubmit" :errors="authErrors" :loading="loading"></login-form>
    </div>
</template>

<script>
    import LoginForm from './LoginForm'
    export default {
        name: 'Login',
        components: {LoginForm},
        data() {
            return {
                authErrors: {},
                loading: false,
            }
        },
        methods: {
            onSubmit(loginData) {
                this.loading = true
                this.$auth
                    .login({
                        data: loginData,
                    })
                    .then(() => {
                        this.$emit('close-dialog');
                    }, error => {
                        if (error.response.status === 422)
                            this.authErrors = error.response.data.errors
                    })
                    .finally(() => this.loading = false);
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
