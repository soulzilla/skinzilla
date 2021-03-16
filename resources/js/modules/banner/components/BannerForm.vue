<template>
    <section id="banner-form">
        <el-form
            :model="form"
            label-width="120px"
            ref="form"
            :rules="rules"
            size="small"
            @submit.native.prevent="saveSubmit">
            <el-form-item label="Title" prop="title" :error="errors.get('title')">
                <el-input
                    v-model="form.title"
                    @change="errors.clear('title')"
                    suffix-icon="el-icon-edit">
                </el-input>
            </el-form-item>
            <el-form-item label="Content" prop="content" :error="errors.get('content')" style="height: 450px">
                <quill-editor
                    v-model="form.content"
                    style="height: 300px"
                    @change="errors.clear('content')">
                </quill-editor>
            </el-form-item>
            <el-form-item label="Url" prop="url" :error="errors.get('url')">
                <el-input
                    v-model="form.url"
                    @change="errors.clear('url')"
                    suffix-icon="el-icon-edit">
                </el-input>
            </el-form-item>
            <el-form-item label="Image" prop="image">
                <el-upload
                    action="/api/v1/file/upload"
                    v-model="form.image"
                    :before-remove="handleRemove"
                    :on-success="handleSuccess"
                    accept="image/*">
                    <el-button size="small" type="primary">Upload</el-button>
                </el-upload>
                <template v-if="form.image">
                    <el-image :src="form.image"></el-image>
                </template>
            </el-form-item>
            <el-form-item label="Target blank" prop="blank" :error="errors.get('blank')">
                <el-checkbox v-model="form.blank">
                </el-checkbox>
            </el-form-item>
            <el-form-item label="Published" prop="published" :error="errors.get('published')">
                <el-checkbox v-model="form.published">
                </el-checkbox>
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
    import bannerApi from '../api'
    import axios from "axios";

    export default {
        name: "BannerForm",
        props: {
            initialForm: {
                default: () => ({})
            },
        },
        data() {
            return {
                errors: new Errors(),
                formLoading: false,
                formTitle: 'New Banner',
                form: {},
                rules: {
                    title: [
                        {required: true, message: 'banner title is required'},
                    ],
                    content: [
                        {required: true, message: 'banner content is required'},
                    ],
                    url: [
                        {required: true, message: 'banner url is required'},
                    ],
                    blank: [
                    ],
                    image: [
                    ],
                    published: [
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
                        let action = this.form.id ? bannerApi.update : bannerApi.create

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
                this.form.image = response[0]
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
