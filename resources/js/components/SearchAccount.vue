<template>
    <div class="filter-account">
        <div class="filter-account-title">
            Поиск по
            <n-tooltip placement="top-start" trigger="hover">
                <template #trigger>
                    <span class="tooltip">?</span>
                </template>
                Если нужно найти данные по определенной квартире или прибору, то укажите номер
            </n-tooltip>
        </div>
        <div class="filter-account-input">
            <n-select
                v-model:filterOptionVal="filterOptionVal"
                :options="filterOptions"
                @change="handleChangeOption"
            >
            </n-select>
            <n-input
                v-model:filterInputVal="filterInputVal"
                type="text"
                :disabled="filterInputValStatus"
                placeholder="Введите значение"
                @input="handleChange"
            ></n-input>
        </div>

    </div>
</template>

<script>
import Dropdown from "./Dropdown.vue";
import {NSelect} from "naive-ui";
import {NInput, NTooltip} from "naive-ui";

export default {
    name: 'SearchAccount',
    components: {Dropdown, NSelect, NInput, NTooltip},
    data() {
        return {
            filterInputValStatus: true,
            filterOptionVal: '',
            filterInputVal: '',
            filterOptions: [
                {
                    label: "Номер квартиры",
                    value: "flat"
                },
                {
                    label: "Номер прибора",
                    value: "id"
                }, {

                    label: "Номер лицевого счета",
                    value: "personal_number"
                },
                {
                    label: "Код мдентификатор",
                    value: "code"
                },
            ]
        }
    },
    methods: {
        handleChangeOption(value) {
            this.filterOptionVal = value
            this.filterInputValStatus = false
        },
        handleChange (value) {
            if( this.filterOptionVal ) {
                const data = {
                    filterTopOptionVal: this.filterOptionVal,
                    filterTopInputVal: value
                }
                this.$emit('updatedFilter', data)
            }
        }

        // async login() {
        // await axios.get("http://localhost/sanctum/csrf-cookie");

        // await axios.post("http://localhost:8000/login", {
        //     email: "kunal@gmail.com",
        //     password: "password",
        // });

        // let response = await axios.get("http://localhost/api/user");
        // console.log(response.data);
        // },
    }
}
</script>
