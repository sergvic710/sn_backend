<template>
    <div class="table-wrapper">
        <div class="list-filter-wrapper">
            <div class="list-filter-group">
                <div class="list-filter-group-title">
                    Дом
                </div>
                <div class="list-filter-group-input">
                    <n-select
                        v-model:value="facilityVal"
                        :options="facilities"
                        @change="changeFacility"
                    ></n-select>
                </div>
            </div>
            <div class="list-filter-group list-filter-group__button">
                <n-button type="primary" @click="exportFile" :disabled ='disabledExportBtn'>
                    {{ exportBtnText }}
                </n-button>
            </div>
        </div>
        <div class="table-list card-list">
            <n-data-table
                remote
                ref="table"
                :columns="columns"
                :data="items"
                :loading="loading"
                :pagination="pagination"
            />
        </div>
        <div class="cfg-columns">
            <n-icon size="20" @click="cfgColumns()">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 20 20">
                    <g fill="none">
                        <path
                            d="M1.91 7.383a8.491 8.491 0 0 1 1.78-3.08a.5.5 0 0 1 .54-.135l1.918.686a1 1 0 0 0 1.32-.762l.366-2.006a.5.5 0 0 1 .388-.4a8.532 8.532 0 0 1 3.555 0a.5.5 0 0 1 .387.4l.367 2.006a1 1 0 0 0 1.32.762l1.918-.686a.5.5 0 0 1 .54.136a8.491 8.491 0 0 1 1.78 3.079a.5.5 0 0 1-.152.535l-1.555 1.32a1 1 0 0 0 0 1.524l1.555 1.32a.5.5 0 0 1 .152.535a8.491 8.491 0 0 1-1.78 3.08a.5.5 0 0 1-.54.135l-1.918-.686a1 1 0 0 0-1.32.762l-.367 2.007a.5.5 0 0 1-.387.399a8.53 8.53 0 0 1-3.555 0a.5.5 0 0 1-.388-.4l-.365-2.006a1 1 0 0 0-1.32-.762l-1.919.686a.5.5 0 0 1-.54-.136a8.49 8.49 0 0 1-1.78-3.079a.5.5 0 0 1 .153-.535l1.554-1.32a1 1 0 0 0 0-1.524l-1.554-1.32a.5.5 0 0 1-.153-.535zM8 10a2 2 0 1 0 4 0a2 2 0 0 0-4 0z"
                            fill="currentColor"></path>
                    </g>
                </svg>
            </n-icon>
        </div>
        <client-edit-dialog
        ></client-edit-dialog>
        <!--            @update:sorter="handleSorterChange"-->
        <!--            @update:filters="handleFiltersChange"-->
        <!--            @update:page="handlePageChange"-->


    </div>
    <n-modal
        v-model:show="showConfigColumns"
        class="custom-card"
        preset="card"
        style="width: 580px;"
        :bordered="false"
        size="small"
    >
        <div>
            <n-checkbox-group v-model:value="configColumns">
                <n-space vertical>
                    <n-checkbox v-for="col in tplColumns" :value="col.key" :label="col.title"/>
                </n-space>
            </n-checkbox-group>

            <div class="form-group form-group__button">
                <n-button type="primary" @click="saveConfig">
                    Сохранить
                </n-button>
            </div>
        </div>
    </n-modal>
</template>

<script>
import {h} from "vue";
import Dropdown from "./Dropdown.vue";
import 'flatpickr/dist/flatpickr.css'
import {NDataTable, NButton} from "naive-ui";
import {NSelect} from "naive-ui";
import EditClientLink from "./EditClientLink.vue";
import ClientEditDialog from "./ClientEditDialog.vue";
import store from "../store";
import {NModal} from "naive-ui";
import {NCheckbox, NCheckboxGroup, NSpace, NIcon} from "naive-ui";
import { PencilAlt} from "@vicons/fa";

const createColumns = ({showModal}) => {
    return [
        {
            title: 'id',
            key: "id",
            sorter: true,
            className: '_hide'
        },
        {
            title: 'Действие',
            key: 'actions',
            render(row) {
                return h(
                    EditClientLink,
                    {
                        onclick: () => showModal(row)
                    }
                )
            }
        }
    ]
}

