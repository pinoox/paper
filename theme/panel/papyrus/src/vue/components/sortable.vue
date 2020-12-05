<template>
    <div>
        <slot></slot>
    </div>
</template>

<script>
    import Sortable from "sortablejs";

    export default {
       mounted() {

           let elements = this.$el.children;
           for(const element of elements)
           {
               Sortable.create(element);
           }
       }
    }
</script>