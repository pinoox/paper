<template>
    <section class="splash">
        <div>R</div>
        <div>E</div>
        <div>P</div>
        <div>A</div>
        <div>P</div>
    </section>
</template>

<script>

    export default {
        created() {
            this.$store.state.checkLogin = true;
        }
    }

</script>