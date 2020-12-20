<template>
    <simplebar class="form-wrapper">
        <header>
            <div class="form-header">
                <div class="title">
                    <div class="text">{{LANG.post.category}}</div>
                    <div class="subtext">{{LANG.post.manage_category}}</div>
                </div>
            </div>
        </header>
        <div class="form-content">
            <category-nest :is-edit="true">
            </category-nest>

        </div>
    </simplebar>
</template>

<script>
    import CategoryNest from '../components/category-nest.vue';

    export default {
        components: {CategoryNest},
    }
</script>