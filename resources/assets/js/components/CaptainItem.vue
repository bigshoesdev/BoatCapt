<template>
    <div class="captain-item">
        <div class="captain-face">
            <img v-if="item.avatar" :src="URL.base + '/public/images/avatars/' + item.avatar" />
            <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
        </div>
        <p class="article-item-trip" v-if="item.tripId">Trip ID #{{ item.tripId }}</p>
        <h4>
            <a class="link-underline" :href="URL.base + '/' + item.id +'/' + param.link">Capt. {{ item.firstName }}</a>
        </h4>
        <template v-if="item.city">
            <p class="captain-item-subtitle font-weight-bold mb-3">
                {{ item.city }}, {{ item.state }}
                <span class="font-weight-normal" v-if="item.away != null">&ensp;({{ item.away | filterComma }}mi away)</span>
            </p>
        </template>
        <template v-else>
            <br/>
        </template>
        <rating-bar v-bind:param="{'value': item.rating}"></rating-bar>
        <a :href="URL.base + '/' + item.id +'/' + param.link" class="btn btn-darkblue btn-size-150">{{ param.buttonText }}</a>
    </div>
</template>

<script>
    export default {

        props: ['item', 'param'],

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