<template>
    <section class="section-background section-login">
        <p class="page-title">Your Account</p>

        <article class="article-modal">
            <h2 class="article-title mb-3">Login to your Account</h2>
            <h5 class="article-subtitle">Not yet a member?
                <a class="link-underline" :href="URL.base + '/register'">Sign Up</a>
            </h5>
            <form :action="URL.base + '/login'" method="POST">
                <div :class="['alert', 'alert-dismissible', message.status == 'success' ? 'alert-success' : 'alert-danger']" v-if="message">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <div v-for="msg in message.body">
                        {{msg}}
                    </div>
                </div>
                <input type="hidden" name="_token" id="_token" :value="token">
                <div class="form-group">
                    <input v-model="param.email" name="email" type="email" class="form-control form-control-md" required="required" placeholder="Email Address" />
                </div>
                <div class="form-group mt-4">
                    <input name="password" type="password" class="form-control form-control-md" required="required" placeholder="Password" />
                </div>
                <h5 class="article-subtitle mt-4">
                    <a :href="URL.base + '/password/reset'">Forgot Password?</a>
                </h5>
                <div class="text-center">
                    <button type="submit" class="btn btn-darkblue btn-size-360">Login</button>
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
                token: window.token
            }
        },
        mounted() {          
        },

        created() {
        },
    }
</script>
