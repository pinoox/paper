<template>
    <div v-if="isLoadEditor" class="content-editor row-editor">
        <ckeditor class="bg-danger" :value="getValue" @input="updateValue" :editor="initEditor" :config="editorConfig"
                  @ready="onReady">
            <slot></slot>
        </ckeditor>
    </div>
</template>

<script>
    export default {
        props: {
            values: {
                default: {
                    title: null,
                    context: null,
                },
                type: Object,
            },
            placeholder: {
                default: null,
            }
        },
        computed: {
            editorConfig() {
                return {
                    toolbar: {
                        items: [
                            'undo',
                            'redo',
                            '|',
                            'heading',
                            'fontSize',
                            'fontFamily',
                            'fontColor',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'strikethrough',
                            'highlight',
                            '|',
                            'alignment',
                            '|',
                            'numberedList',
                            'bulletedList',
                            '|',
                            'indent',
                            'outdent',
                            '|',
                            'code',
                            'link',
                            'blockQuote',
                            'imageUpload',
                            'insertTable',
                            'imageInsert',
                            'mediaEmbed',
                            '|',
                            'exportWord',
                            'exportPdf',
                        ],
                    },
                    balloonToolbar: {
                        items: [
                            'bold',
                            'italic',
                            '|',
                            'undo',
                            'redo',
                        ]
                    },
                    language: 'fa',
                    image: {
                        toolbar: [
                            'imageTextAlternative',
                            'imageStyle:full',
                            'imageStyle:side'
                        ]
                    },
                    table: {
                        contentToolbar: [
                            'tableColumn',
                            'tableRow',
                            'mergeTableCells'
                        ]
                    },
                    title: {
                        placeholder: 'عنوان را وارد کنید'
                    },
                    placeholder: this.placeholder,
                    wordCount: {
                        onUpdate: stats => {
                            // stats.characters
                            // stats.words
                        }
                    }
                }
            },
            getTitle() {
                return this.editor.plugins.get('Title').getTitle();
            },
            getBody() {
                return this.editor.plugins.get('Title').getBody();
            },
            getValue() {
                return '<h1>' + this.values.title + '</h1>\n' + this.values.context;
            },
        },
        data() {
            return {
                isLoadEditor: false,
                initEditor: DecoupledDocumentEditor,
                editor: null,
            };
        },
        methods: {
            updateValue: function (value) {
                this.callEvents();
            },
            onReady(editor) {
                this.editor = editor;
                document.querySelector('.toolbar-editor').prepend(editor.ui.view.toolbar.element);
            },
            callEvents(data = null) {
                data = !!data ? data : {
                    title: this.getTitle,
                    context: this.getBody,
                };

                this.$emit('input', {
                    title: data.title,
                    context: data.context,
                });
                this.$emit('title', data.title);
                this.$emit('context', data.context);
            }
        },
        mounted() {
            this.isLoadEditor = true;
            this.callEvents(this.values);
        },
    }
</script>