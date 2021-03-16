<template>
    <el-form @keyup.enter.native="onSubmit" :model="form" :rules="rules" ref="loginForm">
        <el-form-item prop="email"
                      :error="$t(formErrors.get('email'))"
                      class="form-group">
            <el-input
                name="email"
                type="text"
                placeholder="Почта"
                v-model="form.email"
            ></el-input>
        </el-form-item>
        <el-form-item prop="password"
                      :error="$t(formErrors.get('password'))"
                      class="form-group">
            <el-input
                name="password"
                type="password"
                placeholder="Пароль"
                v-model="form.password"
                show-password></el-input>
        </el-form-item>
        <el-form-item style="width:100%;">
            <el-button @click.native="onSubmit" :loading="loading" class="w-100">
                Войти
            </el-button>
        </el-form-item>
    </el-form>
</template>

<script>
    import {Errors} from "../../../includes/Errors";

    export default {
        name: 'LoginForm',
        props: {
            errors: Object,
            loading: false
        },
        data() {
            return {
                form: {
                    email: '',
                    password: ''
                },
                rules: {
                    email:      [{required:true, message: 'Заполните почту', trigger: 'blur'}],
                    password:   [{required:true, message: 'Введите пароль', trigger: 'blur'}],
                },
                formErrors: new Errors()
            }
        },
        watch: {
            errors: function () {
                this.formErrors.record(this.errors);
            }
        },
        methods: {
            onSubmit(e) {
                this.$refs['loginForm'].validate((valid) => {
                    if (valid) {
                        this.$emit('submit', {
                            ...this.form
                        })
                    }
                })
            },
        },
    }
</script>

<style scoped lang="scss">
    .el-form-item__label {
        padding: 0 !important;
    }

    .sign-up-form {

        .el-form-item {
            margin-bottom: 12px;

            .el-form-item__label {
                padding: 0 !important;
            }
        }
    }

</style>
