<template>
    <section class="section-background section-faq">
        <p class="page-title mb-5">Contact Us</p>

        <article class="article-modal">
            <h2 class="article-title">Have a Question?</h2>
            <h5 class="article-subtitle">Please complete the form below to reach our support team, or call
                <b class="text-nowrap">(212) 555-1235</b> for immediate assistance.</h5>
            <br />
            <form class="form-md container-440">
                <div class="form-group">
                    <input type="text" class="form-control" required="required" placeholder="Full Name" />
                </div>
                <div class="form-group mt-4">
                    <input type="email" class="form-control" required="required" placeholder="Email Address" />
                </div>
                <div class="form-group mt-4">
                    <input type="text" class="form-control" required="required" placeholder="Phone" />
                </div>
                <textarea class="form-control fontsize-md mt-4" placeholder="How can we assist?"></textarea>
                <br />
                <br />
                <div class="text-center">
                    <button type="submit" class="btn btn-darkblue btn-size-360">Contact Support</button>
                </div>
            </form>
        </article>
    </section>
</template>

<script>
    export default {
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
