<template>
    <write :post_id="post_id" post_type="page"></write>
</template>

<script>
    import Write from "./write.vue";
    export default {
        props:['post_id'],
        components: {Write}
    }
</script>