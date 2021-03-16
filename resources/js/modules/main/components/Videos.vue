<template>
    <div>
        <template v-for="video of videos">
            <b-embed
                class="mb-3"
                :src="embedUrl(video.url)">
            </b-embed>
        </template>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Videos",
    data: () => ({
        videos: [],
    }),
    methods: {
        embedUrl: function (url) {
            let ID = '';
            url = url.replace(/(>|<)/gi,'').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
            if(url[2] !== undefined) {
                ID = url[2].split(/[^0-9a-z_\-]/i);
                ID = ID[0];
            }
            else {
                ID = url;
            }

            return '//www.youtube.com/embed/' + ID;
        }
    },
    mounted() {
        axios.get('/video').then(response => (this.videos = response.data.data))
    }
}
</script>

<style scoped>

</style>
