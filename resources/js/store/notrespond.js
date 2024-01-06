import axios from "axios";
import router from "../routes";

export default {
    namespaced: true,
    state: {
        filter: {},
        columns: [],
    },
    getters: {
        columns(state) {
            return state.columns
        }
    },
    mutations: {
        SET_COLUMNS(state, value) {
            state.columns = value
        }
    },
    actions: {
        async saveColumns({commit}, value) {
            commit('SET_COLUMNS', value)
            await axios.post(`/api/devices/notrespond/save-columns`, {columnNotRespond: value})
                .then(response => {
                        let aa = 10
                    }
                )
        },
        async getColumns({commit}, value) {
            // commit('SET_COLUMNS', value)
            await axios.get(`/api/devices/notrespond/get-columns`)
                .then(response => {
                        if (response.data.columnNotRespond.length === 0) {
                            commit('SET_COLUMNS', [])
                        } else {
                            commit('SET_COLUMNS', response.data.columnNotRespond)
                        }
                    }
                )
        }
    }
}
