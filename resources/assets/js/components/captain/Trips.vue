<template>
    <section>
        <article class="article-page">
            <h2 class="article-title mb-3">{{ userInfo.firstName }}’s Trips</h2>
            <h5 class="article-subtitle">You have {{ upcomingCount | filterComma }} upcoming trip!
                <a @click.prevent="getMoreUpcomingTrip" href="#" class="link-underline" v-if="upcomingCount > upcomingTripList.length">View Details</a>
            </h5>
            <div class="captain-list container-640">
                <template v-for="trip in upcomingTripList">
                    <trip-item v-bind:item="trip" v-bind:param="param"></trip-item>
                </template>   
            </div>
            <br/>
            <h4 class="article-title">{{ pendingCount | filterComma }} Pending Trips</h4>
            <br/>
            <div class="container-640" v-if="pendingCount > 0">
                <table class="table table-borderless table-striped font-opensans">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>OWNER</th>
                            <th>TRIP</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="trip in pendingTripList">
                            <td>{{ trip.date }}</td>
                            <td>{{ trip.firstName }} {{ trip.lastName }}</td>
                            <td>
                                <a :href="URL.base + '/' + trip.id + '/captain-pending-trip'" class="link-underline">#{{ trip.tripId }}</a></td>
                            <td v-sup="trip.total"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br/>
            <h4 class="article-title">{{ completeCount | filterComma }} Completed Trips</h4>
            <br/>
            <div class="div-select right-radius container-440 mb-3">
                <select @change="filterChange" v-model="filter" class="custom-select custom-select-lg">
                    <option selected="selected" hidden="hidden" disabled="disabled" value="null">Filter trips by...</option>
                    <option value="date_desc">Date recent first</option>
                    <option value="date_asc">Date oldest first</option>
                    <option value="total_desc">Total highest first</option>
                    <option value="total_asc">Total lowest first</option>
                    <option value="name_asc">Name A to Z</option>
                    <option value="name_desc">Name Z to A</option>
                </select>
                <button type="button" class="btn btn-primary dropdown-toggle">
                </button>
            </div>
            <br/>
            <br/>
            <div class="container-640" v-if="completeCount > 0">
                <table class="table table-borderless table-striped font-opensans">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>OWNER</th>
                            <th>TRIP</th>
                            <th>TOTAL</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="trip in completeTripList">
                            <td>{{ trip.date }}</td>
                            <td>{{ trip.firstName }} {{ trip.lastName }}</td>
                            <td>
                                <a :href="URL.base + '/' + trip.tripId + '/captain-trip-detail'" class="link-underline">#{{ trip.tripId }}</a></td>
                            <td v-sup="trip.total"></td>
                            <td>
                                <a :href="URL.base + '/' + trip.tripId + '/captain-trip-detail'" class="link-underline">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br/>
            <br/>
            <div class="text-center" v-if="completeCount > completeTripList.length">
                <a @click.prevent="getMoreCompleteTrip" href="#" class="btn btn-darkblue btn-size-360">Show More Trips</a>
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
                param: {
                    link: 'captain-trip-detail',
                    buttonText: 'View Trip'
                },
                upcomingCount: 0,
                upcomingTripList: [],
                pendingCount: 0,
                pendingTripList: [],
                completeCount: 0,
                completeTripList: [],
                filter: null,
                working: false
            }
        },
        mounted() {    
            var vm = this
            this.$nextTick(function () {
                vm.getUpcomingTripList();
                vm.getPendingTripList();
                vm.getCompleteTripList();
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

            getPendingTripList() {
                this.working = true;
                var vm = this;
                this.$http.post(this.URL.base + '/captain-bid-list', {}, {})
                .then(response => {
                    var responseData = response.body;
                    vm.pendingCount = responseData.bidCount;
                    vm.pendingTripList = responseData.bidList;
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

            getCompleteTripList() {
                this.working = true;
                var vm = this;
                var param = {
                    isComplete: 1,
                    limit: this.completeTripList.length,
                    filter: this.filter
                }
                this.$http.post(this.URL.base + '/captain-trip-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.completeCount = responseData.tripCount;
                    vm.completeTripList = responseData.tripList;
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            getMoreCompleteTrip() {
                this.working = true;
                var vm = this;
                var param = {
                    isComplete: 1,
                    offset: this.completeTripList.length,
                    filter: this.filter
                }
                this.$http.post(this.URL.base + '/captain-trip-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.completeCount = responseData.tripCount;

                    responseData.tripList.forEach(item => {
                        vm.completeTripList.push(item);
                    })
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            filterChange() {
                this.getCompleteTripList();
            }
        },
    }
</script>
