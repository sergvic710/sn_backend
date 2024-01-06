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
            <div class="list-filter-group">
                <div class="list-filter-group-title">
                    Период
                </div>
                <div class="list-filter-group-input">
                    <flat-pickr
                        ref="calendar"
                        v-model="dateRange"
                        class="select__field-text"
                        :config="configDate"
                        name="date"
                        @onChange="changeDate"
                    />
                </div>
            </div>
            <div class="list-filter-group">
                <div class="list-filter-group-title">
                    Прибор учета
                </div>
                <div class="list-filter-group-input">
                    <n-select
                        v-model:value="deviceTypeVal"
                        :options="deviceTypes"
                        @change="changeDeviceType"
                    ></n-select>
                </div>
            </div>
            <div class="list-filter-group list-filter-group__radio">
                <div class="list-filter-group-title">
                    Тип
                </div>
                <div class="list-filter-group-input">
                    <n-radio
                        :checked="devicePlaceVal === '3'"
                        value="3"
                        name="devicePlace"
                        @change="changeDevicePlace"
                    >
                        Все
                    </n-radio>
                    <n-radio
                        :checked="devicePlaceVal === '1'"
                        value="1"
                        name="devicePlace"
                        @change="changeDevicePlace"
                    >
                        Квартирные
                    </n-radio>
                    <n-radio
                        :checked="devicePlaceVal === '2'"
                        value="2"
                        name="devicePlace"
                        @change="changeDevicePlace"
                    >
                        Общедомовые
                    </n-radio>
                </div>
            </div>
            <div class="list-filter-group list-filter-group__button">
                <!--                <button value="Скачать" @click="exportFile">Скачать</button>-->
                <n-button type="primary" @click="exportFile" :disabled='disabledExportBtn'>
                    {{ exportBtnText }}
                </n-button>
            </div>
            <!--            <n-button type="primary" @click="cfgColumns()">-->
            <!--                Настройка-->
            <!--            </n-button>-->
        </div>
        <div class="table-list">
            <n-data-table
                remote
                ref="table"
                :columns="columns"
                :data="items"
                :loading="loading"
                :pagination="pagination"
                @update:sorter="sorterChange"
            />
        </div>

        <!--            @update:sorter="handleSorterChange"-->
        <!--            @update:filters="handleFiltersChange"-->
        <!--            @update:page="handlePageChange"-->

        <!--        <config-columns-table :columns="tplColumns" :show="showConfigColumns"></config-columns-table>-->
        <!--        <config-columns-table :columns="tplColumns" :showModal="showConfigColumns"></config-columns-table>-->
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
    </div>

    <n-modal
        v-model:show="showConfigColumns"
        class="custom-card"
        preset="card"
        style="width: 580px;"
        :title="title"
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
import flatPickr from 'vue-flatpickr-component'
import Dropdown from "./Dropdown.vue";
import {Russian} from 'flatpickr/dist/l10n/ru.js'
import 'flatpickr/dist/flatpickr.css'
import {NDataTable} from "naive-ui";
import {NSelect, NRadio, NButton} from "naive-ui";
import moment from 'moment';
import store from "../store";
import {NModal} from "naive-ui";
import {NCheckbox, NCheckboxGroup, NSpace, NIcon} from "naive-ui";
import {PencilAlt} from "@vicons/fa";
import {getElementFromSelector} from "bootstrap/js/src/util";


