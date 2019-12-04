<template>
    <section>
        <article class="article-page">
            <h2 class="article-title mb-3">Request Sent!</h2>
            <h5 class="h22 article-subtitle container-520 text-center">Captain {{ captainInfo.firstName }} has been notified of your interest and will reply to your request within 48 hours.</h5>
            <div class="captain-face captain-face-220 m-auto">
                <img v-if="captainInfo.avatar" :src="URL.base + '/public/images/avatars/' + captainInfo.avatar" />
                <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
            </div>
            <h3 class="article-title mt-5 mb-3">Captain {{ captainInfo.fullName }}</h3>
            <h4 class="article-subtitle font-italic mb-3">{{ captainInfo.city }}, {{ captainInfo.state }}</h4>
            <div class="text-center">
                <rating-bar v-bind:param="{'value':captainInfo.rating}"></rating-bar>
            </div>
            <h4 class="text-center mt-1">
                <a class="link-underline" :href="URL.base + '/' + captainInfo.id + '/captain-bio-reviews'">{{ captainInfo.reviews  | filterComma }} Reviews</a>
            </h4>
            <br/>
            <br/>
            <div class="text-center mt-3 mb-4">
                <a :href="URL.base + '/find-captains'" class="btn btn-darkblue btn-size-360">Find More Captains</a>
            </div>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['captainInfo'],

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
