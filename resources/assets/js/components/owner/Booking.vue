<template>
    <section>
        <article class="article-page container-768">
            <article-close v-bind:link="URL.previous"></article-close>
            <h2 class="article-title mb-3">Complete Booking</h2>
            <h5 class="article-subtitle">Trip ID #{{ bidInfo.tripId }}</h5>
            <form class="form-md container-520" :action="URL.base + '/booking-complete'" method="POST">
                <input type="hidden" name="_token" id="_token" :value="token">
                <input type="hidden" name="bidId" id="bidId" :value="bidInfo.id">                
                <div class="ml-5 mr-4">
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
                    <div class="row mb-2">
                        <div class="col-auto" style="padding-left: 2rem;">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="form-check-input custom-control-input" id="radio-visa" v-model="merchant_type" name="merchant_type" value="2" required="required">
                                <label class="form-check-label custom-control-label" for="radio-visa">
                                    &ensp;<img :src="URL.base + '/public/images/credit-cards.png'" alt="VISA" style="margin-top: -3px;" />
                                </label>
                            </div>
                        </div>
                        <div class="col-auto" style="padding-left: 2rem;">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="form-check-input custom-control-input" id="radio-paypal" v-model="merchant_type" name="merchant_type" value="1" required="required">
                                <label class="form-check-label custom-control-label" for="radio-paypal">
                                    &ensp;<img :src="URL.base + '/public/images/paypal.png'" alt="PayPal" />
                                </label>
                            </div>
                        </div>
                    </div>
                    <template v-if="merchant_type == 1">
                        <div class="row">
                            <div class="col">
                                <input name="paypalEmail" v-model="bidInfo.paypalEmail" type="text" class="form-control" required="required" placeholder="PayPal Email Address" />
                            </div>
                        </div>
                    </template>
                    <template v-if="merchant_type == 2">
                        <div class="row">
                            <div class="col">
                                <input name="card_number" v-model="bidInfo.stripeDetail.card_number" type="text" class="form-control" required="required" placeholder="Credit Card Number" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input v-model="exp_date" name="exp_date" type="text" class="form-control min-width-160 exp_date" required="required" placeholder="Expiration date..." />
                            </div>
                            <div class="col">
                                <input name="cc_digits" v-model="bidInfo.stripeDetail.cc_digits" type="text" class="form-control min-width-160" required="required" placeholder="CVV Code" />
                            </div>
                        </div>                        
                    </template>                    
                </div>
                <div class="captain-list">
                    <captain-item v-bind:item="bidInfo.captain" v-bind:param="param"></captain-item>
                </div>

                <div class="h2 font-rockwell text-center mt-5">
                    <b class="color-blackblue">Trip Total:</b>
                    <span v-sup="bidInfo.total"></span>
                </div>
                <br/>
                <div class="text-center mt-1">
                    <button type="submit" class="btn btn-darkblue btn-size-360">Complete Booking</button>
                </div>
                <br/>
            </form>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['message', 'bidInfo', 'oldInput'],

        data() {
            return {
                URL: window.URL,
                token: window.token,
                merchant_type: this.bidInfo.merchant_type,
                exp_date: this.bidInfo.stripeDetail ? this.bidInfo.stripeDetail.exp_date : null,
                param: {
                    link: 'captain-bio',
                    buttonText: 'View Bio'
                }
            }
        },
        mounted() {     
            if(this.oldInput && this.oldInput.merchant_type)
                this.merchant_type = this.oldInput.merchant_type;    

            var vm = this;

            $('.exp_date').datetimepicker({
                format: 'YYYY-MM'
            })
            .on("dp.change", function(e) {
                vm.exp_date = e.target.value;
            })         
        },

        created() {
        },

        updated: function () {
            this.$nextTick(function () {
                var vm = this;

                $('.exp_date').datetimepicker({
                    format: 'YYYY-MM'
                })
                .on("dp.change", function(e) {
                    vm.exp_date = e.target.value;
                })
            })
        }
    }
</script>
