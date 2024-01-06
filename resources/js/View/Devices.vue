<template>
    <section class="section">
        <div class="container">
            <search-account
                @updatedFilter="updateTopFilter"
            ></search-account>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <device-list
                :propFacilityVal="facilityVal"
                :items="data"
                :page="page"
                :pageSize="pageSize"
                :pageCount="pageCount"
                :total="total"
                :facilities="facilities"
                @updatedFilter="updateFilter"
                @pageUpdate='pageUpdate'
            ></device-list>
        </div>
    </section>
</template>

<script>
import SearchAccount from "../components/SearchAccount.vue";
import DeviceList from "../components/DeviceList.vue";
import store from "../store";

export default {
    name: "Devices",
    components: {SearchAccount, DeviceList},

    data() {
        return {
            data: [],
            page: 1,
            pageSize: 25,
            pageCount: 0,
            total: 1,
            facilities: [],
            facilityVal: null,
            facilityName: '',
            filter: {
                filteroptionval: null,
                filterinputval: null,
                facilityval: null,
                daterange: null,
                devicetypeval: 1,
                deviceplace: 3,
                pagesize: 25,
                page: 1,
            }
        }
    },
    mounted() {
        // const filter.js = {
        //     page: this.page,
        //     pagesize: this.pageSize
        // }
        store.commit('filter/SET_FILTER', this.filter)
        this.filter.facilityval = store.state.filter.facilityVal
        this.updateList()
    },
    methods: {
        pageUpdate(data) {
            this.filter.page = data.page
            this.filter.pagesize = data.pageSize
            store.commit('filter/SET_FILTER', this.filter)
            this.updateList()
        },
        updateFilter(data) {
            this.filter.page = 1
            this.filter.facilityval = data.facilityVal
            this.filter.daterange = data.dateRange
            this.filter.devicetypeval = data.deviceTypeVal
            this.filter.deviceplaceval = data.devicePlaceVal
            if( data.sortercolumnkey ) {
                this.filter.sortercolumnkey = data.sortercolumnkey
                this.filter.sorterorder = data.sorterorder
            }
            store.commit('filter/SET_FILTER', this.filter)
            this.updateList()
        },
        updateTopFilter(data) {
            this.filter.page = 1
            this.filter.filteroptionval = data.filterTopOptionVal
            this.filter.filterinputval = data.filterTopInputVal
            this.filter.pagesize = this.pageSize
            store.commit('filter/SET_FILTER', this.filter)
            this.updateList()
        },
        async updateList() {
            store.commit('all/SET_LOADING', true)
            await this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post(`/api/devices`, this.filter)
                    .then(response => {
                        this.data = response.data.data
                        this.pageCount = response.data.lastPage
                        this.total = response.data.total
                        this.facilities = response.data.facilities
                        this.filter.facilityval = this.facilityVal = response.data.facilityval
                        this.facilityName = response.data.facilityname
                        store.commit('filter/SET_FILTER', this.filter)
                        store.commit('filter/SET_FACILITY_NAME', this.facilityName)
                        store.commit('filter/SET_FACILITY_VAL', this.filter.facilityval)
                        store.commit('all/SET_LOADING', false)
                        store.commit('device/SET_DATES', response.data.availableDates)
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
    }
}
</script>

<style scoped>

</style>
