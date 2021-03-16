<template>
    <div>
        <el-upload
            action="/api/v1/file/upload"
            :on-success="handleSuccess"
            :before-remove="handleRemove"
            :accept="accept">
            <el-button size="small" type="primary">Upload</el-button>
            <el-upload-list v-if="value" :files="value">
            </el-upload-list>
        </el-upload>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Uploader",
    props: {
        label: '',
        value: '',
        fileProperty: '',
        accept: false
    },
    methods: {
        handleSuccess: function (response) {
            let images = [];
            for (let item of response) {
                images.push(item);
            }

            if (images.length === 1) {
                document.getElementById(this.fileProperty + '-upload').setAttribute('value', images[0])
            }
        },
        handleRemove: function (file, filesList) {
            if (file.response.length === 1) {
                document.getElementById(this.fileProperty + '-upload').setAttribute('value', '')
            }

            axios.delete('/file/delete', {
                params: {
                    name: file.response[0]
                }
            }).then()
        }
    }
}
</script>

<style scoped>

</style>
