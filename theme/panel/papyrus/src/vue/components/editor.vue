<template>
    <div v-if="isLoadEditor" class="content-editor row-editor">
        <ckeditor class="bg-danger" :value="value" @input="updateValue" :editor="editor" :config="editorConfig" @ready="onReady">
            <slot></slot>
        </ckeditor>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: String,
            },
            placeholder: {
                default: null,
            }
        },
        computed:{
            editorConfig(){
                return {
                    toolbar: {
                        items: [
                            'exportWord',
                            'exportPdf',
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
                            'todoList',
                            'link',
                            'blockQuote',
                            'imageUpload',
                            'insertTable',
                            'imageInsert',
                            'mediaEmbed',
                            '|',
                            'code'
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
                }
            }
        },
        data() {
            return {
                isLoadEditor:false,
                editor: DecoupledDocumentEditor,
            };
        },
        methods: {
            updateValue: function (value) {
                this.$emit('input', value);
            },
            onReady(editor)
            {
                document.querySelector('.toolbar-editor').prepend( editor.ui.view.toolbar.element );
            }
        },
        mounted()
        {
           // this.editorConfig.language = this.currentLang;
            //this.editorConfig.placeholder = 'lkjkljkljkljkl knmklkl';
            this.isLoadEditor = true;
        },
        watch:{
            countTranslate()
            {
                this.isLoadEditor = false;
                this.$nextTick(()=>{
                    this._delay(()=>{
                        this.isLoadEditor = true;
                      //  this.editorConfig.language = this.currentLang;
                      //  this.editorConfig.placeholder = this.LANG.answer.description_placeholder;
                    },500);
                });
            },
        }
    }
</script>