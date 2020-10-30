<template>
    <div class="form-wrapper">
        <div class="page">
            <div class="menubar">
                <div class="items">
                    <router-link :to="{name:'write'}" tag="div" class="item">
                        {{LANG.post.write}}
                    </router-link>
                </div>
            </div>
            <header>
                <div class="form-header">
                    <div class="title">
                        <div class="text">
                            {{LANG.user.add_user}}
                        </div>
                    </div>
                </div>
            </header>
            <div class="form-content" @keyup.enter="save()">
                <row :gutter="12" :columns="2">

                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.fname}}</label>
                            <div class="input-group">
                                <input v-model="params.fname" type="text"
                                       :placeholder="LANG.user.fname" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.lname}}</label>
                            <div class="input-group">
                                <input v-model="params.lname" type="text"
                                       :placeholder="LANG.user.lname" class="input">
                            </div>
                        </div>
                    </column>
                </row>
                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.username}}</label>
                            <div class="input-group">
                                <input v-model="params.username" type="text"
                                       :placeholder="LANG.user.username" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.email}}</label>
                            <div class="input-group">
                                <input v-model="params.email" type="text"
                                       :placeholder="LANG.user.email" class="input">
                            </div>
                        </div>
                    </column>
                </row>
                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.password}}</label>
                            <div class="input-group">
                                <input v-model="params.password" type="password"
                                       :placeholder="LANG.user.password" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.re_password}}</label>
                            <div class="input-group">
                                <input v-model="params.re_password" type="password"
                                       :placeholder="LANG.user.re_password" class="input">
                            </div>
                        </div>
                    </column>
                </row>
                <row :gutter="12" :columns="2">

                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.fname}}</label>
                            <div class="input-group">
                                <input v-model="params.fname" type="text"
                                       :placeholder="LANG.user.fname" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.lname}}</label>
                            <div class="input-group">
                                <input v-model="params.lname" type="text"
                                       :placeholder="LANG.user.lname" class="input">
                            </div>
                        </div>
                    </column>
                </row>
                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.username}}</label>
                            <div class="input-group">
                                <input v-model="params.username" type="text"
                                       :placeholder="LANG.user.username" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.email}}</label>
                            <div class="input-group">
                                <input v-model="params.email" type="text"
                                       :placeholder="LANG.user.email" class="input">
                            </div>
                        </div>
                    </column>
                </row>
                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.password}}</label>
                            <div class="input-group">
                                <input v-model="params.password" type="password"
                                       :placeholder="LANG.user.password" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.re_password}}</label>
                            <div class="input-group">
                                <input v-model="params.re_password" type="password"
                                       :placeholder="LANG.user.re_password" class="input">
                            </div>
                        </div>
                    </column>
                </row>
                <row :gutter="12" :columns="2">

                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.fname}}</label>
                            <div class="input-group">
                                <input v-model="params.fname" type="text"
                                       :placeholder="LANG.user.fname" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.lname}}</label>
                            <div class="input-group">
                                <input v-model="params.lname" type="text"
                                       :placeholder="LANG.user.lname" class="input">
                            </div>
                        </div>
                    </column>
                </row>
                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.username}}</label>
                            <div class="input-group">
                                <input v-model="params.username" type="text"
                                       :placeholder="LANG.user.username" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.email}}</label>
                            <div class="input-group">
                                <input v-model="params.email" type="text"
                                       :placeholder="LANG.user.email" class="input">
                            </div>
                        </div>
                    </column>
                </row>
                <row :gutter="12" :columns="2">
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.password}}</label>
                            <div class="input-group">
                                <input v-model="params.password" type="password"
                                       :placeholder="LANG.user.password" class="input">
                            </div>
                        </div>
                    </column>
                    <column :sm="2" :md="1">
                        <div class="input-wrapper">
                            <label class="input-label">{{LANG.user.re_password}}</label>
                            <div class="input-group">
                                <input v-model="params.re_password" type="password"
                                       :placeholder="LANG.user.re_password" class="input">
                            </div>
                        </div>
                    </column>
                </row>

            </div>
            <div class="footer-space"></div>
            <footer>
                <div class="form-footer">
                    <div class="btn btn-simple">{{LANG.panel.close}}</div>
                    <div class="btn btn-primary" @click="save()">{{LANG.panel.save}}</div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        name: "profile",
        data() {
            return {
                params: {}
            }
        },
        methods: {
            save() {

            }
        }
    }
</script>