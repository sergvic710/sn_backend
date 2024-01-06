import axios from "axios";
import router from "../routes";

export default {
    namespaced: true,
    state: {
        loading: false
    },
    getters: {
        loading(state) {
            return state.loading
        }
    },
    mutations: {
        SET_LOADING(state, value) {
            state.loading = value
        }
    },
}
