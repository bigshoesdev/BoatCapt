<template>
    <section class="section-background section-login">
        <p class="page-title">Your Account</p>

        <article class="article-modal">
            <h2 class="article-title mb-3">Create Free Account</h2>
            <h5 class="article-subtitle">Already a member?
                <a class="link-underline" :href="URL.base + '/login'">Login</a>
            </h5>
            <form class="form-md form-horizontal" role="form" :action="URL.base + '/register'" method="POST">                
                <input type="hidden" name="_token" id="_token" :value="token">
                <div class="row align-items-end text-center ml-sm-4 mr-sm-3">
                    <div class="col mb-4">
                        <div>
                            <img :src="URL.base + '/public/images/ship.png'"/>
                        </div>
                        <div class="custom-control custom-checkbox my-2">
                            <input type="radio" v-model="param.account_type" required class="custom-control-input" id="check-boat-owner" name="account_type" value="1">
                            <label class="custom-control-label" for="check-boat-owner">Boat Owner</label>
                        </div>
                    </div>
                    <div class="col mb-4">
                        <div>
                            <img :src="URL.base + '/public/images/captain.png'"/>
                        </div>
                        <div class="custom-control custom-checkbox my-2">
                            <input type="radio" v-model="param.account_type" required class="custom-control-input" id="check-uscg-captain" name="account_type" value="0">
                            <label class="custom-control-label" for="check-uscg-captain">USCG Captain</label>
                        </div>
                    </div>
                </div>
                <div :class="['alert', 'alert-dismissible', message.status == 'success' ? 'alert-success' : 'alert-danger']" v-if="message">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <div v-for="msg in message.body">
                        {{msg}}
                    </div>
                </div>
                <div class="form-group">
                    <input v-model="param.name" name="name" type="text" class="form-control form-control-md" required="required" placeholder="Full Name" />
                </div>
                <div class="form-group mt-4">
                    <input v-model="param.email" name="email" type="email" class="form-control form-control-md" required="required" placeholder="Email Address" />
                </div>
                <div class="form-group mt-4">
                    <input name="password" type="password" class="form-control form-control-md" required="required" placeholder="Password" />
                </div>
                <br/>
                <div class="text-center">
                    <button type="submit" class="btn btn-darkblue btn-size-360">Sign Up</button>
                </div>
            </form>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['message', 'param'],

        data() {
            return {
                URL: window.URL,
                token: window.token,
            }
        },
        mounted() {            
        },

        created() {
        },
    }
</script>
