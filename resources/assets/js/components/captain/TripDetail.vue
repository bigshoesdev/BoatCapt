<template>
    <section>
        <article class="article-page container-768">
            <article-close v-bind:link="URL.previous"></article-close>
            <h2 class="article-title mb-3">Trip Detail</h2>
            <h5 class="article-subtitle">ID #{{ tripInfo.tripId }}</h5>
            <div class="captain-view">
                <div class="captain-face captain-face-220 m-auto">
                    <img v-if="tripInfo.avatar" :src="URL.base + '/public/images/avatars/' + tripInfo.avatar" />
                    <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                </div>
                <div class="aside-left">
                    <template v-if="tripInfo.towCoverage">
                        <p>
                            <img :src="URL.base + '/public/images/grad.png'" /> Tow Coverage
                        </p>
                    </template>                    
                </div>
                <div class="aside-right">
                    <template v-if="tripInfo.boatInsurance">
                        <p>
                            <img :src="URL.base + '/public/images/veteran.png'" /> Boat Insurance
                        </p>
                    </template>
                    <template v-if="tripInfo.validSafetyGear">
                        <p>
                            <img :src="URL.base + '/public/images/drug.png'" /> Valid Safety Gear
                        </p>
                    </template>                    
                </div>
            </div>
            <br/>
            <h4 class="article-title">{{ tripInfo.firstName }} {{ tripInfo.lastName }}</h4>
            <div class="text-center mt-2">
                <rating-bar v-bind:param="{'value':tripInfo.rating,'size':'md'}"></rating-bar>
            </div>
            <!-- <h5 class="article-subtitle font-italic">{{ tripInfo.city }}, {{ tripInfo.state }}</h5> -->
            <br/>
            <div class="row mx-sm-5">
                <div class="col-4">
                    <h4 class="font-rockwell font-weight-bold color-blackblue">Trip Starts:</h4>
                </div>
                <div class="col-8">
                    <h4 class="font-twcenmt">
                        {{ tripInfo.startLocation }}
                        <br/>
                        {{ tripInfo.startTime }}
                    </h4>
                </div>
            </div>
            <div class="row mx-sm-5 mt-4">
                <div class="col-4">
                    <h4 class="font-rockwell font-weight-bold color-blackblue">Trip Ends:</h4>
                </div>
                <div class="col-8">
                    <h4 class="font-twcenmt">
                        {{ tripInfo.endLocation }}
                        <br/>
                        {{ tripInfo.endTime }}
                    </h4>
                </div>
            </div>
            <div class="row mx-sm-5 mt-4">
                <div class="col-4">
                    <h4 class="font-rockwell font-weight-bold color-blackblue">Capt's Notes:</h4>
                </div>
                <div class="col-8">
                    <h4 v-br="tripInfo.captDescribe"></h4>
                </div>
            </div>
            <div class="h2 font-rockwell text-center mt-5">
                <b class="color-blackblue">Trip Total:</b>
                <span v-sup="tripInfo.total"></span>
            </div>            
            <template v-if="tripInfo.isComplete == 0">
                <h4 class="text-center font-italic">{{ tripInfo.merchantType == 1 ? 'PayPal' : 'Credit Card' }}, {{ tripInfo.payDate }}</h4>
                <div class="text-center mt-5">
                    <a :href="URL.base + '/' + tripInfo.tripId + '/contact-owner'" class="btn btn-darkblue btn-size-360">Contact Owner</a>
                </div>
            </template>
            <div class="text-center" v-if="tripInfo.isComplete == 1">
                <h4 class="text-center font-italic">{{ tripInfo.merchantType == 1 ? 'PayPal' : 'Credit Card' }}, {{ tripInfo.payDate }}</h4>
                <a :href="URL.base + '/' + tripInfo.tripId + '/captain-leave-review'" class="btn btn-darkblue btn-size-360 mt-5">Leave a Review</a>
            </div>       
        </article>
    </section>
</template>

<script>
    export default {

        props: ['tripInfo'],

        data() {
            return {
                URL: window.URL
            }
        },
        mounted() {           
        },

        created() {
        },

        methods: {
        }
    }
</script>
