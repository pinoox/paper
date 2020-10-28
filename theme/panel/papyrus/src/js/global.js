import Vue from "vue";

Vue.mixin({
    computed: {
        isLogin() {
            let user = this.$store.state.user;
            return !!user && !!user.isLogin;
        },
        USER: {
            get() {
                return this.$store.state.user;
            },
            set(val) {
                this.$store.state.user = val;
            }
        },
        ckEditor: {
            get() {
                return this.$store.state.ckEditor;
            },
            set(val) {
                this.$store.state.ckEditor = val;
            }
        },
        LANG: {
            get() {
                return PINOOX.LANG;
            },
        },
        URL: {
            get() {
                return PINOOX.URL;
            },
        },
        _dir() {
            return  !!PINOOX.LANG.paper.direction ? PINOOX.LANG.paper.direction : 'ltr';
        },
        _isLoading: {
            set(val) {
                this.$store.state.isLoading = val;
            },
            get() {
                return this.$store.state.isLoading;
            }
        },
        offLoading() {
            return {
                params: {
                    isLoading: false,
                }
            }
        },
        _icons() {
            return {
                dashboard: require(`@img/svg/ic_dashboard.svg`),
                article: require(`@img/svg/ic_article.svg`),
                stats: require(`@img/svg/ic_stats.svg`),
                setting: require(`@img/svg/ic_setting.svg`),
                users: require(`@img/svg/ic_users.svg`),
                profile: require(`@img/svg/ic_profile.svg`),
                eye: require(`@img/svg/ic_eye.svg`),
                pen: require(`@img/svg/ic_pen_square.svg`),
                delete: require(`@img/svg/ic_delete.svg`),
                publish: require(`@img/svg/ic_publish.svg`),
                seo: require(`@img/svg/ic_seo.svg`),
                category: require(`@img/svg/ic_category.svg`),
                more: require(`@img/svg/ic_more.svg`),
                zoomIn: require(`@img/svg/ic_zoom_in.svg`),
                zoomOut: require(`@img/svg/ic_zoom_out.svg`),
                close: require(`@img/svg/ic_close.svg`),
                save: require(`@img/svg/ic_save.svg`),
                first_post : require(`@img/svg/first_post.svg`),
                comment : require(`@img/svg/ic_comment.svg`),
                call : require(`@img/svg/ic_call.svg`),
                placeholder : require(`@img/placeholder.png`),
        };
        },
    },
    methods: {
        tokenAuth() {
            let token = localStorage.pinoox_user;
            if (!!token) {
                return `${token}`;
            }
            return null;
        },
        _delay: (function () {
            let timer = 0;
            return function (callback, ms) {
                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })(),
        _notify(type, text, group = 'app') {
            this.$notify({
                group: group,
                type: type,
                text: text,
                duration: 5000,
            });
        },
        _messageResponse(json) {
            if (json.status) {
                this._notify('success', json.message, 'app');
                return true;
            } else {
                this._notify('error', json.message, 'app');
                return false;
            }
        },
        _statusResponse(json) {
            if (json.status) {
                this._notify('success', json.result, 'app');
                return true;
            } else {
                this._notify('error', json.result, 'app');
                return false;
            }
        },
        _isNull(value, replace = '-') {
            return !!value ? value : replace;
        },
        _confirm(message, func, isLoader = false) {
            this.$dialog.confirm({
                title: this.LANG.panel.warning,
                body: message,
            }, {
                reverse: true,
                loader: isLoader,
                okText: this.LANG.panel.yes,
                cancelText: this.LANG.panel.no,
                customClass: 'dialog-custom',
            }).then(func);
        },
        _clone($obj) {
            return JSON.parse(JSON.stringify($obj));
        },
        _resetInitialData(key = null) {
            if (key !== null)
                this.$data[key] = this.$options.data()[key];
            else
                Object.assign(this.$data, this.$options.data());

        },
        _empty(data) {
            return !(data !== undefined && data !== null && data.length > 0);
        },
        _routerReplace(location) {
            this.$router.replace(location).catch(() => {
            });
        },
        _routerPush(location) {
            this.$router.push(location).catch(() => {
            });
        },
        _replaceAll(str, find, replace) {
            return str.replace(new RegExp(find.replace(/[.*+\-?^${}()|[\]\\]/g, '\\$&'), 'g'), replace);
        },
        _isNumber: function (evt) {
            evt = (evt) ? evt : window.event;
            let charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        },
        _timeNow() {
            let time = new Date().toLocaleTimeString();
            let parts = time.split(' ');
            return parts[0] + ' ' + this.LANG.panel[parts[1]];
        }
    }
});
