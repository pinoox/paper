<template>
  <section class="page">
    <div class="menubar">
      <div class="items">
        <div v-if="!!post_id" @click="openDrawer('publish')" class="item publish-item">
          {{ !isPageType ? LANG.post.post_publication : LANG.post.page_publication }}
        </div>
        <div class="item" @click="save()">
          {{ LANG.post.save }}
        </div>
        <div v-if="!!post_id && post_type==='post'" class="item" @click="openDrawer('category')">
          {{ LANG.post.category }} {{ category != null ? '(' + category.cat_name + ')' : '' }}
        </div>
        <div class="item" @click="drawerName = 'image-manager'">
          {{ LANG.post.images }}
        </div>
        <router-link v-if="!!post_id && post_type==='post'"
                     :to="{name:'post-stats',params:{post_id:post.post_id}}" class="item">
          {{ LANG.post.stats }}
        </router-link>
        <router-link v-if="!!post_id && post_type==='post'"
                     :to="{name:'comments',params:{post_id:post.post_id}}" class="item">
          {{ LANG.post.comments }}
        </router-link>
        <div v-if="!!post_id" class="item" @click="drawerName = 'settings'">
          {{ LANG.post.settings }}
        </div>
      </div>
    </div>
    <pulled-drawer v-if="openHistory" @onClose="openHistory=false"></pulled-drawer>
    <div id="write" class="write-container">
      <editor class="content"
              :values="editor"
              :status="status"
              :message="message"
              v-model="params.editor"
              :autosave="CONFIG.write.autosave"
              :autosave-time="CONFIG.write.autosave_time"
              @save="save()"
              @onHistoryDrawer="openHistory=!openHistory"
              name="description"
              :title-placeholder="LANG.post.enter_title"
              :placeholder="LANG.post.enter_context">
      </editor>
    </div>
    <publish @onClose="drawerName=null" :open="drawerName==='publish'"></publish>
    <category v-if="post_type==='post'"
              :open="drawerName==='category'"
              :selected="category"
              @onClose="drawerName=null"
              @onSelected="setCategory"></category>
    <image-manager @onClose="drawerName = null" :open="drawerName === 'image-manager'"></image-manager>
    <settings @close="drawerName = null" :open="drawerName === 'settings'"></settings>
    <preview @close="drawerName = null" :values="preview" :open="drawerName === 'preview'"></preview>
    <input v-show="false" ref="file" type="file" name="file-input" @change="handleFileInput" multiple>
  </section>
</template>

<script>

import Editor from "../components/editor.vue";
import Publish from "../drawers/publish.vue";
import Category from "../drawers/category.vue";
import ImageManager from "../drawers/image-manager.vue";
import Settings from "../drawers/settings.vue";
import Preview from "../drawers/preview.vue";
import PulledDrawer from "../components/pulled-drawer.vue";

