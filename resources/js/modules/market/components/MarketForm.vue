<template>
    <section id="market-form">
        <el-form
            :model="form"
            label-width="120px"
            ref="form"
            :rules="rules"
            size="small"
            @submit.native.prevent="saveSubmit">
            <el-form-item label="Market Name" prop="name" :error="errors.get('name')">
                <el-input
                    v-model="form.name"
                    @change="errors.clear('name')"
                    suffix-icon="el-icon-edit">
                </el-input>
            </el-form-item>
            <el-form-item label="Canonical" prop="name_canonical" :error="errors.get('name_canonical')">
                <el-input
                    v-model="form.name_canonical"
                    @change="errors.clear('name_canonical')"
                    suffix-icon="el-icon-edit">
                </el-input>
            </el-form-item>
            <el-form-item label="Description" prop="description" :error="errors.get('description')">
                <el-input
                    v-model="form.description"
                    type="textarea"
                    rows="5"
                    @change="errors.clear('description')"
                    suffix-icon="el-icon-edit">
                </el-input>
            </el-form-item>
            <el-form-item label="Website" prop="website" :error="errors.get('website')">
                <el-input
                    type="url"
                    v-model="form.website"
                    @change="errors.clear('website')"
                    suffix-icon="el-icon-edit">
                </el-input>
            </el-form-item>
            <el-form-item label="Ref link" prop="ref_link" :error="errors.get('ref_link')">
                <el-input
                    type="url"
                    v-model="form.ref_link"
                    @change="errors.clear('ref_link')"
                    suffix-icon="el-icon-edit">
                </el-input>
            </el-form-item>
            <el-form-item label="Promo code" prop="promo_code" :error="errors.get('promo_code')">
                <el-input
                    v-model="form.promo_code"
                    @change="errors.clear('promo_code')"
                    suffix-icon="el-icon-edit">
                </el-input>
            </el-form-item>
            <el-form-item label="Promo code description" prop="promo_code_description" :error="errors.get('promo_code_description')">
                <el-input
                    v-model="form.promo_code_description"
                    @change="errors.clear('promo_code_description')"
                    suffix-icon="el-icon-edit">
                </el-input>
            </el-form-item>
            <el-form-item label="Order" prop="order" :error="errors.get('order')">
                <el-input-number
                    v-model="form.order"
                    @change="errors.clear('order')">
                </el-input-number>
            </el-form-item>
            <el-form-item label="Published" prop="published" :error="errors.get('published')">
                <el-checkbox v-model="form.published">
                </el-checkbox>
            </el-form-item>
            <el-form-item label="Our rating" prop="assessment" :error="errors.get('assessment')">
                <el-input
                    v-model="form.assessment"
                    @change="errors.clear('assessment')"
                    suffix-icon="el-icon-edit">
                </el-input>
            </el-form-item>
            <el-form-item label="Recommendation level" prop="recommendation_level" :error="errors.get('recommendation_level')">
                <el-input-number
                    v-model="form.recommendation_level"
                    @change="errors.clear('recommendation_level')">
                </el-input-number>
            </el-form-item>
            <el-form-item label="Logo" prop="logo">
                <el-upload
                    action="/api/v1/file/upload"
                    v-model="form.logo"
                    :before-remove="handleRemove"
                    :on-success="handleSuccess"
                    accept="image/*">
                    <el-button size="small" type="primary">Upload</el-button>
                </el-upload>
                <template v-if="form.logo">
                    <el-image :src="form.logo"></el-image>
                </template>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click.native="cancel" size="small">{{$t('global.cancel')}}</el-button>
            <el-button type="success"
                       @click.native="saveSubmit"
                       :loading="formLoading"
                       size="small"
                       class="float-right">{{$t('global.save')}}</el-button>
        </div>
    </section>
</template>

<script>
    import {Errors} from "../../../includes/Errors";
    import marketApi from '../api'
    import axios from "axios";

    export default {
        name: "MarketForm",
        props: {
            initialForm: {
                default: () => ({})
            },
        },
        data() {
            return {
                errors: new Errors(),
                formLoading: false,
                formTitle: 'New Market',
                form: {},
                rules: {
                    name: [
                        {required: true, message: 'market name is required'},
                    ],
                    description: [
                        {required: true, message: 'market name is required'},
                    ],
                    name_canonical: [
                        {required: true, message: 'canonical name is required'},
                    ],
                    website: [
                        {required: true, message: 'website is required'}
                    ],
                    promo_code: [
                    ],
                    ref_link: [
                        {required: true, message: 'ref_link is required'}
                    ],
                    assessment: [
                        {required: true, message: 'assessment is required'}
                    ],
                    recommendation_level: [
                    ],
                    logo: [
                    ],
                },
            }
        },
        methods: {
            saveSubmit() {
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        this.formLoading = true
                        this.errors.clear()
                        let action = this.form.id ? marketApi.update : marketApi.create

                        action(this.form).then((response) => {
                            this.$message({
                                message: response.data.message,
                                type: response.data.type
                            })
                            if(response.data.type === 'success')
                                this.$emit('saved')
                        }).catch((error) => {
                            if (error.response.data.errors)
                                this.errors.record(error.response.data.errors)
                        }).finally(() => this.formLoading = false)
                    }
                });
            },
            cancel() {
                this.clearForm()
                this.$emit('cancel')
            },
            clearForm() {
                this.errors.clear()
                if (this.$refs['form'])
                    this.$refs['form'].resetFields()
            },
            handleRemove: function (file, filesList) {
                axios.delete('/file/delete', {
                    params: {
                        name: file.response[0]
                    }
                })
            },
            handleSuccess: function (response) {
                this.form.logo = response[0]
            }
        },
        mounted() {
            this.form = Object.assign({}, this.initialForm)
        },
        watch: {
            initialForm: function(formData) {
                if(_.isEmpty(formData)) this.clearForm()
                this.form = Object.assign({}, formData)
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
