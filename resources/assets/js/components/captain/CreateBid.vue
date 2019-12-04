<template>
    <section class="section-book-captain">
        <article class="article-page container-768">
            <article-close v-bind:link="URL.previous"></article-close>
            <h2 class="article-title mb-3">Create Bid</h2>
            <h5 class="article-subtitle">Trip ID #{{ bidRequest.tripId }}</h5>
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
            <br />
            <br />
            <form class="form-md" :action="URL.base + '/create-bid'" method="POST">
                <input type="hidden" name="_token" id="_token" :value="token">
                <input type="hidden" name="tripId" :value="bidRequest.tripId">
                <div :class="['alert', 'alert-dismissible', message.status == 'success' ? 'alert-success' : 'alert-danger']" v-if="message">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <div v-for="msg in message.body">
                        {{msg}}
                    </div>                            
                </div>
                <h4 class="font-weight-bold mx-4">Do you charge a flat rate or hourly?</h4>
                <div class="div-select mt-3">
                    <select name="chargeType" v-model="chargeType" class="custom-select custom-select-lg" onfocus="(this.style.fontStyle='normal')" onblur="if(!this.value){this.style.fontStyle='italic'}"
                     :style="!chargeType && 'font-style: italic;'" required="required">
                        <option selected="selected" hidden="hidden" disabled="disabled" value="">Choose one...</option>
                        <option value="1">Flat</option>
                        <option value="2">Hourly</option>
                    </select>
                    <button type="button" class="btn btn-pure dropdown-toggle">
                    </button>
                </div>
                <h4 class="font-weight-bold mx-4 mt-5" v-if="chargeType == 2">How much will you hours?</h4>
                <div class="form-group mt-3" v-show="chargeType == 2">
                    <input name="hours" v-model="hours" type="number" class="form-control" required="required" placeholder="Enter hours..." />
                </div>
                <h4 class="font-weight-bold mx-4 mt-5">How much will you charge/per hour?</h4>
                <div class="form-group mt-3">
                    <input name="amount" v-model="amount" type="number" step="0.01" class="form-control" required="required" placeholder="Enter amount..." />
                </div>
                <h4 class="font-weight-bold mx-4 mt-5">Any questions for this owner?</h4>
                <textarea name="describe" v-model="describe" class="form-control fontsize-md mt-3" placeholder="Tell us more about..."></textarea>
                <br />
                <br />
                <h4 class="text-center font-rockwell font-italic mb-2" v-if="chargeType == 2 && amount">{{ hours }} hours x ${{ amount }}/hour</h4>
                <div class="h2 font-rockwell text-center" v-if="chargeType">
                    <b class="color-blackblue">Trip Total:</b>
                    <span v-sup="parseFloat(hours) * parseFloat(amount ? amount : 0) * 100"></span>
                </div>
                <br />
                <br />
                <div class="text-center">
                    <button type="submit" class="btn btn-darkblue btn-size-360">Submit Bid</button>
                </div>
                <br />
            </form>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['message', 'bidRequest', 'oldInput'],

        data() {
            return {
                URL: window.URL,
                token: window.token,
                chargeType: this.oldInput.chargeType,
                hours: this.oldInput.hours,
                amount: this.oldInput.amount,
                describe: this.oldInput.describe
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
