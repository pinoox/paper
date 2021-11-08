<template>
    <div class="page p-0">
        <header>
            <div class="form-header">
                <div class="title">
                    <div class="text">
                        {{LANG.panel.templates}}
                    </div>
                    <div class="subtext">{{LANG.panel.template_subtext}}</div>
                </div>
            </div>
        </header>
        <simplebar class="simplebar">
            <div class="form-content">
                <div class="templates-list" v-if="templates!=null && templates.length>0">
                    <div class="item" v-for="t in templates">
                        <div class="image">
                            <img :src="t.cover" class="thumb-round">
                        </div>
                        <div class="info">
                            <div class="title">{{t.title}}</div>
                            <div class="meta">
                                <span>{{LANG.panel.developer}}: {{t.developer}}</span>
                                <span>{{LANG.panel.version}}: {{t.version}}</span>
                            </div>
                            <div class="description">{{t.description}}</div>
                        </div>
                        <div class="actions">
                            <div v-if="t.is_enable" class="btn-action enable"><span>{{LANG.panel.active}}</span></div>
                            <div v-else class="btn-action" @click="activate(t)">
                                <i class="fa fa-check"></i>
                            </div>
                            <div v-if="_module('panel/setting')" @click="openTemplateConfig(t)" class="btn-action">
                                <i class="fa fa-cog"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </simplebar>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                templates: [],
            }
        },
        methods: {
            getTemplates() {
                this.$http.post(this.URL.API + 'template/getAll').then((json) => {
                    this.templates = json.data;
                });
            },
            activate(t) {
                this.$http.post(this.URL.API + 'template/activate', {template: t}).then((json) => {
                    if (json.data) {
                        this.templates.forEach((n, i) => {
                            this.templates[i]['is_enable'] = false
                        });
                        t.is_enable = true;
                    }
                });
            },
            openTemplateConfig(template) {
                this.$router.push(PINOOX.URL.BASE + '/theme/' + template.name + '/setting');
            }
        },
        created() {
            this.getTemplates()
        }
    }
</script>