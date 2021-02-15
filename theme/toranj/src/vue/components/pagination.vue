<template>
    <ul v-if="page !== undefined && page !== null && page.count > 0" class="pagination">
        <li @click="loadPageNotCurrent(page.first)" :class="page.current === page.first? 'disable' : ''">
            {{LANG.pagination.first}}
        </li>
        <li @click="goPage(page.prev)" :class="page.prev === null? 'disable' : ''">{{LANG.pagination.prev}}</li>
        <li v-if="!page.keepPrev">...</li>
        <li v-for="p in page.all" :class="p===page.current? 'active':''" @click="loadPageNotCurrent(p)">{{ p
            }}
        </li>
        <li v-if="!page.keepNext">...</li>
        <li @click="goPage(page.next)" :class="page.next === null? 'disable' : ''">{{LANG.pagination.next}}</li>
        <li @click="loadPageNotCurrent(page.last)" :class="page.current === page.last? 'disable' : ''">
            {{LANG.pagination.last}}
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            page: {},
        },
        methods: {
            goPage: function (page) {
                if (!page) return;
                let href = this.$el.getAttribute('href');
                if (href) {
                    href = href.replace("[page]", page);
                    window.location.replace(href);
                    return;
                }
                this.$emit("go", page);
            },
            loadPageNotCurrent: function (page) {
                if (page !== this.page.current) this.goPage(page);
            },
        },
    }
</script>