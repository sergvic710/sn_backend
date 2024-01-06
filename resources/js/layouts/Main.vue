<template>
    <n-config-provider :theme-overrides="themeOverrides" :locale="ruRU" :date-locale="dateRuRU">
        <header v-if="show">
        <navbar
            :title = 'title'
        ></navbar>
        </header>
        <main class="main flex justify-center items-center">
            <router-view></router-view>
        </main>
        <footer v-if="show">
            <div class="container">
            <div class="w-100 flex items-center pt-12 pb-12 relative">
                <div class="absolute w-40">
                    Sanext © 2011-2023
                </div>
                <div class="flex justify-center w-full">
                    <img src="/img/sanext1.png" class="h-8">
                </div>
            </div>
            </div>
        </footer>
    </n-config-provider>
</template>

<script>
import navbar from "../components/Navbar.vue";
import {darkTheme, dateRuRU, NConfigProvider, ruRU} from "naive-ui";
import store from "../store"

export default {
    name: "Main",
    components: {navbar, NConfigProvider},
    data() {
        return {
            show: false,
            title: store.state.filter.facilityName,
            themeOverrides: {
                common: {
                    primaryColor: '#000000',
                    fontSize: "15px",
                    fontSizeMedium: "15px",
                },
                DataTable: {
                    sorterSize: '10px',
                },
                Button: {
                    textColor: '#ffffff',
                    colorPrimary: '#2F3E9E',
                    colorHoverPrimary: '#3748B0FF',
                    colorPressedPrimary:  '#3748B0FF',
                    colorFocusPrimary:  '#3748B0FF',
                    colorDisabledPrimary: '#3748B0FF',
                    borderPrimary: '1px solid #3748B0FF',
                    borderHoverPrimary: '1px solid #3748B0FF',
                    borderActivePrimary: '1px solid #3748B0FF',
                },
                Input: {
                    borderHover: '1px solid #efeff5',
                    borderFocus: '1px solid #efeff5',
                    height: '100%',
                    lineHeightTextarea: '1.2'
                },
                Pagination: {
                    itemBorderActive: '1px solid #3748B0FF',
                    itemTextColorHover: '#3748B0FF',
                },
                Select: {
                    // borderHover: '1px solid #efeff5',
                    // borderFocus: '1px solid #efeff5',
                    peers: {
                        InternalSelection: {
                            textColor: '#0F1029'
                        }
                    }
                },
                Tooltip: {
                    color: '#FFFFFF',
                    textColor: '#0F1029'
                }
            },
            darkTheme,
            ruRU,
            dateRuRU
        }
    },
    mounted() {
        this.show = store.state.auth.authenticated
    },
    watch: {
        '$store.state.filter.facilityName': function () {
            this.title = store.state.filter.facilityName
        },
    },
}
</script>

<style scoped>

</style>
