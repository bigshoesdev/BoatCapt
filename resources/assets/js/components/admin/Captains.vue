<template>
    <section>
        <article class="article-page container-768">
            <article-close v-bind:link="URL.base+'/admin-dashboard'"></article-close>
            <h2 class="article-title mb-5">Captains</h2>
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
            <h4 class="article-title">{{ totalCount }} Total Captains</h4>
            <br />
            <div v-for="captain in newCaptains" class="alert alert-info container-640 mb-3">
                <span class="alert-text h5">Captain {{ captain.firstName }}&rsquo;s profile needs review</span>
                <a :href="URL.base + '/' + captain.id + '/admin-captain-profile'" class="btn btn-darkblue btn-size-150 float-right">View profile</a>
            </div>
            <br />
            <div class="div-select right-radius container-440 mb-3">
                <select @change="filterChange" v-model="filter" class="custom-select custom-select-lg">
                    <option selected="selected" hidden="hidden" disabled="disabled" value="null">Filter captains by...</option>
                    <option value="total_desc">Total highest first</option>
                    <option value="total_asc">Total lowest first</option>
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
                <table class="table table-borderless table-striped font-opensans vertical-align-middle">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>CAPTAIN</th>
                            <th>RATING</th>
                            <th>TOTAL</th>
                            <th hidden="hidden">STATE</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="captain in captainList">
                            <td>{{ captain.date }}</td>
                            <td class="text-break">{{ captain.firstName }} {{ captain.lastName }}</td>
                            <td><rating-bar v-bind:param="{'value':captain.rating}"></rating-bar></td>
                            <td v-sup="captain.total"></td>
                            <td hidden="hidden">
                                {{ captain.isActive==0 ? 'Unchecked' : captain.isActive==1 ? 'Approved' : 'Rejected' }}
                            </td>
                            <td>
                                <a :href="URL.base + '/' + captain.id + '/admin-captain-profile'" class="link-underline">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br />
            <br />
            <div class="text-center" v-if="totalCount > captainList.length">
                <a @click.prevent="getMoreCaptain" href="#" class="btn btn-darkblue btn-size-360">Show More Captains</a>
            </div>
            <br />
        </article>
        <working-indicator v-if="working"></working-indicator>
    </section>
</template>

<script>
    export default {

        props: ['newCaptains'],

        data() {
            return {
                URL: window.URL,
                totalCount: 0,
                captainList: [],
                filter: null,
                startDate: null,
                endDate: null,
                working: false
            }
        },
        mounted() {  
            var vm = this
            this.$nextTick(function () {
                $('#startDate').datetimepicker({
                    format: 'MM/DD/YYYY'
                })
                .on("dp.change", function(e) {
                    if(vm.startDate == e.target.value)
                        return;
                    vm.startDate = e.target.value;
                    vm.captainList = [];
                    vm.getCaptainList();
                })
                $('#endDate').datetimepicker({
                    format: 'MM/DD/YYYY'
                })
                .on("dp.change", function(e) {
                    if(vm.endDate == e.target.value)
                        return;
                    vm.endDate = e.target.value;
                    vm.captainList = [];
                    vm.getCaptainList();
                })
                this.getCaptainList();
            })          
        },

        created() {
        },

        methods: {
            getCaptainList() {
                this.working = true;
                var vm = this;
                var param = {
                    startDate: this.startDate,
                    endDate: this.endDate,
                    filter: this.filter,
                    limit: this.captainList.length
                }
                this.$http.post(this.URL.base + '/admin-captain-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.totalCount = responseData.totalCount;
                    vm.captainList = responseData.captainList;
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            getMoreCaptain() {
                this.working = true;
                var vm = this;
                var param = {
                    startDate: this.startDate,
                    endDate: this.endDate,
                    filter: this.filter,
                    offset: this.captainList.length
                }
                this.$http.post(this.URL.base + '/admin-captain-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.totalCount = responseData.totalCount;
                    responseData.captainList.forEach(item => {
                        vm.captainList.push(item);
                    })
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            filterChange() {
                this.getCaptainList();
            }
        },
    }
</script>
