<template>
    <section>
        <article class="article-page container-768">
            <article-close v-bind:link="URL.previous"></article-close>
            <h2 class="article-title">{{ userInfo.firstName }}’s Reviews</h2>
            <div class="text-center mt-4">
                <rating-bar v-bind:param="{'value':userInfo.rating,'size':'md'}"></rating-bar>
            </div>
            <br />
            <br />
            <div class="div-select right-radius container-440 mb-3">
                <select @change="filterChange" v-model="filter" class="custom-select custom-select-lg">
                    <option selected="selected" hidden="hidden" disabled="disabled" value="null">Filter reviews by...</option>
                    <option value="date_desc">Date recent first</option>
                    <option value="date_asc">Date oldest first</option>
                    <option value="rating_desc">Rating highest first</option>
                    <option value="rating_asc">Rating lowest first</option>
                    <option value="name_asc">Name A to Z</option>
                    <option value="name_desc">Name Z to A</option>
                </select>
                <button type="button" class="btn btn-primary dropdown-toggle">
                </button>
            </div>
            <br />
            <br />

            <div class="container-640">
                <h4 class="article-title text-left mt-1 ml-3">{{ totalCount | filterComma }} Total Reviews</h4>
                <br />
                <div class="captain-review-list">
                    <div class="captain-review-item" v-for="review in reviewList">
                        <div class="row">
                            <div class="col-sm-3 text-center">
                                <div class="captain-face captain-face-100 mx-auto mt-1 mb-2">
                                    <img v-if="userInfo.avatar" :src="URL.base + '/public/images/avatars/' + review.avatar" />
                                    <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                                </div>
                                <p class="captain-title">
                                </p>
                            </div>
                            <div class="col-sm-9">
                                <p class="mb-3 mt-1 clearfix">
                                    <b>{{ review.date }}</b>
                                    <rating-bar class="float-right" v-bind:param="{'value': review.rating}"></rating-bar>
                                </p>
                                <p class="text-read-more text-break text-justify" data-index="100" v-readmore="review.describe">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="text-center" v-if="totalCount > reviewList.length">
                <a @click.prevent="getMoreReview" href="#" class="btn btn-darkblue btn-size-360">Show More Reviews</a>
            </div>
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
                totalCount: 0,
                reviewList: [],
                filter: null,
                working: false
            }
        },
        mounted() {                
            var vm = this
            this.$nextTick(function () {                
                vm.getReviewList();                
            })          
        },

        created() {
        },

        methods: {
            getReviewList() {
                this.working = true;
                var vm = this;
                var param = {
                    filter: this.filter,
                    limit: this.reviewList.length
                }
                this.$http.post(this.URL.base + '/' + this.userInfo.id + '/admin-captain-reviews', param, {})
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
                    filter: this.filter,
                    offset: this.reviewList.length
                }
                this.$http.post(this.URL.base + '/' + this.userInfo.id + '/admin-captain-reviews', param, {})
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
