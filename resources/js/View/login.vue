<template>
    <div class="w-96 h-96">
        <div class="text-center flex justify-center">
            <img src="/img/sanext1.png">
        </div>
        <div class="font-bold text-2xl text-center p-7">
            Диспетчеризация
        </div>
        <div class="w-full">
            <form class="login-form">
                <div class="col-12" v-if="error.length > 0">
                    <div class="">
                       {{ error }}
                    </div>
                </div>
                <div class="mt-3 w-full">
                    <input type="text" v-model="email" name="email" id="email"
                           class="p-4 text-base bg-gray-200 border-none w-full h-14 focus-visible:outline-none">
                    <!--                    <input type="text" v-model="auth.email" name="email" id="email" class="form-control">-->
                </div>
                <div class="mt-3">
                    <input type="password" v-model="password" name="password" id="password"
                           class="p-4 text-base bg-gray-200 border-none w-full h-14 focus-visible:outline-none">
                    <!--                    <input type="password" v-model="auth.password" name="password" id="password" class="form-control">-->
                </div>
                <div class="pt-5 pb-5  text-xl text-left flex">
                    <label class="form-checkbox">
                    <div class="form-checkbox__input">
                        <input type="checkbox" name="remember" id="remember">
                        <div class="form-checkbox__square"></div>
                    </div>
                    <span class="pl-3 text-base">
                       Запомнить меня на этом компьютере
                    </span>
                    </label>
                </div>
                <div class="mt-2 w-full">
                    <!--                    <input type="submit" value="Войти" class="w-full h-14 bg-blue-700 text-white text-base rounded">-->
                    <button type="submit" :disabled="processing" @click="login"
                            class="w-full h-14 bg-blue-700 text-white text-base rounded">
                        {{ processing ? "Выполняется вход " : "Войти" }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</template>

<script>
import {mapActions} from 'vuex'

export default {
    name: "login",
    data() {
        return {
            auth: {
                email: "",
                password: ""
            },
            email: '',
            password: '',
            error: '',
            validationErrors: {},
            processing: false
        }
    },
    methods: {
        ...mapActions({
            signIn: 'auth/login'
        }),
        async login(e) {
            this.processing = true
            e.preventDefault()
            if (this.password.length > 0) {
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('api/login', {
                        email: this.email,
                        password: this.password
                    })
                        .then(response => {
                            console.log(response.data)
                            this.processing = false;
                            if (response.data.success) {
                                this.signIn()
                                // this.$router.go('/')
                            } else {
                                this.error = response.data.message
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                            this.processing = false;
                        });
                })
            }
        },
    }
}
</script>
