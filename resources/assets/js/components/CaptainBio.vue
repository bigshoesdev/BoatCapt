<template>
    <section class="section-background section-find-captains">
        <p class="page-title mb-5">Nearby Captains</p>

        <article class="article-modal">
            <article-close v-bind:link="URL.base + '/find-captains'"></article-close>
            <h2 class="article-title">Captain {{ captainInfo.fullName }}</h2>            
            <h5 class="article-subtitle">
                <span v-if="captainInfo.city && captainInfo.state">{{captainInfo.city}}, {{captainInfo.state}}</span>
            </h5>
            <div class="captain-view">
                <div class="captain-face captain-face-220 m-auto">
                    <img v-if="captainInfo.avatar" :src="URL.base + '/public/images/avatars/' + captainInfo.avatar" />
                    <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                </div>
                <div class="aside-left">
                    <template v-if="captainInfo.firstResponder">
                        <p>
                            <img :src="URL.base + '/public/images/responder.png'"/> First Responder
                        </p>
                    </template>
                    <template v-if="captainInfo.maritimeGrad">
                        <p>
                            <img :src="URL.base + '/public/images/grad.png'"/> Maritime Grad
                        </p>
                    </template>                    
                </div>
                <div class="aside-right">
                    <template v-if="captainInfo.militaryVeteran">
                        <p>
                            <img :src="URL.base + '/public/images/veteran.png'"/> Military/Veteran
                        </p>
                    </template>
                    <template v-if="captainInfo.drugFree">
                        <p>
                            <img :src="URL.base + '/public/images/drug.png'"/> Drug Free
                        </p>
                    </template> 
                </div>
            </div>
            <br/>
            <div class="text-center mt-2">
                <rating-bar v-bind:param="{'value':captainInfo.rating,'size':'md'}"></rating-bar>
            </div>
            <div class="h22 text-center mt-2">
                <a class="link-underline" :href="URL.base + '/' + captainInfo.id + '/captain-bio-reviews'">{{ captainInfo.reviews | filterComma }} Reviews</a>
            </div>
            <h4 class="article-title text-left mt-5 mb-4">About Capt. {{ captainInfo.firstName }}</h4>
            <h4 class="captain-detail text-read-more mb-3" data-index="240" v-readmore="captainInfo.describe">
            </h4>
            <br/>
            <h4 class="article-title text-left mt-4 mb-4">Boating Experience</h4>
            <div class="disc-list">
                <h4>50 Ton Master License</h4>
                <h4>USCG licensed since 2008</h4>
                <h4>Powerboat &amp; Sailboat</h4>
                <h4>Powerboat captain</h4>
                <h4>Sailboat captain</h4>
            </div>
            <br/>
            <br/>
            <div class="text-center" v-if="hire == 0">
                <a :href="URL.base + '/login'" class="btn btn-darkblue btn-size-360">Login to Book Capt. {{ captainInfo.firstName }}</a>
            </div>
            <div class="text-center" v-if="hire == 1">
                <a :href="URL.base + '/' + captainInfo.id + '/owner-book-captain'" class="btn btn-darkblue btn-size-360">Hire Capt. {{ captainInfo.firstName }}</a>
            </div>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['hire', 'captainInfo'],

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
