import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import auth from '@/store/auth'
import all from '@/store/all'
import cards from '@/store/cards'
import filter from '@/store/filter'
import device from '@/store/device'
import notRespond from '@/store/notrespond'

const store = createStore({
    plugins:[
        createPersistedState()
    ],
    modules:{
        auth, cards, filter, device, notRespond, all
    }
})
export default store
