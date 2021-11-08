const api_v1 = 'api/panel/v1/';

export default {
    computed: {
        permissions() {
            return [
                {
                    id: 'panel',
                    text: this.LANG.panel.admin_panel,
                    type: 'module',
                    children: [
                        {
                            id: 'panel/dashboard',
                            text: this.LANG.panel.dashboard,
                            api: api_v1 + 'dashboard',
                        },
                        {
                            id: 'panel/user',
                            text: this.LANG.panel.users,
                            api: api_v1 + 'user',
                        },
                        {
                            id: 'panel/group',
                            text: this.LANG.user.groups,
                            api: api_v1 + 'group',
                            children: [
                                {
                                    id: 'panel/permission',
                                    text: this.LANG.panel.permission,
                                    api: api_v1 + 'permission',
                                },
                            ]
                        },
                        {
                            id: 'panel/posts',
                            text: this.LANG.post.posts,
                            api: api_v1 + 'post',
                            children: [
                                {
                                    id: 'panel/write',
                                    text: this.LANG.post.write,
                                    api: api_v1 + 'post/save',
                                },
                                {
                                    id: 'post/stats',
                                    text: this.LANG.panel.post_stats,
                                    api: [
                                        api_v1 + 'post/hasStats',
                                        api_v1 + 'post/getStats',
                                        api_v1 + 'post/getMonthly',
                                    ],
                                },
                                {
                                    id: 'all_posts',
                                    type: 'option',
                                    text: this.LANG.post.access_all_posts,
                                },
                            ]
                        },
                        {
                            id: 'panel/comment',
                            text: this.LANG.comment.comments,
                            api: api_v1 + 'comment',
                        },
                        {
                            id: 'panel/contact',
                            text: this.LANG.contact.contact_title,
                            api: api_v1 + 'contact',
                        },
                        {
                            id: 'panel/pages',
                            text: this.LANG.panel.pages,
                            api: api_v1 + 'page',
                        },
                        {
                            id: 'panel/category',
                            text: this.LANG.panel.category,
                            api: api_v1 + 'category',
                        },
                        {
                            id: 'panel/templates',
                            text: this.LANG.panel.templates,
                            api: api_v1 + 'template',
                        },
                        {
                            id: 'panel/profile',
                            text: this.LANG.panel.profile,
                            api: api_v1 + 'profile',
                        },
                        {
                            id: 'panel/setting',
                            text: this.LANG.panel.settings,
                            api: api_v1 + 'setting',
                        },
                    ]
                }
            ];
        }
    }

};