export default {
    name: "DeviceTableList",
    components: {
        Dropdown,
        flatPickr,
        NDataTable,
        NSelect,
        NRadio,
        NButton,
        NModal,
        NCheckbox,
        NCheckboxGroup,
        NSpace,
        NIcon
    },
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
            sorter: false,
            devicePlaceVal: 3,
            showConfigColumns: false,
            disabledExportBtn: false,
            exportBtnText: 'Скачать',
            // facilities: this.facilities,
            // devicePlaceVal: '1',
            deviceTypes: [
                {
                    label: "Электричество",
                    value: "1",
                    name: 'electricity'
                },
                {
                    label: "Тепло",
                    value: "2",
                    name: 'hot'
                },
                {
                    label: "Вода",
                    value: "3",
                    name: 'water'
                }
            ],
            facilityVal: null,
            // devicePlace:
            loading: false,
            // inputId: 'dp-' + this._uid,
            dateRange: '',
            configDate: {
                mode: "single",
                locale: Russian,
                static: true,
                disableMobile: 'true',
                dateFormat: 'd-m-Y',
            },
            // config: {},
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
                    }, {
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
                },
                // prev() {
                //     return '1111'
                // },
                // next() {
                //     return '222'
                // }

            },
            rowProps: (row) => {
                return {
                    style: "cursor: pointer;",
                    // onClick: () => {
                    //     message.info(row.name);
                    // }
                };
            },
            // Столбцы, которые были выбраны при конфигурации
            configColumns: [],
            // Массив конфигурация для всех столбцов
            configAllColumns: {},
            // Текущие столбцы
            columns: [],
            // Текущий шаблон столбцов
            tplColumns: [],
            // Все столбцы которые возможны для счетчиков
            tplColumnsWaterHot: [
                {
                    title: "№ кв",
                    key: "flat",
                    sorter: 'default',
                },
                {
                    title: "Адрес",
                    key: "address"
                },
                {
                    title: "ФИО",
                    key: "name",
                    sorter: true
                },
                {
                    title: "Номер лицевого счета",
                    key: "personal_number",
                    className: 'data-table-cell-number',
                    sorter: true
                },
                {
                    title: "Номер прибора",
                    key: "number",
                    className: 'data-table-cell-number',
                    sorter: true
                },
                {
                    title: "Код идетификатора",
                    key: "code",
                    className: 'data-table-cell-number',
                    sorter: true
                },
                {
                    title: "Текущие показания",
                    key: "data",
                    className: 'data-table-cell-number',
                    sorter: true
                }
            ],
            tplColumnsElectricity: [
                {
                    title: "№ кв",
                    key: "flat",
                    sorterOrder: 'ascend',
                    sorter: 'default',
                },
                {
                    title: "Адрес",
                    key: "address"
                },
                {
                    title: "ФИО",
                    key: "name",
                    sorter: false
                },
                {
                    title: "Номер лицевого счета",
                    key: "personal_number",
                    className: 'data-table-cell-number',
                    sorter: true
                },
                {
                    title: "Номер прибора",
                    key: "number",
                    className: 'data-table-cell-number',
                    sorter: true
                },
                {
                    title: "Код идетификатора",
                    key: "code",
                    className: 'data-table-cell-number',
                    sorter: true
                },
                {
                    title: "Текущие показания",
                    key: "data",
                    className: 'data-table-cell-number',
                    sorter: true
                },
                {
                    title: "Тариф",
                    key: "counter",
                    className: 'data-table-cell-number',
                    sorter: true
                }
            ],
            // tplColumns_: {}

            // data: this.items
        }
    },
    mounted() {
        store.dispatch('device/getColumns')
        this.deviceTypeVal = '1'
        this.devicePlaceVal = '3'
        // this.facilityVal = this.propFacilityVal
        this.setTemplate()
        this.changePagi()
        this.setColumns()
        this.configDate.enable = store.state.device.availableDates

        let context = this;
        if (!store.state.device.availableDates.length) {
            setTimeout(function () {
                context.configDate.enable = store.state.device.availableDates
            }, 1000)
        }
    },
    computed: {
        showColumns() {
            return store.state.device.columns
        }
    },
    watch: {
        propFacilityVal: function () {
            this.facilityVal = this.propFacilityVal
        },
        showColumns: function () {
            this.showConfigColumns = false
            this.setColumns()
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
        dateDisabled(ts) {
            return false;
        },
        async exportFile() {
            this.disabledExportBtn = true
            this.exportBtnText = 'Генерация'
            const filter = store.state.filter.filter
            await this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post(`/api/export-devices`, filter)
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
        moment() {
            return moment
        },
        changeDate(selectedDates, dateStr, instance) {
            if (selectedDates.length == 1) {
                this.$emit('updatedFilter', this.makeData())
                this.pagination.page = 1
            }
        },
        changeDevicePlace(e) {
            this.devicePlaceVal = e.target.value;
            this.$emit('updatedFilter', this.makeData())
            this.pagination.page = 1
        },
        changeFacility(val) {
            this.facilityVal = val
            this.$emit('updatedFilter', this.makeData())
            this.pagination.page = 1
        },
        changeDeviceType(val) {
            this.deviceTypeVal = val
            this.setTemplate()
            // this.columns = this.tplColumns
            this.setColumns()
            this.$emit('updatedFilter', this.makeData())
            this.pagination.page = 1
        },
        sorterChange(sorter) {
            this.sorter = sorter
            this.columns.forEach((column) => {
                /** column.sortOrder !== undefined means it is uncontrolled */
                if (column.sortOrder === undefined) return
                if (!sorter) {
                    column.sortOrder = false
                    return
                }
                if (column.key === sorter.columnKey) {
                    column.sortOrder = sorter.order
                }
                else {
                    column.sortOrder = false
                }
            })
            this.$emit('updatedFilter', this.makeData())
        },
        makeData() {
            let data ={
                facilityVal: this.facilityVal,
                dateRange: this.dateRange,
                deviceTypeVal: this.deviceTypeVal,
                devicePlaceVal: this.devicePlaceVal
            }
            if( this.sorter ) {
                if( this.sorter.order === false ) {
                    data.sorterorder = ''
                } else {
                    data.sorterorder = this.sorter.order
                }
                data.sortercolumnkey = this.sorter.columnKey
            }
            return data
        },
        changePagi() {
            // const pagination = document.querySelector('n-pagination')
            // const para = document.createElement("div");
            // para.className = 'cla'
// pagination.appendChild(para)
        },
        cfgColumns() {
            this.showConfigColumns = true
        },
        setColumns() {
            const config = store.state.device.columns
            const deviceTypeName = this.getNameSelectedDevice()
            const storeColumns = config[deviceTypeName]
            this.columns = []
            if (storeColumns && storeColumns.length > 0) {
                this.columns = this.tplColumns.filter(function (tplItem) {
                    return storeColumns.find(function (item) {
                        return tplItem.key === item
                    })
                })
            }
            if (this.columns.length === 0) {
                this.columns = this.tplColumns
            }
            this.configColumns = this.columns.map(item => item.key)
        },
        saveConfig() {
            this.showConfigColumns = false
            let configColumns = this.configColumns

            const deviceName = this.getNameSelectedDevice()
            this.configAllColumns[deviceName] = configColumns
            store.dispatch('device/saveColumns', this.configAllColumns)
            this.setColumns()
        },
        setTemplate() {
            if (this.deviceTypeVal === '1') {
                this.tplColumns = this.tplColumnsElectricity
            } else {
                this.tplColumns = this.tplColumnsWaterHot
            }
        },
        getNameSelectedDevice() {
            const deviceTypeVal = this.deviceTypeVal
            const device = this.deviceTypes.find((item) => item.value === deviceTypeVal)
            return device.name
        }
    }
}

</script>

<style scoped>

</style>
