<template>
    <div class="select-image">
        <ul>
            <li v-for="(item,index) in items" @dblclick="select(item)" @click="selectItem(item)" :class="!!item.active? 'active' : ''">
                <img :src="item[link]"/>
            </li>
            <slot></slot>
        </ul>
    </div>
</template>

<script>
    export default {
        name: "select-image",
        props: {
            value: {
                default: null,
            },
            link: {
                default: 'link',
            },
            file_id: {
                default: 'file_id',
            },
            items: {
                default: [],
                type: Array,
            },
            multiple: {
                default: false,
                type: Boolean,
            },
        },
        computed:
            {
                selectedList() {
                    return this.items.filter((item) => {
                        return !!item.active;
                    })
                }
            },
        methods: {
            select(item){
                this.$emit('dblclick', item);
            },
            selectItem(item) {
                if (!this.multiple) {
                    this.$set(item, 'active', true);
                    this.$emit('input', item);
                    this.$emit('select', item);
                } else {
                    this.$set(item, 'active', !item.active);
                    this.$emit('input', this.selectedList);
                    this.$emit('select', this.selectedList);
                }

            },
            resetActiveItems() {
                this.items.map((item) => {
                    this.$set(item, 'active', false);
                });
            },
        },
        watch: {
            value: {
                handler(val, oldVal) {
                    this.resetActiveItems();

                    if(!val)
                        return;
                    for (const item of this.items) {
                        if (!this.multiple) {
                            item.active = (val[this.file_id] === item[this.file_id]);
                            if (item.active)
                                break;
                        } else {
                            item.active = val.some(i => i[this.file_id] === item[this.file_id]);
                        }
                    }
                },
                immediate: true,
            }
        }
    }
</script>
