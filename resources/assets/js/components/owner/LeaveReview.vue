<template>
    <section>
        <article class="article-page container-768">
            <article-close v-bind:link="URL.previous"></article-close>
            <h2 class="article-title mb-3">Leave a Review</h2>
            <h5 class="article-subtitle">ID #{{ tripInfo.tripId }}</h5>
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
            <form class="form-md container-440" :action="URL.base + '/owner-leave-review'" method="POST">
                <div class="row">
                    <div class="col mb-1">
                        <div :class="['alert', 'alert-dismissible', message.status == 'success' ? 'alert-success' : 'alert-danger']" v-if="message">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <div v-for="msg in message.body">
                                {{msg}}
                            </div>                            
                        </div>
                    </div>
                </div>
                <input type="hidden" name="_token" id="_token" :value="token">
                <input type="hidden" name="tripId" id="tripId" :value="tripInfo.tripId">
                <textarea name="describe" v-model="describe" class="form-control mt-3" :placeholder="'How was your trip with '+tripInfo.firstName+'?'" style="height: 290px;"></textarea>
                <br/>
                <p class="font-tahoma text-center mb-4">Rate the trip on a scale of 1-5 (worst-best)</p>
                <fieldset class="starability mx-auto">
                    <input v-model="rating" type="radio" id="rate5" name="rating" value="5" required="required" />
                    <label for="rate5"></label>
                    <input v-model="rating" type="radio" id="rate4" name="rating" value="4" required="required" />
                    <label for="rate4"></label>
                    <input v-model="rating" type="radio" id="rate3" name="rating" value="3" required="required" />
                    <label for="rate3"></label>
                    <input v-model="rating" type="radio" id="rate2" name="rating" value="2" required="required" />
                    <label for="rate2"></label>
                    <input v-model="rating" type="radio" id="rate1" name="rating" value="1" required="required" />
                    <label for="rate1"></label>
                </fieldset>
                <br/>
                <div class="text-center mt-5">
                    <button type="submit" href="#" class="btn btn-darkblue btn-size-360">Submit Review</button>
                </div>
            </form>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['message', 'tripInfo', 'reviewInfo'],

        data() {
            return {
                URL: window.URL,
                token: window.token,
                rating: this.reviewInfo.rating,
                describe: this.reviewInfo.describe
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
