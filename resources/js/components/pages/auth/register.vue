<script setup>
    import { ref, reactive } from 'vue';
    import { RouterLink, useRouter } from 'vue-router';
    import axios from 'axios';

    const routes = useRouter();
    const form = reactive({
        name: '',
        email: '',
        password: '',
    });
    const name_error = ref('');
    const email_error = ref('');
    const pass_error = ref('');
    async function register() {
        try {
        const timer = ref(null);
        function clearValidationErrors() {
            name_error.value = '';
            email_error.value = '';
            pass_error.value = '';
        }
        function setValidationError() {
            clearValidationErrors();
            timer.value = setTimeout(() => {
                name_error.value = response.data.message.name;
                email_error.value = response.data.message.email;
                pass_error.value = response.data.message.password;
            }, 1);
            setTimeout(() => {
                clearValidationErrors();
            }, 5000);
        }
        const response = await axios.post('/api/register', form);
        // console.log(response)
        if (response.data.success) {
            localStorage.setItem('token', response.data.data.token);
            routes.push('/');
        } else {
            setValidationError();
        }
    } catch (err) {
        console.error(err);
    }
}
</script>
<template>
    <div class="bg-gray-50 dark:bg-gray-900 w-full">
        <div class="flex flex-col items-center justify-center px-5 py-20 mx-auto md:h-screen lg:py-0">
            <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-20 h-20 mr-2" src="https://tailwindui.com/img/logos/mark.svg?color=blue&shade=400" alt="logo">
            </a>
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold text-center leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">Register your Account</h1>
                    <form class="space-y-4 md:space-y-6" @submit.prevent="register">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input v-model="form.name" type="text" placeholder="Juan Dela Cruz" :class="{'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:border-red-500 dark:text-red-500 dark:placeholder-red-700 dark:focus-ring-500' : name_error}" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                            <p v-if="name_error" class="mt-1 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ name_error[0] }}</span></p>
                        </div>
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input v-model="form.email" type="email" :class="{'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:border-red-500 dark:text-red-500 dark:placeholder-red-700 dark:focus-ring-500' : email_error}" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com">
                            <p v-if="email_error" class="mt-1 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ email_error[0] }}</span></p>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input  v-model="form.password" type="password" placeholder="••••••••" :class="{'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:border-red-500 dark:text-red-500 dark:placeholder-red-700 dark:focus-ring-500' : pass_error}" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                            <p v-if="pass_error" class="mt-1 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ pass_error[0] }}</span></p>
                        </div>
                        <button type="submit" value="login" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Sign up</button>
                    </form>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                            Already have an account? <RouterLink to="/login" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign in</RouterLink>
                        </p>
                </div>
            </div>
        </div>
    </div>
</template>
