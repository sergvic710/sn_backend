<template>
    <component :is="layout">
        <router-view></router-view>
    </component>

</template>
<script>
import navbar from "./components/Navbar.vue";
import {NConfigProvider} from 'naive-ui'
import { darkTheme } from 'naive-ui'
import {ruRU, dateRuRU} from "naive-ui";
import Login from "./layouts/Login.vue";
import Main from "./layouts/Main.vue";

export default {
    name: "App",
    components: {Login, Main },

    computed: {
        layout() {
            return (this.$route.meta.layout || 'Main')
        }
    },

}
</script>
