<template>
    <el-form :model="form" :rules="rules" v-on:submit.prevent="onSubmit" ref="profileForm" label-position="left">
        <el-form-item prop="name"
                      class="form-group"
                      :error="$t(errors.get('name'))">
            <el-input name="name"
                      type="name"
                      placeholder="Никнейм"
                      v-model="form.name"></el-input>
        </el-form-item>
        <el-form-item style="width:100%;">
            <el-button class="nk-btn nk-btn-rounded nk-btn-color-main-1" type="success" @click.prevent="onSubmit" :loading="loading">Сохранить</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
import {Errors} from "../../../includes/Errors";
import settingApi from "../../setting/api";

export default {
    name: 'profileForm',
    data() {
        return {
            loading: false,
            form: {
                name: this.$auth.user().name,
            },
            rules: {
                name: [
                    {required:true, message: 'Введите никнейм', trigger: 'blur'}
                ],
            },
            errors: new Errors()

        }
    },
    methods: {
        onSubmit(e) {
            this.validateForm('profileForm').then((valid)=>{
                if (valid) {
                    this.loading = true
                    this.errors.clear()

                    settingApi.update(this.form).then(response => {
                        this.$toast.open({
                            message: response.data.message,
                            type: response.data.type
                        })
                        if(response.data.type === 'success') {
                            this.$emit('saved')
                            this.$auth.user().name = this.form.name
                        }
                    }).catch((error) => {
                        if (error.response.data.errors)
                            this.errors.record(error.response.data.errors)
                    }).finally(() => this.loading = false)
                }
            });
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

<style lang="scss">

.el-form-item__label {
    padding: 0 !important;
}

.form-group {
    margin-bottom: 1rem;
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
