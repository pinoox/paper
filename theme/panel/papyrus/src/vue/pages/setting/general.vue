<template>
    <div class="form-wrapper">
        <header>
            <div class="form-header">
                <div class="title">
                    <div class="text">
                        {{LANG.panel.general_settings}}
                    </div>
                </div>
            </div>
        </header>
        <div class="form-content" @keyup.enter="save()">
            <row :gutter="12" :columns="4">

                <column :sm="3" :lg="2">
                    <div class="input-wrapper">
                        <label class="input-label">{{LANG.panel.site_title}}</label>
                        <div class="input-group">
                            <input v-model="params.site_title" type="text"
                                   :placeholder="LANG.panel.site_title" class="input">
                        </div>
                    </div>
                    <div class="input-wrapper">
                        <label class="input-label">{{LANG.panel.site_description}}</label>
                        <div class="input-group">
                            <textarea class="input" v-model="params.site_description"
                                      :placeholder="LANG.panel.site_description" rows="5"></textarea>
                        </div>
                    </div>
                </column>
            </row>
        </div>
        <div class="footer-space"></div>
        <footer>
            <div class="form-footer">
                <router-link :to="{name:'setting'}" class="btn btn-simple">{{LANG.post.close}}</router-link>
                <div class="btn btn-primary" @click="save()">{{LANG.panel.save}}</div>
            </div>
        </footer>
    </div>
</template>

<script>
    export default {
        created(){
            this.getSettings()
        },
        data() {
            return {
                params: {
                    site_title: null,
                    site_description: null,
                }
            }
        },
        methods: {
            getSettings() {
                this.$http.get(this.URL.API + 'setting/get/general').then((json) => {
                    let data = !!json.body ? json.body : [];
                    this.params = {
                        ...this.params,
                        ...data,
                    };
                });
            },
            save() {
                this.$http.post(this.URL.API + 'setting/save/general').then((json) => {
                    this._statusResponse(json.data);
                });
            }
        }
    }
</script>