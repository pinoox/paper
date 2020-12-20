<template>
    <section>
        <ch-drawer custom-class="drawer-wrapper"
                   location="bottom"
                   :visible="open"
                   :destroy-on-close="true"
                   :blur="false"
                   area="90%"
                   @close="closeDrawer">
            <div slot='header' class="drawer-header">
                <div class="title" v-if="comment!=null">
                    <router-link target="_blank" :to="{name:'write',params:{post_id:comment.post_id}}" class="text">{{comment.title}}
                    </router-link>
                </div>
            </div>
            <div class="drawer-content">
                <div v-if="comment!=null" class="comment">
                    <div class="meta">
                        <span class="item">{{comment.approx_insert_date}}</span>
                    </div>

                    <div class="message">
                        <span class="author">{{comment.full_name}}:</span> {{comment.message}}
                    </div>
                </div>
            </div>
            <div slot='footer' class="drawer-footer">
                <div @click="closeDrawer()" class="btn btn-simple">{{LANG.panel.close}}</div>
            </div>
        </ch-drawer>
    </section>
</template>

<script>
    export default {
        props: ['open', 'comment'],
        data() {
            return {}
        },
        methods: {
            closeDrawer() {
                this.$emit('close', false);
            },
        },
    }
</script>