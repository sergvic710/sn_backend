<template>
    <div class="edit-client-btn" @click.prevent="click">
    <n-icon size="14">
        <PencilAlt />
    </n-icon>
        <span>
            изменить
        </span>
    </div>
</template>

<script>
import { PencilAlt} from "@vicons/fa";
import {NIcon} from "naive-ui";

export default {
    name: "EditClientLink",
    components: {PencilAlt, NIcon},
    props: {
        clientId: {type: Number},
        title: {type: String}
    },
    methods: {
        click() {
            this.$root.$emit('showClientEdit', {cliendId: this.clientId, titel: this.title})
        }
    }
}
</script>

<style scoped>

</style>
