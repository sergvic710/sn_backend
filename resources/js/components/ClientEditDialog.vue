<template>
    <n-modal
        v-model:show="showModal"
        class="custom-card"
        preset="card"
        style="width: 580px;"
        :title="title"
        :bordered="false"
        size="small"
        :on-after-leave="onClose"
    >
        <!--        <template #header-extra>-->
        <!--            {{ title }}-->
        <!--        </template>-->
        <div class="form-wrapper">
            <div class="form-group" style="width: 100%;">
                <div class="form-group-title">
                    ФИО
                </div>
                <div class="form-group-input">
                    <n-input
                        v-model:value="name"
                        type="text"
                        placeholder="ФИО"
                    ></n-input>
                </div>
            </div>
        </div>
        <div class="form-wrapper">
            <div class="form-group">
                <div class="form-group-title">
                    Дата проверки
                </div>
                <div class="form-group-input">
                    <flat-pickr
                        ref="calendar"
                        v-model="date_check"
                        class="select__field-text"
                        mode="single"
                        :config="configDate"
                        name="date"
                    />
                </div>
            </div>
            <div class="form-group">
                <div class="form-group-title">
                    Дата вступления <br>в собственность
                </div>
                <div class="form-group-input">
                    <flat-pickr
                        ref="calendar"
                        v-model="date_own"
                        class="select__field-text"
                        mode="single"
                        :config="configDate"
                        name="date"
                    />
                </div>
            </div>
        </div>
        <div class="form-wrapper">
            <div class="form-group" style="width: 100%; height: 85px;">
                <div class="form-group-title">
                    Комментарий
                </div>
                <div class="form-group-input">
                    <n-input
                        v-model:value="comment"
                        type="textarea"
                        rows="4"
                        placeholder="Комментарий"
                    ></n-input>
                </div>
            </div>
        </div>
        <div class="form-wrapper">
            <div class="form-group form-group__button">
                <n-button type="primary" @click="onClick">
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
import {NButton, NInput} from "naive-ui";
import flatPickr from 'vue-flatpickr-component'
import {Russian} from 'flatpickr/dist/l10n/ru.js'
import 'flatpickr/dist/flatpickr.css'

export default {
    name: "ClientEditDialog",
    components: {NModal, NButton, NInput, flatPickr, Russian},
    props: {
        // show: {type: Boolean, default: true },
        // title: {type: String}
    },
    data() {
        return {
            title: '',
            comment: '',
            showModal: false,
            name: store.state.cards.name,
            date_check: store.state.cards.dateCheck,
            date_own: store.state.cards.dateOwn,
            configDate: {
                mode: "single",
                locale: Russian,
                static: true,
                disableMobile: 'true',
                dateFormat: 'd.m.Y'
            },
        }
    },
    computed: {
        modal() {
            return store.state.cards.modal;
        }
    },
    watch: {
        // modal (val, val1) {
        //     this.showModal = true
        // }
        '$store.state.cards.modal': function () {
            // if (store.state.cards.modal === true) {
                this.showModal = store.state.cards.modal
                this.title = store.state.cards.title
            this.name = store.state.cards.name
            this.date_check = store.state.cards.dateCheck
            this.date_own = store.state.cards.dateOwn
            this.comment = store.state.cards.comment

            // }
        }
    },
    methods: {
        onClose() {
            store.commit('cards/HIDE_MODAL')
        },
        async onClick() {
            await this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post(`/api/saveclient`, {
                    id:  store.state.cards.clientId,
                    name: this.name,
                    datecheck: this.date_check,
                    dateown: this.date_own,
                    comment: this.comment
                })
                    .then(response => {
                        store.commit('cards/HIDE_MODAL')
                        store.commit('cards/UPDATE_LIST', true)
                        this.name = ''
                        this.date_check = ''
                        this.date_own = ''
                        this.comment = ''
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    }
}
</script>

<style scoped>

</style>
