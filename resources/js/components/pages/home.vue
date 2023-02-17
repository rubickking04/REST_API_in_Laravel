<script setup>
    import { ref, onMounted } from 'vue';
    const name = ref('');
    onMounted(async() => {
        const data = await axios.get('api/user');
        name.value = data.data.name;
        // console.log(data);
    })
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
