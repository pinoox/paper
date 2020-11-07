<template>
   <div>
       <div class="sidebar">
           <div class="sidebar-content">
               <div class="brand">
                   <div class="title">PAPER</div>
                   <div class="subtitle"></div>
               </div>
               <div class="nav">
                   <router-link :to="{name:'dashboard'}" class="item" exact-active-class="active">
                       <simple-svg :src="_icons.dashboard"
                                   customClassName="icon"
                                   fill="#A5B8CE"/>
                       <span class="text">{{LANG.panel.dashboard}}</span>
                   </router-link>
                   <router-link class="item" :to="{name:'write'}" exact-active-class="active">
                       <simple-svg :src="_icons.pen"
                                   customClassName="icon stroke"/>
                       <span class="text">{{LANG.post.write}}</span>
                   </router-link>
                   <router-link class="item" :to="{name:'posts'}" exact-active-class="active">
                       <simple-svg :src="_icons.article"
                                   customClassName="icon"/>
                       <span class="text">{{LANG.panel.posts}}</span>
                   </router-link>
               </div>
               <div class="menu">
                   <router-link :to="{name:'profile'}" tag="div" class="item">
                       <span class="text">{{LANG.panel.profile}}</span>
                   </router-link>
                   <router-link :to="{name:'users'}" tag="div" class="item">
                       <span class="text">{{LANG.panel.users}}</span>
                   </router-link>
                   <router-link :to="{name:'setting'}" tag="div" class="item">
                       <span class="text">{{LANG.panel.settings}}</span>
                   </router-link>
                   <div class="item" @click="logout()">
                       <span class="text">{{LANG.panel.logout}}</span>
                   </div>
               </div>
           </div>
       </div>
   </div>
</template>

<script>
    export default {
        methods:{
            logout() {
                this._confirm(PINOOX.LANG.panel.are_you_sure_logout_account, () => {
                    this.$http.get(this.URL.API + 'user/logout').then((json) => {
                        if (json.data.status) {
                            this.USER.user = {isLogin: false};
                            this.$router.replace({name:'login'});
                        }
                    });
                });

            }
        }
    }
</script>