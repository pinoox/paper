export default {
    computed: {
        permissions() {
            return [
                {
                    "id": 'panel',
                    "text": this.LANG.panel.admin_panel,
                    "type": 'module',
                    "children": [
                        {
                            "id": 'dashboard',
                            "text": this.LANG.panel.dashboard,
                        },
                        {
                            "id": 'user',
                            "text":  this.LANG.panel.users,
                        },
                        {
                            "id": 'group',
                            "text": this.LANG.user.groups,
                            "children": [
                                {
                                    "id": 'permission',
                                    "text": this.LANG.panel.permission,
                                },
                            ]
                        },
                        {
                            "id": 'posts',
                            "text": this.LANG.post.posts,
                            "children": [
                                {
                                    "id": 'write',
                                    "text": this.LANG.post.write,
                                },
                                {
                                    "id": 'post/stats',
                                    "text": this.LANG.panel.post_stats,
                                },
                                {
                                    "id": 'all_posts',
                                    "type": 'option',
                                    "text": this.LANG.post.access_all_posts,
                                },
                            ]
                        },
                        {
                            "id": 'comment',
                            "text": this.LANG.comment.comments,
                        },
                        {
                            "id": 'contact',
                            "text": this.LANG.contact.contact_title,
                        },
                        {
                            "id": 'page',
                            "text":  this.LANG.panel.pages,
                        },
                        {
                            "id": 'category',
                            "text":  this.LANG.panel.category,
                        },
                        {
                            "id": 'templates',
                            "text":  this.LANG.panel.templates,
                        },
                        {
                            "id": 'profile',
                            "text":  this.LANG.panel.profile,
                        },
                        {
                            "id": 'setting',
                            "text":  this.LANG.panel.settings,
                        },
                    ]
                }
            ];
        }
    }

};