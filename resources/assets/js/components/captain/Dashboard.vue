<template>
    <section>
        <article class="article-page">
            <h2 class="article-title mb-3">{{ userInfo.firstName }}’s Dashboard</h2>
            <h5 class="article-subtitle">You have {{ upcomingCount | filterComma }} upcoming trips!
                <a @click.prevent="getMoreUpcomingTrip" href="#" class="link-underline" v-if="upcomingCount > upcomingTripList.length">View Details</a>
            </h5>
            <div class="captain-list container-640">
                <template v-for="trip in upcomingTripList">
                    <trip-item v-bind:item="trip" v-bind:param="tripParam"></trip-item>
                </template>   
            </div>
            <br/>
            <br/>
            <h4 class="article-title mb-3">Bid Requests</h4>
            <h5 class="article-subtitle">You have {{ bidRequestCount | filterComma }} new bid requests!
                <a @click.prevent="getMoreBidRequest" href="#" class="link-underline" v-if="bidRequestCount > bidRequestList.length">View All Requests</a>
            </h5>
            <div class="captain-list container-640">
                <template v-for="bid in bidRequestList">
                    <trip-item v-bind:item="bid" v-bind:param="bidParam"></trip-item>
                </template>
            </div>
            <br/>
        </article>
        <working-indicator v-if="working"></working-indicator>
    </section>
</template>

<script>
    export default {

        props: ['userInfo'],

        data() {
            return {
                URL: window.URL,
                tripParam: {
                    link: 'captain-trip-detail',
                    buttonText: 'View Trip'
                },
                bidParam: {
                    link: 'bid-request-detail',
                    buttonText: 'View Detail'
                },
                upcomingCount: 0,
                upcomingTripList: [],
                bidRequestCount: 0,
                bidRequestList: [],
                working: false
            }
        },
        mounted() {    
            var vm = this
            this.$nextTick(function () {
                vm.getUpcomingTripList();
                vm.getBidRequestList();
            })         
        },

        created() {
        },
        methods: {
            getUpcomingTripList() {
                this.working = true;
                var vm = this;
                var param = {
                    isComplete: 0,
                    limit: this.upcomingTripList.length
                }
                this.$http.post(this.URL.base + '/captain-trip-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.upcomingCount = responseData.tripCount;
                    vm.upcomingTripList = responseData.tripList;
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            getMoreUpcomingTrip() {
                this.working = true;
                var vm = this;
                var param = {
                    isComplete: 0,
                    offset: this.upcomingTripList.length
                }
                this.$http.post(this.URL.base + '/captain-trip-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.upcomingCount = responseData.tripCount;

                    responseData.tripList.forEach(item => {
                        vm.upcomingTripList.push(item);
                    })
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            getBidRequestList() {
                this.working = true;
                var vm = this;
                var param = {
                    limit: this.bidRequestList.length
                }
                this.$http.post(this.URL.base + '/captain-bid-requests', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.bidRequestCount = responseData.bidRequestCount;
                    vm.bidRequestList = responseData.bidRequestList;
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            getMoreBidRequest() {
                this.working = true;
                var vm = this;
                var param = {
                    offset: this.bidRequestList.length
                }
                this.$http.post(this.URL.base + '/captain-bid-requests', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.bidRequestCount = responseData.bidRequestCount;

                    responseData.bidRequestList.forEach(item => {
                        vm.bidRequestList.push(item);
                    })
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            }
        },
    }
</script>
