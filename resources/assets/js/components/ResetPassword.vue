<template>
    <section class="section-background section-login">
        <p class="page-title">Your Account</p>

        <article class="article-modal">
            <h2 class="article-title">Reset Password</h2>
            <br/>
            <form class="form-md form-horizontal" role="form" method="POST" :action="URL.base + '/password/reset'">
                <div :class="['alert', 'alert-dismissible', message.status == 'success' ? 'alert-success' : 'alert-danger']" v-if="message">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <div v-for="msg in message.body">
                        {{msg}}
                    </div>
                </div>
                <input type="hidden" name="_token" id="_token" :value="token">
                <input type="hidden" name="token" v-model="param.token">
                <div class="form-group">
                    <!-- <label for="email" class="mb-1 mb-o mx-2">E-Mail Address</label> -->
                    <input v-model="param.email" id="email" type="email" class="form-control form-control-md" name="email" required="required" autofocus="autofocus" placeholder="Email Address" />
                </div>
                <div class="form-group mt-4">
                    <!-- <label for="password" class="mb-1 mb-o mx-2">Password</label> -->
                    <input id="password" type="password" class="form-control form-control-md" name="password" required="required" placeholder="Password" />
                </div>
                <div class="form-group mt-4">
                    <!-- <label for="password-confirm" class="mb-1 mb-o mx-2">Confirm Password</label> -->
                    <input id="password-confirm" type="password" class="form-control form-control-md" name="password_confirmation" required="required" placeholder="Confirm Password" />
                </div>
                <br/>
                <div class="text-center">
                    <button type="submit" class="btn btn-darkblue btn-size-360">Reset Password</button>
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
