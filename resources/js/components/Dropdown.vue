<template>
    <div class="dropdown-select">
        <v-select :options="items" label="title" placeholder="Номеру лицевого счета"></v-select>
    </div>
</template>

<script>
// import Select2 from 'vue3-select2-component';
// import {VueSelect} from "vue-select";
import vSelect from 'vue-select'

let required;
let Required;
export default {
    components: {vSelect},
    name: "Dropdown",
    params: {
        label: {default: '', Array},
        tooltip: {default: false, Boolean},
        items: {default: [], Required}
    },
    data() {
        return {
           items: this.items
        };
    }
}
</script>

<style>
@import "vue-select/dist/vue-select.css";
</style>
