import axios from "axios";
import router from "../routes";

export default {
    namespaced: true,
    state: {
        filter: {},
        facilityName: '',
        facilityVal: null,
        sorter: null

    },
    getters:{
        // modal(state) {
        //     return state.filter
        // },
        filter(state) {
            return state.filter
        },
        facilityName(state) {
            return state.facilityName
        },
        facilityVal(state) {
            return state.facilityVal
        },
        sorter(state) {
            return state.sorter
        }
    },
    mutations:{
        SET_FILTER(state, val) {
            state.filter = val
        },
        SET_SORTER(state, val) {
            state.sorter = val
        },
        SET_FACILITY_NAME(state, val) {
            state.facilityName = val
        },
        SET_FACILITY_VAL(state, val) {
            state.facilityVal = val
        },
    },
    actions:{
    }
}
