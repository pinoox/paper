<template>
    <div class="form-wrapper">
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        props: ['theme_name'],
        created() {
            this.isTheme = !!this.theme_name;

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
            },
            addThemeToHeader()
            {
                let theme = this.isTheme ? this.theme_name : '~';
                return  {
                    headers: {
                        "theme_name" : `${theme}`,
                    }
                };
            },
        },
        methods: {
            getViews(lang = '') {
                this.$http.get(this.URL.API+'setting/getViews/' + lang,this.addThemeToHeader).then((json) => {
                    this.views = !!json.data ? json.data : {};
                });
            }
        }
    }
</script>