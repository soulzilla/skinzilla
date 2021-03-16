<template>
    <el-form :model="form" :rules="rules" v-on:submit.prevent="onSubmit" ref="PasswordForm">
        <el-form-item prop="current_password"
                      class="form-group"
                      :error="$t(errors.get('current_password'))">
            <el-input name="current_password"
                      type="password"
                      placeholder="Текущий пароль"
                      v-model="form.current_password" show-password></el-input>
        </el-form-item>
        <el-form-item prop="new_password"
                      class="form-group"
                      :error="$t(errors.get('new_password'))">
            <el-input name="new_password"
                      type="password"
                      placeholder="Новый пароль"
                      v-model="form.new_password" show-password></el-input>
        </el-form-item>
        <el-form-item prop="new_confirm_password"
                      class="form-group"
                      :error="$t(errors.get('new_confirm_password'))">
            <el-input name="new_confirm_password"
                      type="password"
                      placeholder="Повторите новый пароль"
                      v-model="form.new_confirm_password" show-password></el-input>
        </el-form-item>
        <el-form-item style="width:100%;">
            <el-button type="success" @click.prevent="onSubmit" :loading="loading">Сохранить</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
import {Errors} from "../../../includes/Errors";
import settingApi from "../../setting/api";

export default {
    name: "PasswordForm",
    data() {
        return {
            loading: false,
            form: {
                current_password: '',
                new_password: '',
                new_confirm_password: ''
            },
            rules: {
                current_password: [
                    {required:true, message: 'Введите текущий пароль', trigger: 'blur'}
                ],
                new_password: [
                    {required:true, message: 'Введите новый пароль', trigger: 'blur'}
                ],
                new_confirm_password: [
                    {required:true, message: 'Повторите новый пароль', trigger: 'blur'},
                    { validator: this.checkPassIdentical, trigger: 'blur' }
                ],
            },
            errors: new Errors()

        }
    },
    methods: {
        onSubmit(e) {
            this.validateForm('PasswordForm').then((valid)=>{
                if (valid) {
                    this.loading = true;
                    this.errors.clear();

                    settingApi.password(this.form).then(response => {
                        this.$toast.open({
                            message: response.data.message,
                            type: response.data.type
                        })
                        if(response.data.type === 'success') {
                            this.$emit('saved')
                            this.form.current_password = '';
                            this.form.new_password = '';
                            this.form.new_confirm_password = '';
                        }
                    }).catch((error) => {
                        if (error.response.data.errors)
                            this.errors.record(error.response.data.errors)
                    }).finally(() => this.loading = false)
                }
            });
        },
        checkPassIdentical(rule, value, callback) {
            if (!value) {
                return callback(new Error('Повторите новый пароль'));
            }

            if (value !== this.form.new_password) {
                console.log(value)
                console.log(this.form.new_password)
                callback(new Error('Пароли не совпадают'));
            } else {
                callback();
            }
        },
        async validateForm(formName) {
            if (typeof this.errors !== 'undefined') {
                this.errors.clear(null)
            }
            return this.$refs[formName].validate()
        },
    },
}
</script>

<style scoped>

</style>
