<template>
    <section>
        <article class="article-page container-768">
            <article-close v-bind:link="URL.previous"></article-close>
            <template v-if="isAdmin==0">
                <h2 class="article-title mb-3">{{ tripInfo.isComplete ? 'Completed' : 'Upcoming'}} Trip Detail</h2>
                <h5 class="article-subtitle">ID #{{ tripInfo.tripId }}</h5>
            </template>
            <template v-else>
                <h2 class="article-title mb-3">Trip ID #{{ tripInfo.tripId }} Detail</h2>
                <br/>
            </template>
            <div class="captain-view">
                <div class="captain-face captain-face-220 m-auto">
                    <img v-if="tripInfo.avatar" :src="URL.base + '/public/images/avatars/' + tripInfo.avatar" />
                    <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                </div>
                <div class="aside-left">
                    <template v-if="tripInfo.firstResponder">
                        <p>
                            <img :src="URL.base + '/public/images/responder.png'"/> First Responder
                        </p>
                    </template>
                    <template v-if="tripInfo.maritimeGrad">
                        <p>
                            <img :src="URL.base + '/public/images/grad.png'"/> Maritime Grad
                        </p>
                    </template>                    
                </div>
                <div class="aside-right">
                    <template v-if="tripInfo.militaryVeteran">
                        <p>
                            <img :src="URL.base + '/public/images/veteran.png'"/> Military/Veteran
                        </p>
                    </template>
                    <template v-if="tripInfo.drugFree">
                        <p>
                            <img :src="URL.base + '/public/images/drug.png'"/> Drug Free
                        </p>
                    </template> 
                </div>
            </div>
            <br/>
            <h4 class="article-title">{{ tripInfo.firstName }} {{ tripInfo.lastName }}</h4>
            <div class="text-center mt-2">
                <rating-bar v-bind:param="{'value':tripInfo.rating,'size':'md'}"></rating-bar>
            </div>
            <div class="h22 text-center mt-2">
                <a class="link-underline" :href="URL.base + '/' + tripInfo.captainId + '/captain-bio'">View Bio</a>
            </div>
            <br/>
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
            <div class="row mx-sm-5 mt-4 mb-5" v-if="tripInfo.isComplete == null">
                <div class="col-4">
                    <h4 class="font-rockwell font-weight-bold color-blackblue">Trip Notes:</h4>
                </div>
                <div class="col-8">
                    <div>
                        <h4 class="font-twcenmt" v-br="tripInfo.ownerDescribe">
                        </h4>
                    </div>
                </div>
            </div>
            <template v-else>
                <div class="row mx-sm-5 mt-4">
                    <div class="col-4">
                        <h4 class="font-rockwell font-weight-bold color-blackblue">Capt's Notes:</h4>
                    </div>
                    <div class="col-8">
                        <div>
                            <h4 class="font-twcenmt" v-br="tripInfo.captDescribe">
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="h2 font-rockwell text-center mt-5">
                    <b class="color-blackblue">Trip Total:</b>
                    <span v-sup="tripInfo.total"></span>
                </div>
                <h4 class="text-center font-italic">{{ tripInfo.merchantType == 1 ? 'PayPal' : 'Credit Card' }}, {{ tripInfo.payDate }}</h4>
                <div class="text-center mt-5" v-if="!tripInfo.isComplete && isAdmin==0">
                    <a :href="URL.base + '/' + tripInfo.tripId + '/contact-captain'" class="btn btn-darkblue btn-size-360">Contact Capt. {{ tripInfo.firstName }}</a>
                </div>
                <div class="text-center mt-5" v-if="tripInfo.isComplete && isAdmin==0">
                    <a :href="URL.base + '/' + tripInfo.tripId + '/owner-leave-review'" class="btn btn-darkblue btn-size-360">Leave a Review</a>
                    <br/>
                    <a :href="URL.base + '/' + tripInfo.captainId + '/owner-book-captain'" class="btn btn-darkblue color-reverse btn-size-360 mt-3">Request Another Trip</a>
                </div>
            </template>
            
        </article>
    </section>
</template>

<script>
    export default {

        props: ['tripInfo', 'isAdmin'],

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
