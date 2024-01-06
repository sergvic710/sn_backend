<template>
    <header class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="navbar-title">
                    {{ title }}
                </div>
                <ul class="navbar-menu">
                    <li class="navbar-menu-item">
                        <router-link to="/">Показания приборов учета</router-link>
                    </li>
                    <li class="navbar-menu-item">
                        <router-link to="/analytics/">Аналитика</router-link>
                    </li>
                    <li class="navbar-menu-item">
                        <router-link to="/cards/">Карточки жильцов</router-link>
                    </li>
                    <li class="navbar-menu-item">
                        <router-link to="/notrespond">Не опрашивается</router-link>
                    </li>
                </ul>
                <div class="navbar-profile">
                    <div class="navbar-profile-avatar mr-1">
                        <img src="/img/sanext.ico"/>
                    </div>
                    <div class="navbar-profile-menu">
                    <n-dropdown :options="options" :on-select="select">
                            {{user.name }}
                    </n-dropdown>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
import {mapActions} from "vuex";
import { h } from 'vue'
import {NDropdown, NButton} from "naive-ui";
import { NIcon } from "naive-ui";
import {
    PersonCircleOutline as UserIcon,
    Pencil as EditIcon,
    LogOutOutline as LogoutIcon
} from "@vicons/ionicons5";


const renderIcon = (icon) => {
    return () => {
        return h(NIcon, null, {
            default: () => h(icon)
        });
    };
};
export default {
    name: "Navbar",
    components: {NDropdown, NButton},
    props: {
        title: {type:String}
    },
    data(){
        return {
            facilityName: '',
            user:this.$store.state.auth.user,
            auth:this.$store.state.auth.authenticated,
            options: [
                // {
                //     label: 'Profile',
                //     key: 'profile',
                //     // icon: renderIcon(UserIcon)
                // },
                // {
                //     label: 'Edit Profile',
                //     key: 'editProfile',
                //     // icon: renderIcon(EditIcon)
                // },
                {
                    label: 'Logout',
                    key: 'logout',
                    icon: renderIcon(LogoutIcon)
                }
            ]
        }
    },
    methods:{
        select(key) {
            if( key === 'logout') {
                this.logout()
            }
        },
        ...mapActions({
            signOut:"auth/logout"
        }),
        async logout(){
            await axios.get('/sanctum/csrf-cookie')
            await axios.post('/api/logout').then(({data})=>{
                this.signOut()
                this.$router.push({name:"login"})
            })
        }
    }
}
</script>

<style scoped>

</style>
