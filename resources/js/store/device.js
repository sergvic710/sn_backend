import axios from "axios";
import router from "../routes";

export default {
    namespaced: true,
    state: {
        filter: {},
        columns: {},
        loading: false,
        availableDates: []
    },
    getters: {
        authenticated(state) {
            return state.authenticated
        },
        user(state) {
            return state.user
        },
        columns(state) {
            return state.columns
        },
        loading(state) {
            return state.loading
        },
        availableDates(state) {
            return state.availableDates
        }
    },
    mutations: {
        SET_AUTHENTICATED(state, value) {
            state.authenticated = value
        },
        SET_USER(state, value) {
            state.user = value
        },
        SET_COLUMNS(state, value) {
            state.columns = value
        },
        SET_LOADING(state, value) {
            state.loading = value
        },
        SET_DATES(state, value) {
            state.availableDates = value
        }
    },
    actions: {
        async saveColumns({commit}, value) {
            commit('SET_COLUMNS', value)
            await axios.post(`/api/devices/save-columns`, {columnDevices: value})
                .then(response => {
                        let aa = 10
                    }
                )
        },
        async getColumns({commit}, value) {
            // commit('SET_COLUMNS', value)
            await axios.get(`/api/devices/get-columns`)
                .then(response => {
                        if (response.data.columnDevices.length === 0) {
                            commit('SET_COLUMNS', [])
                        } else {
                            commit('SET_COLUMNS', response.data.columnDevices)
                        }
                    }
                )
        }
    }
}
