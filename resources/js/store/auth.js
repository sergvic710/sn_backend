import axios from "axios";
import router from "../routes";

export default {
    namespaced: true,
    state:{
        authenticated:false,
        user:{}
    },
    getters:{
        authenticated(state){
            console.log('8')
            // axios.get('/api/user').then(({data})=>{
            //     console.log(data)
            // }).catch(({response})=>{
            //     if(response.status===401) {
            //         commit('SET_USER', {})
            //         commit('SET_AUTHENTICATED', false)
            //     }
            // });
            return state.authenticated
        },
        user(state){
            return state.user
        }
    },
    mutations:{
        SET_AUTHENTICATED (state, value) {
            state.authenticated = value
        },
        SET_USER (state, value) {
            state.user = value
        }
    },
    actions:{
        login({commit}){
            return axios.get('/api/user').then(({data})=>{
                commit('SET_USER',data)
                commit('SET_AUTHENTICATED',true)
                router.push({name:'home'})
            }).catch(({response:{data}})=>{
                commit('SET_USER',{})
                commit('SET_AUTHENTICATED',false)
            })
        },
        logout({commit}){
            commit('SET_USER',{})
            commit('SET_AUTHENTICATED',false)
        },
        checkAuth({commit}) {
            return axios.get('/api/user').then(({data}) => {
                console.log(data)
                commit('SET_USER', data)
                commit('SET_AUTHENTICATED', true)
            }).catch(({response: {data}}) => {
                // console.log(response)
                console.log(data)
                commit('SET_USER', {})
                commit('SET_AUTHENTICATED', false)
                // router.push({name: 'login'})
            })
        }
    }
}
