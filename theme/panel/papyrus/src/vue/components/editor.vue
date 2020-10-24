<template>
    <div v-if="isLoadEditor">
        <div class="toolbar-editor"></div>
        <div :style="{'width':paperSize + '%', 'margin-top':marginTop}" class="paper">
            <div class="content-editor row-editor" :style="{'margin':marginContent}">
                <ckeditor class="bg-danger" :value="getValue" @input="updateValue" :editor="initEditor"
                          :config="editorConfig"
                          @ready="onReady">
                    <slot></slot>
                </ckeditor>
            </div>
        </div>
        <div class="statusbar">
            <div class="item revert" v-show="message!=null">
                <div class="label">{{message}}</div>
            </div>
            <div class="item">
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
            message: {
                default: null
            },
            status: {
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
            autosaveTime: {
                default: 10,
            },
            autosave: {
                default: false,
            },
        },
        computed: {
            editorConfig() {
                let vm = this;
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
                            'horizontalLine',
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
                    title: {
                        placeholder: this.titlePlaceholder,
                    },
                    placeholder: this.placeholder,
                    wordCount: {
                        onUpdate: stats => {
                            this.stats = stats;
                        }
                    },
                    image: {
                        toolbar: [
                            'linkImage',
                            'imageTextAlternative',
                            '|',
                            'imageStyle:alignRight',
                            'imageStyle:alignCenter',
                            'imageStyle:alignLeft',
                            '|',
                            'imageResize',

                        ],
                        resizeOptions: [
                            {
                                name: 'imageResize:original',
                                value: null,
                                label: '100%'
                            },
                            {
                                name: 'imageResize:75',
                                label: '75%',
                                value: '75'
                            },
                            {
                                name: 'imageResize:50',
                                label: '50%',
                                value: '50'
                            },
                            {
                                name: 'imageResize:25',
                                label: '25%',
                                value: '25'
                            },
                        ],
                        styles: [
                            'alignLeft',
                            'alignCenter',
                            'alignRight'
                        ],
                    },
                    paperUpload: {
                        uploadUrl: this.URL.API + 'post/imageUpload',
                        withCredentials: true,
                        params: {
                            hash_id: vm.getHashId,
                        },
                        headers: {
                            Authorization: this.tokenAuth(),
                        },
                        getFile(file) {
                            vm.$parent.pushToImages(file);
                        },
                    },
                    link: {
                        decorators: [
                            {
                                mode: 'manual',
                                label: 'Open in a new tab',
                                defaultValue: true,			// This option will be selected by default.
                                attributes: {
                                    target: '_blank',
                                    rel: 'noopener noreferrer'
                                }
                            },
                            {
                                mode: 'manual',
                                label: 'Downloadable',
                                attributes: {
                                    download: 'file'
                                }
                            },
                        ]
                    },
                    fontFamily: {
                        options: [
                            'default',
                            'Arial',
                            'sans-serif',
                            'Ubuntu',
                            'iransans',
                            'Karla',
                            'serif',
                        ],
                    },
                    blockToolbar: [

                        'bulletedList', 'numberedList',
                        '|',
                        'blockQuote', 'imageUpload'
                    ],
                    table: {
                        contentToolbar: [
                            'tableColumn', 'tableRow', 'mergeTableCells',
                            'tableProperties', 'tableCellProperties'
                        ],
                        tableProperties: {},
                        tableCellProperties: {}
                    },
                    autosave: this.getAutoSave,
                }
            },
            getTitle() {
                return this.ckEditor.plugins.get('Title').getTitle();
            },
            getAutoSave() {
                let vm = this;
                return {
                    waitingTime: parseInt(this.autosaveTime) * 1000,
                    save(editor) {
                        if (vm.autosave)
                            return vm.$emit('save');

                    }
                }
            },
            getBody() {
                return this.ckEditor.plugins.get('Title').getBody();
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
                paperSize: 75,
                marginTop: '64px',
                marginContent: '0',
                stats: {
                    characters: 0,
                    words: 0,
                },
            };
        },
        methods: {
            getHashId() {
                return this.$parent.params.hash_id;
            },
            updateValue: function (value) {
                this.callEvents();
            },
            onReady(editor) {
                this.ckEditor = editor;
                document.querySelector('.toolbar-editor').prepend(editor.ui.view.toolbar.element);
                this.ckEditor.plugins.get('Notification').on('show:warning', (evt, data) => {
                    let message = !!data.message ? data.message : data.title;
                    this._notify('warn', message);
                    evt.stop();
                });
                let vm = this;
                editor.keystrokes.listenTo( document );
                editor.keystrokes.set('ctrl+S', (e) => {
                    e.preventDefault();
                    vm.$emit('save');
                });
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
                this.marginContent = this.paperSize >= 100 ? '30px' : '0';
            },
        },
        mounted() {
            this.isLoadEditor = true;
            this.callEvents(this.values);
        },
    }
</script>