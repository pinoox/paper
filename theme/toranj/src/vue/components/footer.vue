<template>
    <div class="container">
        <div class="footer">
            <div class="links">
                <router-link v-for="m in generalSetting.menu" :to="URL.BASE + m.link">{{m.label}}</router-link>
            </div>

            <div class="copyright">
                © {{LANG.front.copyright_text1}}
                <a target="_blank" href="https://pinoox.com/">{{LANG.front.pinoox}}</a>
                {{LANG.front.copyright_text2}}
            </div>
        </div>
    </div>
</template>