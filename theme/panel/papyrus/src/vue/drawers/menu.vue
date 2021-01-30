<template>
    <section>
        <ch-drawer custom-class="drawer-wrapper"
                   :location='drawerPosition'
                   :visible.sync='drawerOpen'
                   :area="drawerArea"
                   :before-close='handleBeforeClose'>
            <div slot='header' class="drawer-header">
                <div class="title">
                    <div class="text">{{LANG.panel.menus}}</div>
                    <div class="subtext">{{LANG.panel.menus_info}}</div>
                </div>
            </div>
            <div class="drawer-content">
                <div class="menus">
                    <router-link @click.native="toggleDrawer()" class="item" :to="{name:'dashboard'}">
                        <simple-svg :src="_icons.dashboard"
                                    customClassName="icon"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.dashboard}}</span>
                    </router-link>
                    <router-link @click.native="toggleDrawer()" class="item" :to="{name:'write'}">
                        <simple-svg :src="_icons.pen"
                                    customClassName="icon stroke"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.post.write}}</span>
                    </router-link>
                    <router-link @click.native="toggleDrawer()" class="item" :to="{name:'posts'}">
                        <simple-svg :src="_icons.article"
                                    customClassName="icon"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.posts}}</span>
                    </router-link>
                    <router-link @click.native="toggleDrawer()" class="item" :to="{name:'comments'}">
                        <simple-svg :src="_icons.comment"
                                    customClassName="icon stroke"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.comments}}</span>
                    </router-link>
                    <router-link @click.native="toggleDrawer()" class="item" :to="{name:'contacts'}">
                        <simple-svg :src="_icons.call"
                                    customClassName="icon stroke"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.contacts}}</span>
                    </router-link>
                    <router-link @click.native="toggleDrawer()" class="item" :to="{name:'pages'}">
                        <simple-svg :src="_icons.page"
                                    customClassName="icon stroke"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.pages}}</span>
                    </router-link>
                    <router-link @click.native="toggleDrawer()" class="item" :to="{name:'users'}">
                        <simple-svg :src="_icons.users"
                                    height="22px"
                                    customClassName="icon"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.users}}</span>
                    </router-link>
                    <router-link @click.native="toggleDrawer()" class="item" :to="{name:'template'}">
                        <simple-svg :src="_icons.image"
                                    height="22px"
                                    customClassName="icon stroke"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.templates}}</span>
                    </router-link>
                    <router-link @click.native="toggleDrawer()" class="item" :to="{name:'profile'}">
                        <simple-svg :src="_icons.profile"
                                    customClassName="icon"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.profile}}</span>
                    </router-link>
                    <router-link @click.native="toggleDrawer()" class="item" :to="{name:'category'}">
                        <simple-svg :src="_icons.category"
                                    customClassName="icon stroke"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.category}}</span>
                    </router-link>
                    <router-link @click.native="toggleDrawer()" class="item" :to="{name:'setting'}">
                        <simple-svg :src="_icons.setting"
                                    customClassName="icon stroke"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.settings}}</span>
                    </router-link>
                    <a class="item" :href="URL.FRONT" target="_blank">
                        <simple-svg :src="_icons.eye"
                                    customClassName="icon stroke"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.view_site}}</span>
                    </a>
                    <div class="item" @click="logoutPanel()">
                        <simple-svg :src="_icons.logout"
                                    customClassName="icon stroke"
                                    fill="#A5B8CE"/>
                        <span class="text">{{LANG.panel.logout}}</span>
                    </div>

                </div>
            </div>
            <div slot='footer' class="drawer-footer">
                <div>
                    <div @click="toggleDrawer()" class="btn btn-simple">{{LANG.panel.close}}</div>
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
            }
        },
        computed: {
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
            logoutPanel()
            {
                this.logout(()=>{
                    this.toggleDrawer();
                })
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
        watch:{
            '$route':function () {
                this.toggleDrawer();
            }
        }
    }
</script>
