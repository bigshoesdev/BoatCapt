<template>
    <section>
        <article class="article-page">
            <h2 class="article-title mb-3">Contact Captain</h2>
            <h5 class="article-subtitle">ID #{{ tripInfo.tripId }}</h5>
            <div class="captain-view">
                <div class="captain-face captain-face-220 m-auto">
                    <img v-if="tripInfo.avatar" :src="URL.base + '/public/images/avatars/' + tripInfo.avatar" />
                    <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                </div>
                <div class="aside-left">
                    <template v-if="tripInfo.firstResponder">
                        <p>
                            <img :src="URL.base + '/public/images/responder.png'"/> First Responder
                        </p>
                    </template>
                    <template v-if="tripInfo.maritimeGrad">
                        <p>
                            <img :src="URL.base + '/public/images/grad.png'"/> Maritime Grad
                        </p>
                    </template>                    
                </div>
                <div class="aside-right">
                    <template v-if="tripInfo.militaryVeteran">
                        <p>
                            <img :src="URL.base + '/public/images/veteran.png'"/> Military/Veteran
                        </p>
                    </template>
                    <template v-if="tripInfo.drugFree">
                        <p>
                            <img :src="URL.base + '/public/images/drug.png'"/> Drug Free
                        </p>
                    </template> 
                </div>
            </div>
            <h3 class="article-title mt-5">{{ tripInfo.firstName }} {{ tripInfo.lastName }}</h3>
            <h4 class="article-subtitle font-italic">{{ tripInfo.city }}, {{ tripInfo.state }}</h4>
            <table class="table-pair-detail h4">
                <tbody>
                    <tr>
                        <th>Phone:</th>
                        <td>{{ tripInfo.captainPhone }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ tripInfo.captainEmail }}</td>
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

        props: ['tripInfo'],

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
