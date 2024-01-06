import axios from "axios";
import router from "../routes";

export default {
    namespaced: true,
    state: {
        modal: false,
        title: '',
        name: '',
        dateCheck: '',
        dateOwn: '',
        clientId: null,
        updateList: false,
        comment: '',
        columns: [],
    },
    getters:{
        modal(state) {
            return state.modal
        },
        title(state) {
            return state.title
        },
        name(state) {
            return state.name
        },
        comment(state) {
            return state.comment
        },
        dateCheck(state) {
            return state.dateCheck
        },
        dateOwn(state) {
            return state.dateOwn
        },
        clientId(state) {
            return state.clientId
        },
        updateList(state) {
            return state.updateList
        },
        columns(state) {
            return state.columns
        },

    },
    mutations:{
        SHOW_MODAL(state) {
            state.modal = true
        },
        HIDE_MODAL(state) {
            state.modal = false
        },
        TITLE(state, val) {
            state.title = val
        },
        NAME(state, val) {
            state.name = val
        },
        COMMENT(state, val) {
            state.comment = val
        },
        DATECHECK(state, val) {
            state.dateCheck = val
        },
        DATEOWN(state, val) {
            state.dateOwn = val
        },
        CLIENTID(state, val) {
            state.clientId = val
        },
        UPDATE_LIST(state, val) {
            state.updateList = val
        },
        SET_COLUMNS(state, value) {
            state.columns = value
        }
    },
    actions:{
        async saveColumns({commit}, value) {
            commit('SET_COLUMNS', value)
            await axios.post(`/api/cards/save-columns`, {columnCards: value})
                .then(response => {
                        let aa = 10
                    }
                )
        },
        async getColumns({commit}, value) {
            // commit('SET_COLUMNS', value)
            await axios.get(`/api/cards/get-columns`,)
                .then(response => {
                        if (response.data.columnCards.length === 0) {
                            commit('SET_COLUMNS', [])
                        } else {
                            commit('SET_COLUMNS', response.data.columnCards)
                        }
                    }
                )
        }
    }
}
