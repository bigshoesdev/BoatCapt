<template>
    <section>
        <article class="article-page">
            <h2 class="article-title mb-3">Booking Complete!</h2>
            <h5 class="article-subtitle container-640">An email confirmation will be sent to {{ email }} with the trip details, and you can contact your caption to make any
                final arrangements:</h5>
            <div class="captain-face captain-face-220 m-auto">
                <img v-if="bidInfo.avatar" :src="URL.base + '/public/images/avatars/' + bidInfo.avatar" />
                <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
            </div>
            <h3 class="article-title mt-5 mb-3">Captain {{ bidInfo.firstName }} {{ bidInfo.lastName }}</h3>
            <h4 class="article-subtitle font-italic mb-3">{{ bidInfo.city }}, {{ bidInfo.state }}</h4>
            <div class="text-center mb-3">
                <rating-bar v-bind:param="{'value':bidInfo.rating}"></rating-bar>
            </div>
            <table class="table-pair-detail h4">
                <tbody>
                    <tr>
                        <th>Phone:</th>
                        <td>{{ bidInfo.captainPhone }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ bidInfo.captainEmail }}</td>
                    </tr>
                </tbody>
            </table>
            <br/>
            <br/>
            <div class="text-center mb-4">
                <a href="#" class="btn btn-darkblue btn-size-360">Add to Calendar</a>
            </div>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['bidInfo', 'email'],

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