export default {
  name: 'write',
  components: {Preview, Editor, Category, Publish, ImageManager, Settings, PulledDrawer},
  beforeRouteLeave(to, from, next) {
    if (this.isSave) {
      next();
      return;
    }

    this._confirm(this.LANG.post.confirm_leave_write, () => {
      next();
    });
  },
  // beforeRouteUpdate(to, from, next) {
  //     if (to.name === this.$route.name) {
  //         {
  //             if (!!to.params && !!to.params.post_id)
  //                 window.history.pushState(null, {}, to.path);
  //             else
  //                 next();
  //         }
  //     } else {
  //         next();
  //     }
  // },
  data() {
    return {
      countStatus: 0,
      temp_post_id: null,
      preview: {},
      historyItems: [],
      openHistory: false,
      isSave: true,
      isOpenFullscreen: false,
      isSynced: false,
      post: {},
      editor: {
        title: '',
        context: '',
      },
      params: {
        editor: {},
        summary: '',
        tags: [],
        hash_id: null,
        image: null,
        post_key: '',
        characters: 0,
        schedule_date: null,
        words: 0,
        hook: 'disable',
      },
      category: null,
      images: [],
      status: 'draft',
      settings: {
        comment_status: 'open',
      },
      drawerName: false,
      message: null,
      timeSleep: 30,
      time: 0,
      isImageLoading: false,
    };
  },
  computed: {
    isPageType() {
      return this.post_type === 'page';
    },
    post_type() {
      return !!this.$route.name && this.$route.name === 'page-write' ? 'page' : 'post';
    },
    post_id:
        {
          get() {
            return this.temp_post_id;
          },
          set(val) {
            this.temp_post_id = val;
          }
        }
  },
  created() {
    this.setPostId();
    this.getInitData()
        .then(() => {
          return this.getImages();
        })
        .then(() => {
          this.getFeaturingImage();
        })
        .then(() => {
          this.locker();
          this.startTimer();
          this.$watch('params', () => {
            this.isSave = false;
          }, {
            deep: true,
          })
        });
  },
  methods: {
    locker() {
      document.onmousemove = () => {
        this.timeSleep = 30;
      };

      document.onkeypress = () => {
        this.timeSleep = 30;
      };
    },
    enableHook() {
      this.params.hook = 'enable';
    },
    disableHook() {
      this.params.hook = 'disable';
    },
    startTimer() {
      return setInterval(() => {
        this.timeSleep--;

        if (this.timeSleep > 0)
          this.time++;
      }, 1000);
    },
    setPostId() {
      if (!!this.$route.params.post_id)
        this.post_id = this.$route.params.post_id;
    },
    getInitData() {
      this.params.post_type = this.post_type;
      if (!!this.post_id) {
        return this.getPost();
      } else {
        return this.getHashId();
      }
    },
    handleFileDrop(e) {
      let droppedFiles = e.dataTransfer.files;
      if (!droppedFiles) return;
      ([...droppedFiles]).forEach(f => {
        this.uploadFiles(f);
      });
    },
    selectFile(isImageLoading = true) {
      this.$refs.file.click();
      this.isImageLoading = isImageLoading;
    },
    handleFileInput(e) {
      let files = e.target.files;
      if (!files) return;
      ([...files]).forEach(f => {
        this.uploadFiles(f);
      });
    },
    uploadFiles(file) {
      let data = new FormData();
      data.append('upload', file);
      data.append('hash_id', this.params.hash_id);
      let xhr = this.$http.CancelToken.source();
      this.$http.post(this.URL.API + 'post/imageUpload/', data, {
        cancelToken: xhr.token,
        params: {
          isPrimaryLoading: this.isImageLoading,
        },
        onUploadProgress: (progressEvent) => {
          let percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
        }
      }).then((json) => {
        if (!json.data.error) {
          this.pushToImages(json.data.file);
        } else {
          let message = json.data.error.message;
          this._notify('error', message);
        }
      }).catch(function (thrown) {
      });
    },
    pushToImages(file) {
      this.images.push({
        file_id: file.file_id,
        link: file.link,
      })
    },
    deleteFromImages(file) {
      return this.$http.post(this.URL.API + 'post/deleteImage', {
        file_id: file.file_id,
        hash_id: this.params.hash_id,
      }).then((json) => {
        if (json.data.status) {
          this.images = this.images.filter(function (image) {
            return image.file_id !== file.file_id;
          });
        } else {
          let message = json.data.result;
          this._notify('error', message);
        }

      });
    },
    openDrawer(drawerName) {
      if (drawerName === 'publish')
        this.setEditorFields(this.params.editor);

      this.drawerName = drawerName;
    },
    getImages() {
      let hash_id = this.params.hash_id;
      return this.$http.get(this.URL.API + 'post/getImages/' + hash_id).then((json) => {
        this.images = !!json.data ? json.data : [];
      });
    },
    setEditorFields(data) {
      this.editor = {
        title: !!data['title'] ? data['title'] : '',
        context: !!data['context'] ? data['context'] : '',
      };
    },
    getPost() {
      return this.$http.get(this.URL.API + 'post/get/' + this.post_id + '/' + this.post_type).then((json) => {
        if (!json.data) {
          this._routerReplace({name: 'error'});
          return;
        }
        this.post = json.data;
        this.params.post_id = this.post_id;
        this.setEditorFields({
          title: json.data.draft_title,
          context: json.data.draft_context,
        });
        this.params.tags = this.createTags(json.data.tags);
        this.category = json.data.category;
        this.params.summary = !!json.data.summary ? json.data.summary : '';
        this.params.post_key = !!json.data.post_key ? json.data.post_key : '';
        this.params.schedule_date = !!json.data.schedule_date ? json.data.schedule_date : null;
        this.status = !!json.data.status ? json.data.status : 'draft';
        this.settings.comment_status = !!json.data.comment_status ? json.data.comment_status : 'open';
        this.params.hash_id = json.data.hash_id;
        this.isSynced = !!json.data.synced ? !!(parseInt(json.data.synced)) : false;
      });
    },
    getFeaturingImage() {
      if (!this.post.image_id)
        return;

      for (const image of this.images) {
        if (image.file_id === this.post.image_id) {
          this.$set(this.params, 'image', image);
          break;
        }
      }
    },
    deleteImageFeature(image) {
      if (image.file_id === this.params.image.file_id)
        this.params.image = null;
    },
    getHashId() {
      return this.$http.get(this.URL.API + 'post/getHashId/').then((json) => {
        this.params.hash_id = json.data.result;
      });
    },
    changeStatus(status) {
      if (!status)
        return;

      this.countStatus++;
      this.status = status;
      this.post.title = this.params.editor.title;
      this.post.context = this.params.editor.context;
      this.isSynced = true;
    },
    save(status = null) {
      let params = this.getFormData(this.params);
      this.disableHook();
      if (!!status)
        params.append('status', status);

      this.message = this.LANG.panel.saving;

      return this.$http.post(this.URL.API + 'post/save', params, this.offLoading).then((json) => {
        if (json.data.status) {
          this.isSave = true;
          this.replaceUrl(json.data.result);
          this.isSynced = false;
          this.message = this.LANG.panel.saved + ' (' + this._timeNow() + ')';
          this.changeStatus(status);
          this.time = 0;
        } else {
          this.message = json.data.message;
          this._notify('error', json.data.message, 'app');
        }

        return json.data;
      });
    },
    replaceUrl(post_id) {
      if (!!this.post_id)
        return;

      this.post_id = post_id;
      this._routerReplace({name: this.$route.name, params: {post_id: post_id}});
      this.getPost().then(() => {
        this.isSave = true;
      });
    },
    createTags(tags) {
      tags = !!tags ? tags : [];
      return tags.map(function (tag) {
        return (typeof tag === "object") ? tag['tag_name'] : tag;
      });
    },
    addFormTags(tags, formData) {
      for (let index in tags) {
        if (!tags[index] === undefined || !tags[index] === null)
          continue;
        let tag = tags[index];
        tag = (typeof tag === "object") ? tag.tag_name : tag;
        formData.append('tags[' + index + ']', tag);
      }
    },
    getFormData(params) {
      let formData = new FormData();
      for (let key in params) {
        let value = (params[key] !== null) ? params[key] : '';

        if (key === 'tags') {
          this.addFormTags(params[key], formData);
        } else if (key === 'image') {
          let image = !!value.file_id ? value.file_id : '';
          formData.append('image', image);
        } else if (key === 'editor') {
          formData.append('title', value.title);
          formData.append('context', value.context);
        } else {
          formData.append(key, value);
        }
      }

      formData.append('time', this.time);

      return formData;
    },
    setCategory(val) {
      this.category = val;
    },
    openFullscreen() {
      let ckBody = $('#write').find('.ck-body-wrapper');
      if (!ckBody || ckBody.length <= 0) {
        ckBody = $('.ck-body-wrapper');
        ckBody.css('position', 'absolute');
        $('#write').append(ckBody);
      }

      let el = document.getElementById('write');
      if (el.requestFullscreen) {
        return el.requestFullscreen();
      } else if (el.mozRequestFullScreen) {  /* Firefox */
        return el.mozRequestFullScreen();
      } else if (el.webkitRequestFullscreen) { /* Safari */
        return el.webkitRequestFullscreen();
      } else if (el.msRequestFullscreen) { /* IE11 */
        return el.msRequestFullscreen();
      }
    },
    closeFullscreen() {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.mozExitFullScreen) {  /* Firefox */
        document.mozExitFullScreen();
      } else if (document.webkitExitFullscreen) { /* Safari */
        document.webkitExitFullscreen();
      } else if (document.msExitFullscreen) { /* IE11 */
        document.msExitFullscreen();
      }
    },
    checkFullscreen() {
      let vm = this;
      this.$nextTick(() => {
        let el = document.getElementById('write');
        el.addEventListener('fullscreenchange', (event) => {
          vm.isOpenFullscreen = !vm.isOpenFullscreen;
          $('#write').toggleClass('fullscreen');
        });
      });
    },
  },
  mounted() {
    this.checkFullscreen();
    $('.drawer--blur').removeClass('drawer--blur');
    $('.toggle-over-flow').removeClass('toggle-over-flow');
  },
  watch: {
    drawerName() {
      $('.app-container').toggleClass('drawer--blur');
      $('body').toggleClass('toggle-over-flow');
    },
  }
}
</script>