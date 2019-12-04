<template>
    <section>
        <article class="article-page">
            <template v-if="bidCount == 0 && tripCount == 0">
                <h2 class="article-title mb-3">Empty Dashboard</h2>
                <h5 class="article-subtitle">
                    You do not have anything booked.
                    <a :href="URL.base + '/find-captains'" class="link-underline">Find Captains</a>
                </h5>
                <br/>
                <br/>
                <div class="text-center">
                    <a :href="URL.base + '/find-captains'" class="btn btn-darkblue btn-size-360">Find Captains</a>
                </div>
                <br/>
            </template>
            <template v-else>
                <h2 class="article-title mb-3">{{ userInfo.firstName }}’s Dashboard</h2>
                <h5 class="article-subtitle">You have {{ bidCount | filterComma }} new bids awaiting review!
                    <a @click.prevent="getMoreBid" href="#" class="link-underline" v-if="bidCount > bidList.length">View Bids</a>
                </h5>
                <div class="captain-list container-640">
                    <template v-for="bid in bidList">
                        <captain-item v-bind:item="bid" v-bind:param="param"></captain-item>
                    </template>                
                </div>
                <br/>
                <div class="container-640" v-if="tripCount > 0">
                    <table class="table table-borderless table-striped font-opensans">
                        <caption class="h4">Recent Trips</caption>
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th>CAPTAIN</th>
                                <th>TRIP</th>
                                <th>TOTAL</th>
                                <th>BOOK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="trip in tripList">
                                <td>{{ trip.date }}</td>
                                <td>Capt. {{ trip.firstName }}</td>
                                <td>
                                    <a :href="URL.base + '/' + trip.tripId + '/owner-trip-detail'" class="link-underline">#{{ trip.tripId }}</a></td>
                                <td v-sup="trip.total"></td>
                                <td>
                                    <a :href="URL.base + '/' + trip.captainId + '/owner-book-captain'" class="link-underline">Request</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br/>
                <br/>
                <div class="text-center" v-if="tripCount > tripList.length">
                    <a @click.prevent="getMoreTrip" href="#" class="btn btn-darkblue btn-size-360">Show More Trips</a>
                </div>
                <br/>
            </template>            
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
                param: {
                    link: 'owner-view-bid',
                    buttonText: 'View Bid'
                },
                bidCount: 0,
                bidList: [],
                tripCount: 0,
                tripList: [],
                working: false
            }
        },
        mounted() {    
            var vm = this
            this.$nextTick(function () {
                vm.getBidList();
                vm.getTripList();
            })         
        },

        created() {
        },

        methods: {
            getBidList() {
                this.working = true;
                var vm = this;
                var param = {
                    limit: this.bidList.length
                }
                this.$http.post(this.URL.base + '/owner-bid-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.bidCount = responseData.bidCount;
                    vm.bidList = responseData.bidList;
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            getMoreBid() {
                this.working = true;
                var vm = this;
                var param = {
                    offset: this.bidList.length
                }
                this.$http.post(this.URL.base + '/owner-bid-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.bidCount = responseData.bidCount;

                    responseData.bidList.forEach(item => {
                        vm.bidList.push(item);
                    })
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            getTripList() {
                this.working = true;
                var vm = this;
                var param = {
                    isComplete: 1,
                    limit: 3
                }
                this.$http.post(this.URL.base + '/owner-trip-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.tripCount = responseData.tripCount;
                    vm.tripList = responseData.tripList;
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            getMoreTrip() {
                this.working = true;
                var vm = this;
                var param = {
                    isComplete: 1,
                    offset: this.tripList.length
                }
                this.$http.post(this.URL.base + '/owner-trip-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.tripCount = responseData.tripCount;

                    responseData.tripList.forEach(item => {
                        vm.tripList.push(item);
                    })
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },
        },
    }
</script>
