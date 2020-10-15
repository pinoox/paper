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
        }
    },
    methods: {
        hasErrors(){
          return false;
        },
        getErrors(){
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
        _emptyPrint(value, replace = '-') {
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
            this.$router.replace(location).catch(()=>{
            });
        },
        _routerPush(location) {
            this.$router.push(location).catch(()=>{
            });
        },
        _replaceAll(str, find, replace) {
            return str.replace(new RegExp(find.replace(/[.*+\-?^${}()|[\]\\]/g, '\\$&'), 'g'), replace);
        },
        _isNumber: function(evt) {
            evt = (evt) ? evt : window.event;
            let charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        }
    }
});
