<template>
    <section class="section-background section-find-captains">
        <p class="page-title mb-5">Nearby Captains</p>

        <article class="article-modal">
            <article-close v-bind:link="URL.base + '/' + captainInfo.id + '/captain-bio'"></article-close>
            <h2 class="article-title">Captain {{ captainInfo.fullName }}</h2>            
            <h5 class="article-subtitle">
                <span v-if="captainInfo.city && captainInfo.state">{{captainInfo.city}}, {{captainInfo.state}}</span>
            </h5>
            <div class="captain-view">
                <div class="captain-face captain-face-220 m-auto">
                    <img v-if="captainInfo.avatar" :src="URL.base + '/public/images/avatars/' + captainInfo.avatar" />
                    <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                </div>
                <div class="aside-left">
                    <template v-if="captainInfo.firstResponder">
                        <p>
                            <img :src="URL.base + '/public/images/responder.png'"/> First Responder
                        </p>
                    </template>
                    <template v-if="captainInfo.maritimeGrad">
                        <p>
                            <img :src="URL.base + '/public/images/grad.png'"/> Maritime Grad
                        </p>
                    </template>                    
                </div>
                <div class="aside-right">
                    <template v-if="captainInfo.militaryVeteran">
                        <p>
                            <img :src="URL.base + '/public/images/veteran.png'"/> Military/Veteran
                        </p>
                    </template>
                    <template v-if="captainInfo.drugFree">
                        <p>
                            <img :src="URL.base + '/public/images/drug.png'"/> Drug Free
                        </p>
                    </template> 
                </div>
            </div>
            <br/>
            <div class="text-center mt-2">
                <rating-bar v-bind:param="{'value':captainInfo.rating,'size':'md'}"></rating-bar>
            </div>
            <div class="h22 text-center mt-2">
                <a class="link-underline" :href="URL.base + '/' + captainInfo.id + '/captain-bio'">View Bio</a>
            </div>
            <div class="row mt-5 mb-4">
                <div class="col pr-0">
                    <h4 class="article-title text-left text-nowrap mt-1">{{ totalCount | filterComma }} Total Reviews</h4>
                </div>
                <div class="col">
                    <div class="div-select right-radius">
                        <select @change="filterChange" v-model="filter" class="custom-select">
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
                </div>
            </div>
            <div class="captain-review-list">
                <div class="captain-review-item" v-for="review in reviewList">
                    <div class="row">
                        <div class="col-sm-3 text-center">
                            <div class="captain-face captain-face-100 mx-auto mt-1 mb-2">
                                <img v-if="captainInfo.avatar" :src="URL.base + '/public/images/avatars/' + review.avatar" />
                                <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                            </div>
                            <p class="captain-title">
                                <b>{{ review.firstName }} {{ review.lastName[0]}}.</b>
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
            <br/>
            <br/>
            <div class="text-center mb-2" v-if="totalCount > reviewList.length">
                <a @click.prevent="getMoreReview" href="#" class="btn btn-darkblue btn-size-360">Show More Reviews</a>
            </div>
            <div class="text-center" v-if="hire == 0">
                <a :href="URL.base + '/login'" class="btn btn-darkblue btn-size-360">Login to Book Capt. {{ captainInfo.firstName }}</a>
            </div>
            <div class="text-center" v-if="hire == 1">
                <a :href="URL.base + '/' + captainInfo.id + '/owner-book-captain'" class="btn btn-darkblue btn-size-360">Hire Capt. {{ captainInfo.firstName }}</a>
            </div>
        </article>
        <working-indicator v-if="working"></working-indicator>
    </section>
</template>

<script>
    export default {

        props: ['hire', 'captainInfo', 'reviews'],
        
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
                    captainId: this.captainInfo.id,
                    filter: this.filter,
                    limit: this.reviewList.length
                }
                this.$http.post(this.URL.base + '/captain-bio-reviews', param, {})
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
                    captainId: this.captainInfo.id,
                    filter: this.filter,
                    offset: this.reviewList.length
                }
                this.$http.post(this.URL.base + '/captain-bio-reviews', param, {})
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
