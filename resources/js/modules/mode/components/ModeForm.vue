<template>
    <section id="mode-form">
        <el-form
            :model="form"
            label-width="120px"
            ref="form"
            :rules="rules"
            size="small"
            @submit.native.prevent="saveSubmit">
            <el-form-item label="Mode Name" prop="name" :error="errors.get('name')">
                <el-input
                    v-model="form.name"
                    @change="errors.clear('name')"
                    suffix-icon="el-icon-edit">
                </el-input>
            </el-form-item>
            <el-form-item label="Description" prop="description" :error="errors.get('description')">
                <el-input
                    type="textarea"
                    rows="5"
                    v-model="form.description"
                    @change="errors.clear('description')"
                    suffix-icon="el-icon-edit">
                </el-input>
            </el-form-item>
            <el-form-item label="Entity type" prop="entity_table" :error="errors.get('entity_table')">
                <el-select
                    v-model="form.entity_table"
                    @change="handleEntityTypeChange"
                    suffix-icon="el-icon-edit">
                    <el-option value="gamblings" label="Gambling">
                        Gambling
                    </el-option>
                    <el-option value="roulettes" label="Roulette">
                        Roulette
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Entity id" prop="entity_id" :error="errors.get('entity_id')">
                <el-select
                    v-model="form.entity_id"
                    @change="handleEntityTypeChange"
                    suffix-icon="el-icon-edit">
                    <el-option v-for="item of entityList" v-bind:key="item.id" :value="item.id" :label="item.name">
                        {{ item.name }}
                    </el-option>
                </el-select>
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
    import modeApi from '../api'
    import rouletteApi from '../../roulette/api'
    import gamblingApi from '../../gambling/api'

    export default {
        name: "ModeForm",
        props: {
            initialForm: {
                default: () => ({})
            },
        },
        data() {
            return {
                entityList: [],
                rouletteList: [],
                gamblingList: [],
                errors: new Errors(),
                formLoading: false,
                formTitle: 'New Mode',
                form: {},
                rules: {
                    name: [
                        {required: true, message: 'mode name is required'},
                    ],
                    description: [
                        {required: true, message: 'mode name is required'},
                    ],
                    entity_id: [
                        {required: true, message: 'entity id is required'},
                    ],
                    entity_table: [
                        {required: true, message: 'entity type is required'},
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
                        let action = this.form.id ? modeApi.update : modeApi.create

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
            handleEntityTypeChange: function (value) {
                if (value === 'gamblings') {
                    this.entityList = this.gamblingList
                } else {
                    this.entityList = this.rouletteList
                }
                this.errors.clear('entity_table');
            }
        },
        mounted() {
            this.form = Object.assign({}, this.initialForm)
            rouletteApi.all().then(response => (this.rouletteList = response.data.data))
            gamblingApi.all().then(response => (this.gamblingList = response.data.data))
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
