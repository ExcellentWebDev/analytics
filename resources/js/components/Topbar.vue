<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="dark">
            <b-navbar-brand href="#"><img src="../../img/favicon.png" width="32"></b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
            <b-navbar-nav>
                <b-nav-item href="#" @click="search">Search Events</b-nav-item>
                <b-nav-item href="#" v-if="role == 1" @click="license">License</b-nav-item>
            </b-navbar-nav>

            <b-navbar-nav class="ml-auto">
                <b-nav-item right href="#" @click="logout">Logout</b-nav-item>
            </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>
import router from 'vue-router';
export default {
    name: 'Topbar',
    data: () => ({
        role: ""
    }),
    methods: {
        logout() {
            localStorage.setItem("status", 0);
            this.$store.dispatch('LOGOUT_HANDLE');
        },
        search() {
            this.$router.push('/search');
        },
        license() {
            this.$router.push('/license');
        },
    },
    mounted() {
        this.role = localStorage.getItem("role");
    }
}
</script>
<style scoped>
    .nav-link {
        color: white !important;
    }
    .nav-link:hover {
        color: #18c5a9 !important;
    }
</style>