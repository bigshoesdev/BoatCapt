<template>
    <section>
        <article class="article-page container-768">
            <article-close v-bind:link="URL.previous"></article-close>
            <h2 class="article-title mb-3">Bid Details</h2>
            <h5 class="article-subtitle">ID #{{ bidInfo.tripId }}</h5>
            <div class="captain-view">
                <div class="captain-face captain-face-220 m-auto">
                    <img v-if="bidInfo.avatar" :src="URL.base + '/public/images/avatars/' + bidInfo.avatar" />
                    <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                </div>
                <div class="aside-left">
                    <template v-if="bidInfo.firstResponder">
                        <p>
                            <img :src="URL.base + '/public/images/responder.png'"/> First Responder
                        </p>
                    </template>
                    <template v-if="bidInfo.maritimeGrad">
                        <p>
                            <img :src="URL.base + '/public/images/grad.png'"/> Maritime Grad
                        </p>
                    </template>                    
                </div>
                <div class="aside-right">
                    <template v-if="bidInfo.militaryVeteran">
                        <p>
                            <img :src="URL.base + '/public/images/veteran.png'"/> Military/Veteran
                        </p>
                    </template>
                    <template v-if="bidInfo.drugFree">
                        <p>
                            <img :src="URL.base + '/public/images/drug.png'"/> Drug Free
                        </p>
                    </template> 
                </div>
            </div>
            <br/>
            <h4 class="article-title">{{ bidInfo.firstName }} {{ bidInfo.lastName }}</h4>
            <div class="text-center mt-2">
                <rating-bar v-bind:param="{'value':bidInfo.rating,'size':'md'}"></rating-bar>
            </div>
            <div class="h22 text-center mt-2">
                <a class="link-underline" :href="URL.base + '/' + bidInfo.captainId + '/captain-bio'">View Bio</a>
            </div>
            <br/>
            <br/>
            <div class="row mx-sm-5">
                <div class="col-4">
                    <h4 class="font-rockwell font-weight-bold color-blackblue">Trip Starts:</h4>
                </div>
                <div class="col-8">
                    <h4 class="font-twcenmt">
                        {{ bidInfo.startLocation }}
                        <br/>
                        {{ bidInfo.startTime }}
                    </h4>
                </div>
            </div>
            <div class="row mx-sm-5 mt-4">
                <div class="col-4">
                    <h4 class="font-rockwell font-weight-bold color-blackblue">Trip Ends:</h4>
                </div>
                <div class="col-8">
                    <h4 class="font-twcenmt">
                        {{ bidInfo.endLocation }}
                        <br/>
                        {{ bidInfo.endTime }}
                    </h4>
                </div>
            </div>
            <div class="row mx-sm-5 mt-4">
                <div class="col-4">
                    <h4 class="font-rockwell font-weight-bold color-blackblue">Capt's Notes:</h4>
                </div>
                <div class="col-8">
                    <div>
                        <h4 class="font-twcenmt" v-br="bidInfo.captDescribe">
                        </h4>
                    </div>
                </div>
            </div>
            <div class="h2 font-rockwell text-center mt-5">
                <b class="color-blackblue">Trip Total:</b>
                <span v-sup="bidInfo.total"></span>
            </div>
            <div class="text-center mt-5">
                <a :href="URL.base + '/' + bidInfo.id + '/owner-booking'" class="btn btn-darkblue btn-size-360">Accept Bid</a>
            </div>
            <h4 class="text-center mt-4 mb-0">
                <a :href="URL.base + '/' + bidInfo.id + '/reject-bid'" class="link-underline text-danger">Reject Bid</a>
            </h4>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['bidInfo'],

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