export default {
    name: "CardList",
    components: {Dropdown, NDataTable, NSelect, NButton, EditClientLink, ClientEditDialog, NModal, NCheckbox, NCheckboxGroup,NSpace, NIcon,PencilAlt},
    props: {
        items: {type: Array, required: true},
        page: {type: Number},
        pageSize: {type: Number},
        total: {type: Number},
        pageCount: {type: Number},
        facilities: {type: Array},
        propFacilityVal: {type: Number}
    },
    data() {
        return {
            loading: false,
            configColumns: [],
            showConfigColumns: false,
            disabledExportBtn: false,
            exportBtnText: 'Скачать',
            showEditDialog: false,
            facilityVal: null,
            params: {},
            pagination: {
                page: this.page,
                pageCount: this.pageCount,
                pageSize: 25,
                showSizePicker: true,
                itemCount: this.total,
                pageSizes: [
                    {
                        label: '25',
                        value: 25
                    },
                    {
                        label: '50',
                        value: 50
                    },
                    {
                        label: '100',
                        value: 100
                    },
                    {
                        label: 'все',
                        value: -1
                    },
                ],
                onChange: (page) => {
                    const data = {
                        page: page,
                        pageSize: this.pagination.pageSize
                    }
                    this.$emit('pageUpdate', data)
                    // this.updateList()
                    this.pagination.page = page
                },
                onUpdatePageSize: (pageSize) => {
                    const data = {
                        page: this.pagination.page,
                        pageSize: pageSize
                    }
                    this.$emit('pageUpdate', data)
                    // this.updateList()
                    this.pagination.pageSize = pageSize

                    // paginationReactive.page = 1
                },
                prefix({itemCount}) {
                    return `Всего ${itemCount}.`
                }
            },
            tplColumns: [

                {
                    title: "№ кв",
                    key: "flat",
                },
                {
                    title: "Адрес",
                    key: "address"
                },
                {
                    title: "ФИО",
                    key: "name"
                },
                {
                    title: "Дата поверки",
                    key: "datecheck",
                    className: 'data-table-cell-number data-table-cell-date-check '
                },
                {
                    title: "Дата вступления в собственность",
                    key: "dateown",
                    className: 'data-table-cell-number data-table-cell-date-own'
                },
                {
                    title: "Комментарий",
                    key: "comment",
                    className: 'data-table-cell-comment'
                },
            ],
            columns: [],
            columns_: createColumns({ showModal(row) {
                // this.showEditDialog = true
                    store.commit('cards/SHOW_MODAL')
                    store.commit('cards/TITLE',row.address)
                    store.commit('cards/CLIENTID',row.id)
                    store.commit('cards/NAME',row.name)
                    store.commit('cards/DATECHECK',row.datecheck)
                    store.commit('cards/DATEOWN',row.dateown)
                    store.commit('cards/COMMENT',row.comment)
            }}),
            data: []
        }
    },
    mounted() {
        store.commit('cards/HIDE_MODAL')
        store.dispatch('cards/getColumns')
        this.setColumns()
    },
    watch: {
        propFacilityVal: function () {
            this.facilityVal = this.propFacilityVal
        },
        items: function () {
            this.pagination.pageCount = this.pageCount
            this.pagination.itemCount = this.total
        },
        '$store.state.all.loading': function () {
            this.loading = store.state.all.loading
        }
    },
    methods: {
        async exportFile() {
            this.disabledExportBtn = true
            this.exportBtnText = 'Генерация'
            const filter = store.state.filter.filter
            await this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post(`/api/export-cards`, filter)
                    .then(response => {
                        this.disabledExportBtn = false
                        this.exportBtnText = 'Скачать'
                        window.location.href = response.data.file;
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        changeFacility(val) {
            this.facilityVal = val
            this.$emit('updatedFilter', this.makeData())
            this.pagination.page = 1
        },
        makeData() {
            return {
                facilityVal: this.facilityVal,
            }
        },
        cfgColumns() {
            this.showConfigColumns = true
        },
        setColumns() {
            let storeColumns =  store.state.cards.columns
            if( storeColumns.length === 0 ) {
                this.columns = [].concat(this.tplColumns, this.columns_ )
            } else {
                this.columns = [].concat(storeColumns, this.columns_)
            }
            this.configColumns =   this.columns.map( item => item.key )
        },
        saveConfig() {
            this.showConfigColumns = false
            let configColumns = this.configColumns
            let columns_ = this.tplColumns.filter(function (tplItem) {
                return configColumns.find(function (item) {
                    return tplItem.key === item
                })
            })
            if( columns_.length === 0 ) {
                columns_ = this.tplColumns
            }
            this.columns = [].concat(columns_, this.columns_ )
            store.dispatch('cards/saveColumns', columns_)
        }
    }
}
</script>

<style scoped>

</style>
