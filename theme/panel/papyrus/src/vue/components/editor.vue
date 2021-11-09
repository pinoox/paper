<template>
  <div v-if="isLoadEditor">
    <div class="toolbar-editor"></div>
    <simplebar class="simplebar">
      <div :style="{'width':paperSize + '%', 'margin-top':marginTop}" class="paper">
        <div class="content-editor row-editor" :style="{'margin':marginContent}">
          <ckeditor class="bg-danger" :value="getValue" @input="updateValue" :editor="initEditor"
                    :config="editorConfig"
                    @ready="onReady">
            <slot></slot>
          </ckeditor>
        </div>
      </div>
    </simplebar>
    <div class="statusbar">
      <div class="item revert">
        <div v-if="!!$parent.post_id" class="label history" @click="onHistoryDrawer()">
          <simple-svg :src="_icons.history"
                      width="14px"
                      customClassName="icon"/>
          {{ LANG.post.change_history }}
        </div>
        <div class="label" v-show="message!=null">{{ message }}</div>
      </div>
      <div class="item">
        <div class="label" :class="status">{{ LANG.post.status[status] }}</div>
      </div>
      <div class="item">
        <div class="label">{{ $parent.params.words }} {{ LANG.post.word }}</div>
        <div class="label">{{ $parent.params.characters }} {{ LANG.post.character }}</div>
      </div>
      <div class="item">
        <span class="label no-select">{{ LANG.post.size_screen }}</span>
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
let iconSave = require('!!raw-loader?!@img/svg/ic_save.svg');
let iconFullscreen = require('!!raw-loader?!@img/svg/ic_fullscreen.svg');
let iconExitFullscreen = require('!!raw-loader?!@img/svg/ic_exit_fullscreen.svg');

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
            'fastbtn:save',
            'fastbtn:fullscreen',
            '|',
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'fontSize',
            'fontColor',
            '|',
            'alignment',
            '|',
            'numberedList',
            'bulletedList',
            '|',
            'paragraph', 'heading1', 'heading2', 'heading3', 'heading4', 'heading5', 'heading6',
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
            'findAndReplace',
          ],
        },
        balloonToolbar: {
          items: [
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'fontSize',
            'highlight',
            '|',
            'alignment',
            '|',
            'link',
            'code',
          ]
        },
        language: this.currentLang,
        title: {
          placeholder: this.titlePlaceholder,
        },
        placeholder: this.placeholder,
        wordCount: {
          onUpdate: stats => {
            this.$parent.params.characters = stats.characters;
            this.$parent.params.words = stats.words;
          }
        },
        heading: {
          options: [
            {model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph'},
            {model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'},
            {model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'},
            {model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'},
            {model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4'},
            {model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5'},
            {model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6'}
          ]
        },
        image: {
          toolbar: [
            'linkImage',
            'imageTextAlternative',
            'toggleImageCaption',
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

          'bulletedList',
          'numberedList',
          '|',
          'blockQuote',
          'code',
          'horizontalLine',
          '|',
          'imageInsert',
          'mediaEmbed',
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
        mediaEmbed: {
          previewsInData: true,
          extraProviders: [
            {
              name: 'aparat',
              url: /^aparat\.com\/v\/(\w+)/,
              html: match => {
                const id = match[1];
                return (
                    '<div style="position: relative; padding-bottom: 100%; height: 0; padding-bottom: 56.2493%;">' +
                    `<iframe src="https://www.aparat.com/video/video/embed/videohash/${id}/vt/frame" ` +
                    'style="position: absolute; width: 100%; height: 100%; top: 0; left: 0;" ' +
                    'frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>' +
                    '</iframe>' +
                    '</div>'
                );
              },
            },
          ]
        },
        fastbtn: [
          {
            name: 'save',
            label: this.LANG.post.save,
            icon: iconSave.default,
            keystroke: 'Ctrl+S',
            tooltip: true,
            created(view) {
              vm.$watch('$parent.isSave', (val) => {
                view.isEnabled = !val;
              }, {
                immediate: true,
              });
            },
            action() {
              vm.$parent.save(null,true);
            }
          },
          {
            name: 'fullscreen',
            keystroke: 'Ctrl+F11',
            label: this.LANG.post.fullscreen,
            icon: iconFullscreen.default,
            tooltip: true,
            created(view) {
              vm.$watch('$parent.isOpenFullscreen', (status) => {
                vm._delay(() => {
                  view.icon = (status) ? iconExitFullscreen.default : iconFullscreen.default;
                  view.label = (!status) ? vm.LANG.post.fullscreen : vm.LANG.post.exit_fullscreen;
                }, 100);
              }, {
                immediate: true,
              });
            },
            action() {
              let status = vm.$parent.isOpenFullscreen;
              if (!status)
                vm.$parent.openFullscreen().catch(err => {
                  console.warn(`Error Paper attempting to enable full-screen mode: ${err.message} (${err.name})`);
                });
              else
                vm.$parent.closeFullscreen();
            }
          }
        ]
      }
    },
    getAutoSave() {
      let vm = this;
      return {
        waitingTime: parseInt(this.autosaveTime) * 1000,
        save(editor) {
          if (vm.autosave && !!vm.$parent.post_id && !vm.$parent.isSave && !vm.$parent.drawerName)
            return vm.$emit('save');
          else
            return false;
        }
      }
    },
    getValue() {
      let title = !!this.values.title ? this.values.title : '';
      let context = !!this.values.context ? this.values.context : '';
      return '<h1>' + title + '</h1>\n' + context;
    },
    paperSize: {
      get() {
        return !!this.userSettings.paperSize ? this.userSettings.paperSize : 75;
      },
      set(val) {
        this.userSettings.paperSize = val;
      }
    },
    marginTop() {
      return this.paperSize >= 100 ? '28px' : '64px';
    },
    marginContent() {
      return this.paperSize >= 100 ? '30px' : '0';
    },
  },
  data() {
    return {
      isLoadEditor: false,
      initEditor: DecoupledDocumentEditor,
    };
  },
  methods: {
    getTitle() {
      return window.paperEditor.plugins.get('Title').getTitle();
    },
    getBody() {
      return window.paperEditor.plugins.get('Title').getBody();
    },
    getHashId() {
      return this.$parent.params.hash_id;
    },
    updateValue: function (value) {
      this.callEvents();
    },
    onReady(editor) {
      window.paperEditor = editor;
      document.querySelector('.toolbar-editor').prepend(editor.ui.view.toolbar.element);
      editor.plugins.get('Notification').on('show:warning', (evt, data) => {
        let message = !!data.message ? data.message : data.title;
        this._notify('warn', message);
        evt.stop();
      });
      let vm = this;
      editor.keystrokes.listenTo(document);
      editor.keystrokes.set('ctrl+S', (e) => {
        e.preventDefault();
        vm.$emit('save');
      });
    },
    callEvents(data = null) {
      data = !!data ? data : {
        title: this.getTitle(),
        context: this.getBody(),
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

      this._delay(() => {
        this.saveUserSetting(this.paperSize, 'paperSize');
      }, 3000);
    },
    onHistoryDrawer() {
      this.$emit('onHistoryDrawer', true);
    }
  },
  mounted() {
    this.isLoadEditor = true;
    this.callEvents(this.values);
  },
}
</script>