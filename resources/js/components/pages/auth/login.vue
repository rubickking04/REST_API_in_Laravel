<script setup>
    import { ref, reactive } from 'vue';
    import { RouterLink, useRouter } from 'vue-router';
    import axios from 'axios';

    const routes = useRouter();
    const form = reactive({
        email: '',
        password: '',
        remember: '',
    });

    const error = ref('');
    const email_error = ref('');
    const pass_error = ref('');
    async function login() {
    try {
        const response = await axios.post('/api/login', form);
        const timer = ref(null);
        function clearValidationErrors() {
            email_error.value = '';
            pass_error.value = '';
        }
        function clearErrorValidation() {
            error.value = '';
        }
        function setValidationError() {
            clearValidationErrors();
            timer.value = setTimeout(() => {
                email_error.value =  response.data.errors.email;
                pass_error.value = response.data.errors.password;
            }, 1);
            setTimeout(() => {
                clearValidationErrors();
            }, 5000);
        }
        function setErrorValidation() {
            clearErrorValidation();
            timer.value = setTimeout(() => {
                error.value = response.data.message;
            }, 1);
            setTimeout(() => {
                clearErrorValidation();
            }, 5000);
        }
        // console.log(response)
        if (response.data.success) {
            localStorage.setItem('token', response.data.data.token);
            routes.push('/');
        }
        else {
            if (response.data.errors) {
                setValidationError();
            }
            else {
                setErrorValidation();
            }
        }
    }
    catch (err) {
        console.error(err);
    }
}
</script>
<template>
    <div class="w-full bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-5 py-20 mx-auto md:h-screen lg:py-0">
            <RouterLink to="/" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-20 h-20 mr-2" src="https://tailwindui.com/img/logos/mark.svg?color=blue&shade=400" alt="logo">
            </RouterLink>
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-center text-gray-900 md:text-2xl dark:text-white">Sign in to your Account</h1>
                    <div id="alert-2" v-if="error" class="flex p-4 mb-4 text-sm text-red-800 rounded-lg border-red-300 bg-red-50 dark:bg-red-200 dark:text-red-500 dark:border-red-900 border" role="alert">
                        <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Info</span>
                        <div>
                            <span class="text-sm font-medium">{{ error }}</span>
                        </div>
                    </div>
                    <form class="space-y-4 md:space-y-6" @submit.prevent="login">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                                </div>
                                <input v-model="form.email" type="email" id="input-group-1" class="block w-full pl-10 p-2.5 text-sm rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :class="{'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:border-red-500 dark:text-red-500 dark:placeholder-red-700 dark:focus-ring-500' : email_error}" placeholder="name@flowbite.com">
                            </div>
                            <p v-if="email_error" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ email_error[0] }}</span></p>
                            <!-- <input v-model="form.email" type="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com"> -->
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="w-5 h-5 text-gray-500 fa fa-lock dark:text-gray-400" ></i>
                                    <!-- <svg aria-hidden="true" class="w" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg> -->
                                </div>
                            <input v-model="form.password" :type="passwordFieldType" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :class="{'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:border-red-500 dark:text-red-500 dark:placeholder-red-700 dark:focus-ring-500' : pass_error}">
                            <i @click="togglePasswordVisibility" :class="passwordIconClass" class="absolute inset-y-0 right-0 pr-3 text-base text-gray-500 flex items-center text-sm leading-5" ></i>
                        </div>
                            <p v-if="pass_error" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ pass_error[0] }}</span></p>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input v-model="form.remember" id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded  bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" >
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember" class="text-gray-500 dark:text-gray-300">Remember me</label>
                                </div>
                            </div>
                            <a href="#" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">Forgot password?</a>
                        </div>
                        <button type="submit" value="login" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Sign in</button>
                    </form>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            Don’t have an account yet? <RouterLink to="/register" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign up</RouterLink>
                        </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                // email: '',
                password: '',
                passwordVisible: false,
            }
        },
    computed: {
            // isEmailValid() {
            //     const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
            //     return emailRegex.test(this.email)
            // },
            passwordFieldType() {
                return this.passwordVisible ? 'text' : 'password';
            },
            passwordIconClass() {
                return this.passwordVisible ? 'fa fa-eye-slash' : 'fa fa-eye';
            }
            },
        methods: {
            togglePasswordVisibility() {
                this.passwordVisible = !this.passwordVisible;
            }
        }
    }
</script>
