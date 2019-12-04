<template>
    <section class="section-background section-login">
        <p class="page-title">Your Account</p>

        <article class="article-modal" v-if="!success">
            <h2 class="article-title mb-3">Forgot Password</h2>
            <h5 class="article-subtitle mb-5">Do you remember?
                <a class="link-underline" :href="URL.base + '/login'">Login</a>
            </h5>
            <form :action="URL.base + '/password/email'" method="POST">
                <div :class="['alert', 'alert-dismissible', message.status == 'success' ? 'alert-success' : 'alert-danger']" v-if="message">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <div v-for="msg in message.body">
                        {{msg}}
                    </div>
                </div>
                <input type="hidden" name="_token" id="_token" :value="token" />
                <div class="form-group">
                    <input name="email" type="email" class="form-control form-control-md" required="required" placeholder="Email Address" />
                </div>
                <br/>
                <div class="text-center">
                    <button type="submit" class="btn btn-darkblue btn-size-360">Request Reset</button>
                </div>
            </form>
        </article>
        <article class="article-modal" v-if="success">
            <h2 class="article-title mb-4">Reset Password Email Sent!</h2>
            <h5 class="article-subtitle">
                Please check your inbox for an email that contains a link where you can reset your password. Having trouble?
                <a class="link-underline" :href="URL.base + '/contact'">Contact Us</a>
            </h5>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['success', 'message'],

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
