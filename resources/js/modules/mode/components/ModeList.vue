<template>
    <section id="mode">
        <!-- filters -->
        <el-col :span="24" class="m-t-10">
            <el-form :inline="true" :model="filters" @submit.native.prevent="fetchData" size="mini">
                <el-form-item>
                    <el-button type="primary" @click="handleAdd" icon="el-icon-plus">{{$t('global.add')}}</el-button>
                </el-form-item>

                <el-form-item class="mr-0 float-right">
                    <el-input v-model="filters.search" @input="applySearch" :placeholder="$t('global.search')">
                        <i slot="suffix" class="el-input__icon el-icon-error" v-if="filters.search.length" @click="clearSearch"></i>
                    </el-input>
                </el-form-item>

            </el-form>
        </el-col>

        <!-- table -->
        <el-table
            :data="modes"
            highlight-current-row
            v-loading="modesLoading"
            @sort-change="handleSortChange"
            @filter-change="handleFilterChange"
            class="w-100">
            <el-table-column prop="id" label="Id" width="80"></el-table-column>
            <el-table-column prop="name" label="Name" min-width="200" sortable>
                <template slot-scope="scope">
                    <router-link class="el-link el-link--default ellipsis-form" :to="{name: 'Show Mode', params: {id: scope.row.id}}">
                        <span class="el-link--inner item_name">{{ scope.row.name }}</span>
                    </router-link>
                </template>
            </el-table-column>
            <el-table-column prop="entity_id" label="Entity Id" width="200" sortable :sort-orders="sortOrders">
                <template slot-scope="entity_id">
                    {{ entity_id.row.entity_id }}
                </template>
            </el-table-column>
            <el-table-column prop="entity_table" label="Entity type" width="200" sortable :sort-orders="sortOrders">
                <template slot-scope="entity_table">
                    {{ entity_table.row.entity_table }}
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
            :total="modesMeta.total"
            class="float-right mt-3 mb-3">
        </el-pagination>

        <!-- form dialog -->
        <el-dialog
            :title="formTitle"
            :visible.sync="formVisible"
            close-on-click-modal
            class="modes-dialog">
            <ModeForm :initialForm="formData" @saved="saved" @cancel="formVisible = false" />
        </el-dialog>
    </section>
</template>

<script>

    import {mapActions, mapGetters, mapMutations} from 'vuex'
    import {MODE_FETCH, MODE_OBTAIN} from "../store/types";
    import modeApi from '../api'
    import ModeForm from "./ModeForm";

    export default {
        name: "ModeList",
        components: {ModeForm},
        data: () => ({
            sortBy: 'id,asc',
            sortOrders: ['ascending', 'descending'],
            filters: {
                search: ''
            },
            page: 1,
            formVisible: false,
            formTitle: 'New Mode',
            formData: null
        }),
        computed: {
            ...mapGetters(['modes', 'modesMeta', 'modesLoading']),
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
            ...mapActions([MODE_FETCH]),
            ...mapMutations([MODE_OBTAIN]),
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
                this[MODE_FETCH](params)
            },
            handleAdd() {
                this.formTitle = 'New Mode';
                this.formData = {};
                this.formVisible = true;
            },
            handleEdit(row) {
                this.formTitle = 'Edit Mode';
                this.formData = Object.assign({}, row);
                this.formVisible = true;
            },
            handleDelete(row) {
                this.$confirm('This will permanently delete the mode. Continue?', 'Warning', {
                    type: 'warning'
                }).then(() => {
                    modeApi.delete(row.id).then((response) => {
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
