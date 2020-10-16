<template>
    <div v-if="isLoadEditor">
        <div class="toolbar-editor"></div>
        <div :style="{'width':paperSize + '%', 'margin-top':marginTop}" class="paper">
            <div class="content-editor row-editor">
                <ckeditor class="bg-danger" :value="getValue" @input="updateValue" :editor="initEditor"
                          :config="editorConfig"
                          @ready="onReady">
                    <slot></slot>
                </ckeditor>
            </div>
        </div>
        <div class="statusbar">
            <div class="item revert">
                <div class="label" :class="status">{{LANG.post.status[status]}}</div>
            </div>
            <div class="item">
                <div class="label">{{stats.words}} {{LANG.post.word}}</div>
                <div class="label">{{stats.characters}} {{LANG.post.character}}</div>
            </div>
            <div class="item">
                <span class="label no-select">{{LANG.post.size_screen}}</span>
                <div class="zoom in" @click="resizePaper('in')">
                    <simple-svg :src="_icons.zoomIn"
                                customClassName="icon"
                                fill="#A5B8CE"/>
                </div>
                <div class="zoom out" @click="resizePaper('out')">
                    <simple-svg :src="_icons.zoomOut"
                                customClassName="icon"
                                fill="#A5B8CE"/>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: {
            status:{
                default: 'draft',
            },
            values: {
                default: {
                    title: null,
                    context: null,
                },
                type: Object,
            },
            placeholder: {
                default: null,
            },
            titlePlaceholder: {
                default: null,
            },
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
                            'fontSize',
                            '|',
                            'alignment',
                            'numberedList',
                            'bulletedList',
                            '|',
                            'link',
                            'blockQuote',
                            'code',
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
                        placeholder: this.titlePlaceholder,
                    },
                    placeholder: this.placeholder,
                    wordCount: {
                        onUpdate: stats => {
                           this.stats = stats;
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
                let title = !!this.values.title ? this.values.title : '';
                let context = !!this.values.context ? this.values.context : '';
                return '<h1>' + title + '</h1>\n' + context;
            },
        },
        data() {
            return {
                isLoadEditor: false,
                initEditor: DecoupledDocumentEditor,
                editor: null,
                paperSize: 75,
                marginTop: '64px',
                stats:{
                    characters:0,
                    words:0,
                },
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
            },
            resizePaper(zoom) {
                if ((zoom === 'in' && this.paperSize < 100)) this.paperSize += 5;
                if ((zoom === 'out' && this.paperSize > 50)) this.paperSize -= 5;
                this.marginTop = this.paperSize >= 100 ? '28px' : '64px';
            },
        },
        mounted() {
            this.isLoadEditor = true;
            this.callEvents(this.values);
        },
    }
</script>