<template>
    <div id="app-layout">
        <navigation></navigation>
        <router-view></router-view>
        <login-modal></login-modal>
    </div>
</template>

<script>
    import Navigation from "../components/global/Navigation";
    import LoginModal from "../components/global/LoginModal";

    export default {
        name: "Layout",
        components:
            {
                Navigation,
                LoginModal,
            },
        created() {
            this.$store.dispatch('loadCafes');
            this.$store.dispatch('loadUser');
            this.$store.dispatch('loadBrewMethods');
        },
    }
</script>

<style scoped>

</style>
