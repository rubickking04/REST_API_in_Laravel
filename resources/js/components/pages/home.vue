<script setup>
    import { ref, onMounted } from 'vue';
const name = ref('');
    function removeToken() {
        localStorage.removeItem('token');
    }
    onMounted(async () => {
        try {
            const data = await axios.get('api/user');
            name.value = data.data.name;
            console.log(data);
        }
        catch (error) {
            if (error.response.status === 401) {
                removeToken();
            }
            console.error(error);
        }
    });
</script>
<template>
    <div class="py-20">
        <Navbar/>
    <p class="py-20 text-6xl text-center text-indigo-400">Welcome, {{ name }}</p>
    </div>
</template>
<script>
import Navbar from './navbar.vue';
export default {
    components: {
        Navbar
    }
}
</script>
