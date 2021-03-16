<template>
    <section id="comment">
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
            :data="comments"
            highlight-current-row
            v-loading="commentsLoading"
            @sort-change="handleSortChange"
            @filter-change="handleFilterChange"
            class="w-100">
            <el-table-column prop="id" label="Id" width="80"></el-table-column>
            <el-table-column prop="content" label="Content" min-width="200" sortable>
                <template slot-scope="scope">
                    <router-link class="el-link el-link--default ellipsis-form" :to="{name: 'Show Comment', params: {id: scope.row.id}}">
                        <span class="el-link--inner item_name">{{ scope.row.content }}</span>
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
            :total="commentsMeta.total"
            class="float-right mt-3 mb-3">
        </el-pagination>

        <!-- form dialog -->
        <el-dialog
            :title="formTitle"
            :visible.sync="formVisible"
            close-on-click-modal
            class="comments-dialog">
            <CommentForm :initialForm="formData" @saved="saved" @cancel="formVisible = false" />
        </el-dialog>
    </section>
</template>

<script>

    import {mapActions, mapGetters, mapMutations} from 'vuex'
    import {COMMENT_FETCH, COMMENT_OBTAIN} from "../store/types";
    import commentApi from '../api'
    import CommentForm from "./CommentForm";

    export default {
        name: "CommentList",
        components: {CommentForm},
        data: () => ({
            sortBy: 'id,asc',
            sortOrders: ['ascending', 'descending'],
            filters: {
                search: ''
            },
            page: 1,
            formVisible: false,
            formTitle: 'New Comment',
            formData: null
        }),
        computed: {
            ...mapGetters(['comments', 'commentsMeta', 'commentsLoading']),
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
            ...mapActions([COMMENT_FETCH]),
            ...mapMutations([COMMENT_OBTAIN]),
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
                this[COMMENT_FETCH](params)
            },
            handleAdd() {
                this.formTitle = 'New Comment';
                this.formData = {};
                this.formVisible = true;
            },
            handleEdit(row) {
                this.formTitle = 'Edit Comment';
                this.formData = Object.assign({}, row);
                this.formVisible = true;
            },
            handleDelete(row) {
                this.$confirm('This will permanently delete the comment. Continue?', 'Warning', {
                    type: 'warning'
                }).then(() => {
                    commentApi.delete(row.id).then((response) => {
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
