<template>
    <section>
        <ch-drawer custom-class="drawer-wrapper"
                   location="bottom"
                   :visible="open"
                   :destroy-on-close="true"
                   :blur="false"
                   area="90%"
                   @open="openDrawer()"
                   @close="closeDrawer()">
            <div slot="header" class="drawer-header">
                <div class="title">
                    <div class="text">{{LANG.post.preview}}</div>
                </div>
            </div>
            <div class="drawer-content">
                <div class="preview-editor">
                    <ckeditor disabled class="bg-danger" :value="getValue" :editor="initEditor"
                              :config="editorConfig"
                              @ready="onReady">
                        <slot></slot>
                    </ckeditor>
                </div>
            </div>
            <div slot="footer" class="drawer-footer">
                <div @click="closeDrawer()" class="btn btn-simple">{{LANG.post.close}}</div>
                <div class="btn btn-primary" @click="restorePost()">
                    {{LANG.post.restore}}
                </div>
                <div @click="deleteHistory()" class="btn btn-danger right">{{LANG.post.delete}}</div>

            </div>
        </ch-drawer>
    </section>
</template>

<script>

    export default {
        props: ['open', 'values'],
        data() {
            return {
                initEditor: DecoupledDocumentEditor,
                editor: null,
            }
        },
        computed: {
            editorConfig() {
                let vm = this;
                return {
                    language: 'fa',
                }
            },
            getValue() {
                let title = !!this.values.title ? this.values.title : '';
                let context = !!this.values.context ? this.values.context : '';
                return '<h1>' + title + '</h1>\n' + context;
            },
            historyItems: {
                get() {
                    return this.$parent.historyItems;
                },
                set(val) {
                    this.$parent.historyItems = val;
                }
            }
        },
        methods: {
            openDrawer() {
            },
            closeDrawer() {
                this.$emit('close', false);
            },
            restorePost() {
                this.ckEditor.setData(this.editor.getData());
                this.closeDrawer();
            },
            deleteHistory() {
                this._confirm(PINOOX.LANG.panel.are_you_sure_to_delete, () => {
                    this.$http.get(this.URL.API + 'post/deleteHistory/' + this.values.ph_id).then((json) => {
                        this.historyItems = this.historyItems.filter((item) => {
                            return item.ph_id !== this.values.ph_id;
                        });
                        this.closeDrawer();
                    });
                });
            },
            onReady(editor) {
                this.editor = editor;
            },
        },
    }
</script>