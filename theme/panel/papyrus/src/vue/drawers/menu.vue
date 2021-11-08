<template>
  <section>
    <ch-drawer custom-class="drawer-wrapper"
               :location='drawerPosition'
               :visible.sync='drawerOpen'
               :area="drawerArea"
               :before-close='handleBeforeClose'>
      <div slot='header' class="drawer-header">
        <div class="title">
          <div class="text">{{ LANG.panel.menus }}</div>
          <div class="subtext">{{ LANG.panel.menus_info }}</div>
        </div>
      </div>
      <div class="drawer-content">
        <div class="input-wrapper search-head">
          <div class="input-group">
            <input v-model="keyword" type="text"
                   :placeholder="LANG.panel.search" class="input">
          </div>
        </div>
        <div class="menus">
          <router-link v-for="m in filterMenu" @click.native="toggleDrawer()" class="item" :to="m.url">
            <b :class="m.icon" v-if="!!m.icon"></b>
            <simple-svg :src="m.image" v-else
                        :customClassName="m.customClass"
                        fill="#A5B8CE"/>
            <span class="text">{{ m.label }}</span>
          </router-link>
          <a class="item" :href="URL.FRONT" target="_blank">
            <simple-svg :src="_icons.eye"
                        customClassName="icon stroke"
                        fill="#A5B8CE"/>
            <span class="text">{{ LANG.panel.view_site }}</span>
          </a>
          <div class="item" @click="logoutPanel()">
            <simple-svg :src="_icons.logout"
                        customClassName="icon stroke"
                        fill="#A5B8CE"/>
            <span class="text">{{ LANG.panel.logout }}</span>
          </div>
        </div>
      </div>
      <div slot='footer' class="drawer-footer">
        <div>
          <div @click="toggleDrawer()" class="btn btn-simple">{{ LANG.panel.close }}</div>
        </div>
      </div>

    </ch-drawer>

  </section>
</template>

<script>

export default {
  props: ['open'],
  data() {
    return {
      drawerPosition: 'bottom',
      drawerArea: '90%',
      keyword: '',
    }
  },
  computed: {
    filterMenu() {
      return this.menu.filter((item) => {
        if (!!item.link && !this._module(item.link))
          return false;

        return this.keyword.toLowerCase().split(' ').every(v => item.label.toLowerCase().includes(v))
      })
    },
    menu() {
      return [
        {
          url: {name: 'dashboard'},
          label: this.LANG.panel.dashboard,
          image: this._icons.dashboard,
          customClass: 'icon',
          link: 'panel/dashboard',
        },
        {
          url: {name: 'write'},
          label: this.LANG.post.write,
          image: this._icons.pen,
          customClass: 'icon stroke',
          link: 'panel/write',
        },
        {
          url: {name: 'posts'},
          label: this.LANG.panel.posts,
          image: this._icons.article,
          customClass: 'icon',
          link: 'panel/posts',
        },
        {
          url: {name: 'comments'},
          label: this.LANG.panel.comments,
          image: this._icons.comment,
          customClass: 'icon stroke',
          link: 'panel/comment',
        },
        {
          url: {name: 'users'},
          label: this.LANG.panel.users,
          image: this._icons.users,
          customClass: 'icon',
          link: 'panel/user',
        },
        {
          url: {name: 'groups'},
          label: this.LANG.user.groups,
          icon: 'fal fa-users-cog',
          link: 'panel/group',
        },
        {
          url: {name: 'contacts'},
          label: this.LANG.panel.contacts,
          image: this._icons.call,
          customClass: 'icon stroke',
          link: 'panel/contact',
        },
        {
          url: {name: 'template'},
          label: this.LANG.panel.templates,
          image: this._icons.image,
          customClass: 'icon stroke',
          link: 'panel/templates',
        },
        {
          url: {name: 'category'},
          label: this.LANG.panel.category,
          image: this._icons.category,
          customClass: 'icon stroke',
          link: 'panel/category',
        },
        {
          url: {name: 'pages'},
          label: this.LANG.panel.pages,
          image: this._icons.page,
          customClass: 'icon stroke',
          link: 'panel/pages',
        },
        {
          url: {name: 'setting'},
          label: this.LANG.panel.settings,
          image: this._icons.setting,
          customClass: 'icon stroke',
          link: 'panel/setting',
        },
        {
          url: {name: 'profile'},
          label: this.LANG.panel.profile,
          image: this._icons.profile,
          customClass: 'icon',
          link: 'panel/profile',
        },
      ];
    },
    drawerOpen: {
      get() {
        return this.open;
      },
      set(val) {
        this.$emit('onClose', val);
      }
    }
  },
  methods: {
    logoutPanel() {
      this.logout(() => {
        this.toggleDrawer();
      })
    },
    changeCaptureDrawer(capture) {
      this.changeCapture(capture);
      this.toggleDrawer();
    },
    toggleDrawer() {
      this.drawerOpen = !this.drawerOpen;
    },
    handleBeforeClose(next) {
      this.toggleDrawer();
      next();
    },
    goTo(page) {
      this.$router.push({name: page}).catch(err => {
      });
      this.toggleDrawer();
    },

  },
}
</script>
