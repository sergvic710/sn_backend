<template>
    <section class="section">
        <div class="container">
            <search-account
                @updatedFilter="updateTopFiletr"
            ></search-account>
        </div>
    </section>
    <section class="section cards_table">
        <div class="container">
            <card-list
                :items = "data"
                :page = "page"
                :pageSize = "pageSize"
                :pageCount = "pageCount"
                :total = "total"
                :facilities = "facilities"
                :propFacilityVal = "facilityVal"
                @updatedFilter = "updateFilter"
                @pageUpdate = 'pageUpdate'
            ></card-list>
        </div>
    </section>
</template>


<script>
import searchAccount from "../components/SearchAccount.vue";
import CardList from "../components/CardList.vue";
import store from "../store";
import DeviceList from "../components/DeviceList.vue";

export default {
    name: "Cards",
    components: {DeviceList, searchAccount, CardList},
    data() {
        return {
            data: [],
            page: 1,
            pageSize: 20,
            pageCount: 0,
            total: 1,
            facilities: [],
            facilityVal: null,
            filter: {
                facilityval: null,
                pagesize: 25,
                page: 1,
            }
        }
    },
    mounted() {
        // store.commit('filter/SET_FILTER', this.filter)
        this.filter.facilityval = store.state.filter.facilityVal
        this.updateList()
    },
    watch: {
        '$store.state.cards.updateList': function () {
            if( store.state.cards.updateList ) {
                this.updateList()
                store.commit('cards/UPDATE_LIST', false)
            }
        }
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
            store.commit('filter/SET_FILTER', this.filter)
            this.updateList()
        },
        updateTopFiletr(data) {
            this.filter.page = 1
            this.filter.pagesize = this.pageSize
            this.filter.filteroptionval = data.filterTopOptionVal
            this.filter.filterinputval =  data.filterTopInputVal
            store.commit('filter/SET_FILTER', this.filter)
            this.updateList()
        },
        async updateList(filter) {
            store.commit('all/SET_LOADING', true)
            await this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post(`/api/cards`,this.filter)
                    .then(response => {
                        this.data = response.data.data
                        this.pageCount = response.data.lastPage
                        this.total = response.data.total
                        this.facilities =  response.data.facilities
                        this.facilityVal = response.data.facilityval
                        this.facilityName = response.data.facilityname
                        store.commit('filter/SET_FACILITY_NAME', this.facilityName)
                        store.commit('filter/SET_FACILITY_VAL', this.facilityVal)
                        store.commit('all/SET_LOADING', false)
                        // this.data = response.data.data;
                        // this.pagination.pageCount = response.data.lastPage
                        // this.pagination.itemCount = response.data.total
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
