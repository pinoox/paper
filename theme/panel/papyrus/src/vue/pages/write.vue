<template>
    <section class="page">
        <div class="write-container">
            <div class="toolbox">
                <div class="items">
                    <div class="item" @click="save()">
                        {{LANG.post.save}}
                    </div>
                    <div @click="openDrawer('publish')" class="item">
                        {{LANG.post.publication}}
                    </div>
                    <div class="item" @click="openDrawer('category')">
                        {{LANG.post.category}}
                    </div>
                </div>
            </div>
            <editor class="content"
                    :values="editor"
                    :status="status"
                    v-model="params.editor"
                    name="description"
                    :title-placeholder="LANG.post.enter_title"
                    :placeholder="LANG.post.enter_context">
            </editor>
        </div>
        <publish @onClose="drawerName=null" :open="drawerName==='publish'"></publish>
        <category @onClose="drawerName=null" :open="drawerName==='category'"></category>
    </section>
</template>

<script>

    import Editor from "../components/editor.vue";
    import Publish from "../drawers/pubish.vue";
    import Category from "../drawers/category.vue";

    export default {
        name: 'write',
        props: ['post_id'],
        components: {Editor, Category, Publish},
        beforeRouteLeave(to, from, next) {
            // this._confirm('confirm?', () => {
            next();
            // });
        },
        created() {
            if (!!this.post_id)
                this.getPost();
        },
        data() {
            return {
                editor: {
                    title: null,
                    context: null,
                },
                params: {
                    editor: {},
                    summary: '',
                    status: 'draft',
                    tags: [],
                },
                status: 'draft',
                drawerName: false,
                stats: {
                    word: 0,
                    charecter: 0,
                }
            };
        },
        methods: {
            openDrawer(drawerName) {
                if (drawerName === 'publish')
                    this.editor = this.params.editor;

                this.drawerName = drawerName;
            },
            getPost() {
                this.$http.post(this.URL.API + 'post/get/' + this.post_id).then((json) => {
                    this.params.post_id = this.post_id;
                    this.editor = {
                        title: json.data.title,
                        context: json.data.context,
                    };
                    this.params.tags = json.data.tags;
                    this.params.summary = json.data.summary;
                    this.params.status = json.data.status;
                    this.status = json.data.status;
                });
            },
            changeStatus(status){
              this.params.status = status;
              this.save();
            },
            save() {
                let params = this.getFormData(this.params);
                this.$http.post(this.URL.API + 'post/save', params).then((json) => {
                    if (this._messageResponse(json.data)) {
                        this.status = this.params.status;
                        if (!this.post_id)
                            this._routerReplace({name: 'post-edit', params: {post_id: json.data.result}});
                    }
                });
            },
            getFormData(params) {
                let formData = new FormData();
                for (let key in params) {
                    let value = (params[key] !== null) ? params[key] : '';

                    if (key === 'tags') {
                        let tags = params[key];
                        for (let index in tags) {
                            if (!tags[index] === undefined || !tags[index] === null)
                                continue;
                            let tag = tags[index];
                            tag = (typeof tag === "object") ? tag.tag_name : tag;
                            formData.append('tags[' + index + ']', tag);
                        }
                    } else if (key === 'editor') {
                        formData.append('title', value.title);
                        formData.append('context', value.context);
                    } else {
                        formData.append(key, value);
                    }
                }

                return formData;
            },
        }
    }
</script>
