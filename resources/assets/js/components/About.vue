<template>
    <section class="section-background section-faq">
        <p class="page-title mb-5">About Us</p>

        <article class="article-modal">
            <h2 class="article-title">Connecting Owners &amp; Captains</h2>
            <h5 class="article-subtitle mb-5">This is the paragraph where you can write more details about the service. Keep
                users
                engaged by providing meaningful information</h5>
            <div class="row align-items-center">
                <div class="col-sm-6 pl-sm-3 pr-0">
                    <h5 class="mb-4">
                        <img :src="URL.base + '/public/images/anchor.png'" />&ensp;Key service highlight here...
                    </h5>
                    <h5 class="mb-4">
                        <img :src="URL.base + '/public/images/anchor.png'" />&ensp;Key service highlight here...
                    </h5>
                    <h5 class="mb-4">
                        <img :src="URL.base + '/public/images/anchor.png'" />&ensp;Key service highlight here...
                    </h5>
                </div>
                <div class="col-sm-6 text-right pr-0">
                    <img :src="URL.base + '/public/images/about.png'" />
                </div>
            </div>
            <br/>
            <br/>
            <div class="text-center">
                <a :href="URL.base + '/register'" class="btn btn-darkblue btn-size-360">Create Free Account</a>
            </div>
        </article>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                URL: window.URL
            }
        },
        mounted() {            
        },

        created() {
        },
    }
</script>
