<template>
    <section>
        <article class="article-page container-768">
            <article-close v-bind:link="URL.previous"></article-close>
            <h2 class="article-title mb-3">Bid Request Detail</h2>
            <h5 class="article-subtitle">ID #{{ bidRequest.tripId }}</h5>
            <div class="captain-view">
                <div class="captain-face captain-face-220 m-auto">
                    <img v-if="bidRequest.avatar" :src="URL.base + '/public/images/avatars/' + bidRequest.avatar" />
                    <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                </div>
                <div class="aside-left">
                    <template v-if="bidRequest.towCoverage">
                        <p>
                            <img :src="URL.base + '/public/images/grad.png'" /> Tow Coverage
                        </p>
                    </template>                    
                </div>
                <div class="aside-right">
                    <template v-if="bidRequest.boatInsurance">
                        <p>
                            <img :src="URL.base + '/public/images/veteran.png'" /> Boat Insurance
                        </p>
                    </template>
                    <template v-if="bidRequest.validSafetyGear">
                        <p>
                            <img :src="URL.base + '/public/images/drug.png'" /> Valid Safety Gear
                        </p>
                    </template>                    
                </div>
            </div>
            <br/>
            <h4 class="article-title">{{ bidRequest.firstName }} {{ bidRequest.lastName }}</h4>
            <div class="text-center mt-2">
                <rating-bar v-bind:param="{'value':bidRequest.rating,'size':'md'}"></rating-bar>
            </div>
            <br/>
            <div class="row mx-sm-5">
                <div class="col-4">
                    <h4 class="font-rockwell font-weight-bold color-blackblue">Trip Starts:</h4>
                </div>
                <div class="col-8">
                    <h4 class="font-twcenmt">
                        {{ bidRequest.startLocation }}
                        <br/>
                        {{ bidRequest.startTime }}
                    </h4>
                </div>
            </div>
            <div class="row mx-sm-5 mt-4">
                <div class="col-4">
                    <h4 class="font-rockwell font-weight-bold color-blackblue">Trip Ends:</h4>
                </div>
                <div class="col-8">
                    <h4 class="font-twcenmt">
                        {{ bidRequest.endLocation }}
                        <br/>
                        {{ bidRequest.endTime }}
                    </h4>
                </div>
            </div>
            <div class="row mx-sm-5 mt-4">
                <div class="col-4">
                    <h4 class="font-rockwell font-weight-bold color-blackblue">Trip Notes:</h4>
                </div>
                <div class="col-8">
                    <h4 v-br="bidRequest.describe"></h4>
                </div>
            </div>
            <div class="text-center mt-5">
                <a :href="URL.base + '/' + bidRequest.tripId + '/create-bid'" class="btn btn-darkblue btn-size-360">Create Bid</a>
            </div>
            <h4 class="text-center mt-5">
                <a :href="URL.base + '/' + bidRequest.tripId + '/pass-trip'" class="link-underline text-danger">Pass on the Trip</a>
            </h4>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['bidRequest'],

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
