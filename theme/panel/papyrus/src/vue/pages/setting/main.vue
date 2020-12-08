<template>
    <div class="form-wrapper">
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        props: ['theme_name'],
        created() {
            this.initHttp();

            if (!this.views || this.views.length <= 0)
                this.getViews();
        },
        data() {
            return {
                params: {},
                http: null,
                isTheme: false,
                viewsTheme: [],
            }
        },
        computed: {
            views: {
                get() {
                    return this.isTheme ? this.viewsTheme : this.viewSettings;
                },
                set(val) {
                    if (this.isTheme)
                        this.viewsTheme = val;
                    else
                        this.viewSettings = val;
                }
            }
        },
        methods: {
            initHttp() {
                this.isTheme = !!this.theme_name;
                this.http = this.$http.create({
                    baseURL: this.URL.API + 'setting/',
                });

                this.http.defaults.headers.common['theme_name'] = this.isTheme ? this.theme_name : '~';
                this.http.defaults.headers.common['Authorization'] = this.tokenAuth();

            },
            getViews(lang = '') {
                this.http.get('getViews/' + lang).then((json) => {
                    this.views = !!json.data ? json.data : {};
                });
            }
        }
    }
</script>