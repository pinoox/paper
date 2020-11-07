<template>
    <div class="form-wrapper">
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        created()
        {
            if(!this.viewSettings || this.viewSettings.length <= 0)
                this.getViews();
        },
        data(){
          return {
              params:{},
          }
        },
        methods: {
            getViews() {
                this.$http.get(this.URL.API + 'setting/getViews/').then((json) => {
                    this.viewSettings = !!json.data ? json.data : {};
                });
            }
        }
    }
</script>