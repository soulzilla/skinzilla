<template>
    <div class="mb-3">
        <h3>Обратная связь</h3>
        <el-form v-if="$auth.check()">
            <el-form-item prop="content">
                <b-textarea no-resize v-model="form.content" rows="5"></b-textarea>
            </el-form-item>
            <el-button @click="submitForm" :disabled="form.content.length === 0">
                Отправить
            </el-button>
        </el-form>
    </div>
</template>

<script>
import feedbackApi from '../../feedback/api'

export default {
    name: "ContactForm",
    data: () => ({
        form: {
            content: ''
        }
    }),
    methods: {
        submitForm: function () {
            let data = {
                content: this.form.content
            }
            let that = this

            feedbackApi.create(data).then(function () {
                that.$toast.success('Спасибо за обращение, мы обработаем ваше сообщение в ближайшее время.')
                that.form.content = ''
            })
        }
    }
}
</script>

<style scoped>

</style>
