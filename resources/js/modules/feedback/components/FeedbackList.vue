<template>
    <section id="feedback">
        <!-- filters -->
        <el-col :span="24" class="m-t-10">
            <el-form :inline="true" :model="filters" @submit.native.prevent="fetchData" size="mini">

                <el-form-item class="mr-0 float-right">
                    <el-input v-model="filters.search" @input="applySearch" :placeholder="$t('global.search')">
                        <i slot="suffix" class="el-input__icon el-icon-error" v-if="filters.search.length" @click="clearSearch"></i>
                    </el-input>
                </el-form-item>

            </el-form>
        </el-col>

        <!-- table -->
        <el-table
            :data="feedback"
            highlight-current-row
            v-loading="feedbackLoading"
            @sort-change="handleSortChange"
            @filter-change="handleFilterChange"
            class="w-100">
            <el-table-column prop="id" label="Id" width="80"></el-table-column>
            <el-table-column prop="name" label="Name" min-width="200" sortable>
                <template slot-scope="scope">
                    <router-link class="el-link el-link--default ellipsis-form" :to="{name: 'Show Feedback', params: {id: scope.row.id}}">
                        <span class="el-link--inner item_name">{{ scope.row.name }}</span>
                    </router-link>
                </template>
            </el-table-column>
            <el-table-column prop="updated_at" label="Updated" width="200" sortable :sort-orders="sortOrders">
                <template slot-scope="updated_at">
                    {{ GlobalFormatDate(updated_at.row.updated_at) }}
                </template>
            </el-table-column>
            <el-table-column label="Actions" width="130" align="right">
                <template slot-scope="scope">
                    <el-tooltip
                        :open-delay="300"
                        placement="top"
                        :content="$t('global.edit')">
                        <span>
                            <el-button
                                @click="handleEdit(scope.row)"
                                size="mini"
                                icon="el-icon-edit">
                            </el-button>
                        </span>
                    </el-tooltip>
                    <el-tooltip
                        :open-delay="300"
                        placement="top"
                        :content="$t('global.delete')">
                        <span>
                            <el-button
                                @click="handleDelete(scope.row)"
                                type="danger"
                                size="mini"
                                icon="el-icon-delete">
                            </el-button>
                        </span>
                    </el-tooltip>
                </template>
            </el-table-column>
        </el-table>

        <!-- pagination -->
        <el-pagination
            layout="sizes, prev, pager, next"
            :current-page.sync="page"
            :page-size.sync="globalPageSize"
            :total="feedbackMeta.total"
            class="float-right mt-3 mb-3">
        </el-pagination>

        <!-- form dialog -->
        <el-dialog
            :title="formTitle"
            :visible.sync="formVisible"
            close-on-click-modal
            class="feedback-dialog">
            <FeedbackForm :initialForm="formData" @saved="saved" @cancel="formVisible = false" />
        </el-dialog>
    </section>
</template>

<script>

    import {mapActions, mapGetters, mapMutations} from 'vuex'
    import {FEEDBACK_FETCH, FEEDBACK_OBTAIN} from "../store/types";
    import feedbackApi from '../api'
    import FeedbackForm from "./FeedbackForm";

    export default {
        name: "FeedbackList",
        components: {FeedbackForm},
        data: () => ({
            sortBy: 'id,asc',
            sortOrders: ['ascending', 'descending'],
            filters: {
                search: ''
            },
            page: 1,
            formVisible: false,
            formTitle: 'New Feedback',
            formData: null
        }),
        computed: {
            ...mapGetters(['feedback', 'feedbackMeta', 'feedbackLoading']),
        },
        created() {
            this.fetchData()
        },
        watch:{
            page: function () {
                this.fetchData();
            },
            pageSize: function () {
                this.fetchData();
            },
        },
        methods: {
            ...mapActions([FEEDBACK_FETCH]),
            ...mapMutations([FEEDBACK_OBTAIN]),
            handleSortChange(val) {
                if (val.prop != null && val.order != null) {
                    let sort = val.order.startsWith('a') ? 'asc' : 'desc';
                    this.sortBy = val.prop + ',' + sort;
                    this.fetchData();
                }
            },
            handleFilterChange() {
                this.fetchData();
            },
            fetchData() {
                let params = {
                    page: this.page,
                    search: this.filters.search,
                    sortBy: this.sortBy,
                    pageSize: this.globalPageSize
                };
                this[FEEDBACK_FETCH](params)
            },
            handleAdd() {
                this.formTitle = 'New Feedback';
                this.formData = {};
                this.formVisible = true;
            },
            handleEdit(row) {
                this.formTitle = 'Edit Feedback';
                this.formData = Object.assign({}, row);
                this.formVisible = true;
            },
            handleDelete(row) {
                this.$confirm('This will permanently delete the feedback. Continue?', 'Warning', {
                    type: 'warning'
                }).then(() => {
                    feedbackApi.delete(row.id).then((response) => {
                        this.$message({
                            message: response.data.message,
                            type: response.data.type
                        })
                        this.fetchData()
                    })
                })
            },
            applySearch: _.debounce( function() {
                this.fetchData();
            }, 300),
            clearSearch() {
                this.filters.search = '';
                this.fetchData();
            },
            saved() {
                this.formVisible = false
                this.fetchData()
            }
        }
    }
</script>
<style lang="scss" scoped>

</style>
