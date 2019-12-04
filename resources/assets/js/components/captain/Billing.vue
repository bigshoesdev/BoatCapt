<template>
    <section>
        <article class="article-page container-768">
            <h2 class="article-title mb-3">{{userName}}’s Billing Profile</h2>
            <h5 class="article-subtitle">
                <span v-if="profile.city && profile.state">{{profile.city}}, {{profile.state}} |</span>
                <a :href="URL.base + (captainId?'/'+captainId+'/admin-captain-profile':'/captain-profile')" class="link-underline">Account Profile</a>
            </h5>
            <div class="captain-view">
                <div class="captain-face captain-face-220 m-auto">
                    <img v-if="profile.avatar" :src="URL.base + '/public/images/avatars/' + profile.avatar" />
                    <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                </div>
                <div class="aside-left">
                    <template v-if="profile.firstResponder">
                        <p>
                            <img :src="URL.base + '/public/images/responder.png'"/> First Responder
                        </p>
                    </template>  
                    <template v-if="profile.maritimeGrad">       
                        <p>
                            <img :src="URL.base + '/public/images/grad.png'"/> Maritime Grad
                        </p>
                    </template> 
                </div>
                <div class="aside-right">
                    <template v-if="profile.militaryVeteran">
                        <p>
                            <img :src="URL.base + '/public/images/veteran.png'"/> Military/Veteran
                        </p>
                    </template> 
                    <template v-if="profile.drugFree">
                        <p>
                            <img :src="URL.base + '/public/images/drug.png'"/> Drug Free
                        </p>
                    </template> 
                </div>
            </div>
            <br />
            <div class="text-center mt-2" v-if="profile.rating">
                <rating-bar v-bind:param="{'value':profile.rating,'size':'md'}"></rating-bar>
            </div>
            <div class="h22 text-center mt-2">
                <a class="link-underline" :href="URL.base + (captainId?'/'+captainId+'/admin-captain-reviews':'/captain-reviews')">{{ profile.reviews | filterComma }} Reviews</a>
            </div>
            <br/>
            <br/>
            <form class="form-md container-440" :action="URL.base + (captainId?'/'+captainId+'/admin-captain-billing':'/captain-billing')" method="POST">
                <input type="hidden" name="_token" id="_token" :value="token">
                <h4 class="article-title">Billing Profile</h4>                
                <h5 class="article-subtitle font-italic">
                    <span v-if="profile.merchant_type == 1">Getting paid via PayPal</span>
                    <span v-if="profile.merchant_type == 2">Paying via card</span>
                </h5>
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
                <div class="row">
                    <div class="col">
                        <div class="div-select">
                            <select v-model="profile.merchant_type" name="merchant_type" class="custom-select custom-select-lg">
                                <option selected="selected" hidden="hidden" disabled="disabled" value="null">Choose payment method...</option>
                                <option value="1">PayPal</option>
                                <option value="2">Credit Card</option>
                            </select>
                            <button type="button" class="btn btn-pure dropdown-toggle">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="profile.merchant_type == 1">
                    <div class="col">
                        <input v-model="profile.paypalEmail" name="paypalEmail" type="email" class="form-control" required="required" placeholder="PayPal email address..." />
                    </div>
                </div>
                <template v-if="profile.merchant_type == 2">
                    <div class="row">
                        <div class="col">
                            <input v-model="profile.stripeDetail.card_number" name="card_number" type="text" class="form-control" required="required" placeholder="Credit card number..." />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input v-model="exp_date" name="exp_date" type="text" class="form-control min-width-160 exp_date" required="required" placeholder="Expiration date..." />
                        </div>
                        <div class="col">
                            <input v-model="profile.stripeDetail.cc_digits" name="cc_digits" type="text" class="form-control min-width-160" required="required" placeholder="CVV Code..." />
                        </div>
                    </div>
                </template>   
                <br/>
                <div class="text-center mt-1">
                    <button type="submit" class="btn btn-darkblue btn-size-360">Update Profile</button>
                </div>
            </form>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['message', 'userInfo', 'userName', 'oldInput', 'captainId'],

        data() {
            return {
                URL: window.URL,
                token: window.token,
                profile: $.extend(true, {}, this.userInfo),
                exp_date: this.userInfo.stripeDetail ? this.userInfo.stripeDetail.exp_date : null
            }
        },
        mounted() {  
            if(this.oldInput && this.oldInput.merchant_type)
                this.profile.merchant_type = this.oldInput.merchant_type;      
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

        methods: {
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
