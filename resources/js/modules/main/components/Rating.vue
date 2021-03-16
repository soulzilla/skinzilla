<template>
    <div>
        <b-form-rating
            v-model="rate"
            variant="warning"
            size="lg"
            @change="handleRate"
            show-value
            no-border></b-form-rating>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Rating",
    props: {
        item: {},
        entity_table: ''
    },
    data: () => ({
        rate: 0
    }),
    methods: {
        handleRate: function (value) {
            var that = this
            let data = {
                entity_id: this.item.id,
                entity_table: this.entity_table,
                rate: value
            };
            console.log(this.item)
            axios.post('/rating', data).then(function (response) {
                that.$toast.success('Спасибо за оценку!')
            })
        }
    },
    mounted() {
        this.rate = this.item.rating ? this.item.rating.rate : 0;
    }
}
</script>

<style scoped>

</style>
