<template>
    <section>
        <article class="article-page container-768">
            <h2 class="article-title mb-3">Bid Submitted!</h2>
            <h5 class="article-subtitle">
                Notification has been sent to the owner with your bid details, and you should expect a response within 48 hours.
            </h5>
            <br/>
            <div class="text-center">
                <a :href="URL.base + '/dashboard'" class="btn btn-darkblue btn-size-360">Back to Dashboard</a>
            </div>
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
