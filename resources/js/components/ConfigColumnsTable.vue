<template>
    <n-modal
        v-model:show="show"
        class="custom-card"
        preset="card"
        style="width: 580px;"
        :title="title"
        :bordered="false"
        size="small"
        :segmented="segmented"
        :on-after-leave="onClose"
    >
        <!--        <template #header-extra>-->
        <!--            {{ title }}-->
        <!--        </template>-->
        <div>
            <!--            <n-checkbox-group v-model:value="setColumns">-->

            <n-checkbox-group v-model:value="setColumns">
                <n-space vertical>
                    <n-checkbox v-for="col in columns" :value="col.key" :label="col.title"/>
                </n-space>
            </n-checkbox-group>

            <!--            </n-checkbox-group>-->
            <div class="form-group form-group__button">
                <n-button type="primary" @click="saveConfig">
                    Сохранить
                </n-button>
            </div>
        </div>
        <!--        <template #footer>-->
        <!--            Footer-->
        <!--        </template>-->
    </n-modal>
</template>

<script>
import {NModal} from "naive-ui";
import store from "../store";
import {NButton, NCheckbox, NCheckboxGroup, NSpace} from "naive-ui";

export default {
    name: "ConfigColumnsTable",
    components: {NModal, NButton, NCheckbox, NCheckboxGroup, NSpace},
    props: {
        columns: {type: Array},
        // show: {type: Boolean, default: false}
    },
    data() {
        return {
            setColumns: [],
            showModal: false,
            show: false,
            title: 'Столбцы',
            name: '',
        }
    },
    mounted() {
    },
    computed: {
        // modal() {
        //     return store.state.cards.modal;
        // }
    },
    watch: {
        showModal: function ( value ) {
            let aa = 10
        }
        // '$store.state.cards.modal': function () {
        //     // if (store.state.cards.modal === true) {
        //     this.showModal = store.state.cards.modal
        //     this.title = store.state.cards.title
        //     // }
        // }
    },
    methods: {
        onClose() {
           let aa=10
        },
        saveConfig() {
            this.show = false
            // let setColumns = this.setColumns
            // let newColumns = this.columns.filter(function (tplItem) {
            //     return setColumns.find(function (item) {
            //         return tplItem.key === item
            //     })
            // })
            // store.dispatch('device/saveColumns', newColumns)
        }
    }
}
</script>

<style scoped>

</style>
