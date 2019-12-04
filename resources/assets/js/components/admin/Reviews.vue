<template>
    <section>
        <article class="article-page container-768">
            <article-close v-bind:link="URL.base+'/admin-dashboard'"></article-close>
            <h2 class="article-title mb-5">Reviews</h2>
            <div class="container-640">
                <div class="row" data-padding="2">
                    <div class="col">
                        <div class="form-group-calendar">
                            <input id="startDate" type="text" class="form-control form-control-md min-width-160" placeholder="Start Date..." />
                            <span class="right-icon fa fa-calendar"></span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group-calendar">
                            <input id="endDate" type="text" class="form-control form-control-md min-width-160" placeholder="End Date..." />
                            <span class="right-icon fa fa-calendar"></span>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <br />
            <h4 class="article-title">{{ totalCount }} Total Reviews</h4>
            <br />
            <br />
            <div class="div-select right-radius container-440 mb-3">
                <select @change="filterChange" v-model="filter" class="custom-select custom-select-lg">
                    <option selected="selected" hidden="hidden" disabled="disabled" value="null">Filter revenue by...</option>
                    <option value="date_desc">Date recent first</option>
                    <option value="date_asc">Date oldest first</option>
                    <option value="rating_desc">Rating highest first</option>
                    <option value="rating_asc">Rating lowest first</option>
                    <option value="captain_asc">Captain A to Z</option>
                    <option value="captain_desc">Captain Z to A</option>
                </select>
                <button type="button" class="btn btn-primary dropdown-toggle">
                </button>
            </div>
            <br />
            <div class="container-640">
                <table class="table table-borderless table-striped font-opensans">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>CAPTAIN</th>
                            <th>&ensp;RATING</th>
                            <th>TRIP</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="review in reviewList">
                            <td>{{ review.date }}</td>
                            <td class="text-break">{{ review.firstName }} {{ review.lastName }}</td>
                            <td><rating-bar v-bind:param="{'value':review.rating}"></rating-bar></td>
                            <td>
                                <a :href="URL.base + '/' + review.tripId + '/admin-trip-detail'" class="link-underline">#{{ review.tripId }}</a>
                            </td>
                            <td>
                                <a :href="URL.base + '/' + review.captainId + '/admin-captain-profile'" class="link-underline">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br />
            <br />
            <div class="text-center" v-if="totalCount > reviewList.length">
                <a @click.prevent="getMoreReview" href="#" class="btn btn-darkblue btn-size-360">Show More Reviews</a>
            </div>
            <br />
        </article>
        <working-indicator v-if="working"></working-indicator>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                URL: window.URL,
                totalCount: 0,
                reviewList: [],
                filter: null,
                startDate: null,
                endDate: null,
                working: false
            }
        },
        mounted() {  
            var vm = this;
            this.$nextTick(function () {
                $('#startDate').datetimepicker({
                    format: 'MM/DD/YYYY'
                })
                .on("dp.change", function(e) {
                    if(vm.startDate == e.target.value)
                        return;
                    vm.startDate = e.target.value;
                    vm.reviewList = [];
                    vm.getReviewList();
                })
                $('#endDate').datetimepicker({
                    format: 'MM/DD/YYYY'
                })
                .on("dp.change", function(e) {
                    if(vm.endDate == e.target.value)
                        return;
                    vm.endDate = e.target.value;
                    vm.reviewList = [];
                    vm.getReviewList();
                })
                this.getReviewList();
            })          
        },

        created() {
        },

        methods: {
            getReviewList() {
                this.working = true;
                var vm = this;
                var param = {
                    startDate: this.startDate,
                    endDate: this.endDate,
                    filter: this.filter,
                    limit: this.reviewList.length
                }
                this.$http.post(this.URL.base + '/admin-review-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.totalCount = responseData.totalCount;
                    vm.reviewList = responseData.reviewList;
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            getMoreReview() {
                this.working = true;
                var vm = this;
                var param = {
                    startDate: this.startDate,
                    endDate: this.endDate,
                    filter: this.filter,
                    offset: this.reviewList.length
                }
                this.$http.post(this.URL.base + '/admin-review-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.totalCount = responseData.totalCount;
                    responseData.reviewList.forEach(item => {
                        vm.reviewList.push(item);
                    })
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            filterChange() {
                this.getReviewList();
            }
        }
    }
</script>
