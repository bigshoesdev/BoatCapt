<template>
    <section>
        <article class="article-page container-768">
            <article-close v-bind:link="URL.base+'/admin-dashboard'"></article-close>
            <h2 class="article-title mb-5">Boat Owners</h2>
            <div class="container-640">
                <div class="row"  data-padding="2">
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
            <h4 class="article-title">{{ totalCount }} Total Owners</h4>
            <br />
            <br />
            <div class="div-select right-radius container-440 mb-3">
                <select @change="filterChange" v-model="filter" class="custom-select custom-select-lg">
                    <option selected="selected" hidden="hidden" disabled="disabled" value="null">Filter owners by...</option>
                    <option value="total_desc">Total highest first</option>
                    <option value="total_asc">Total lowest first</option>
                    <option value="rating_desc">Rating highest first</option>
                    <option value="rating_asc">Rating lowest first</option>
                    <option value="owner_asc">Owner A to Z</option>
                    <option value="owner_desc">Owner Z to A</option>
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
                            <th>OWNER</th>
                            <th>&ensp;RATING</th>
                            <th>TOTAL</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="owner in ownerList">
                            <td>{{ owner.date }}</td>
                            <td class="text-break">{{ owner.firstName }} {{ owner.lastName }}</td>
                            <td><rating-bar v-bind:param="{'value':owner.rating}"></rating-bar></td>
                            <td v-sup="owner.total"></td>
                            <td>
                                <a :href="URL.base + '/' + owner.id + '/admin-owner-profile'" class="link-underline">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br />
            <br />
            <div class="text-center" v-if="totalCount > ownerList.length">
                <a @click.prevent="getMoreOwner" href="#" class="btn btn-darkblue btn-size-360">Show More Owners</a>
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
                ownerList: [],
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
                    vm.ownerList = [];
                    vm.getOwnerList();
                })
                $('#endDate').datetimepicker({
                    format: 'MM/DD/YYYY'
                })
                .on("dp.change", function(e) {
                    if(vm.endDate == e.target.value)
                        return;
                    vm.endDate = e.target.value;
                    vm.ownerList = [];
                    vm.getOwnerList();
                })
                this.getOwnerList();
            })          
        },

        created() {
        },

        methods: {
            getOwnerList() {
                this.working = true;
                var vm = this;
                var param = {
                    startDate: this.startDate,
                    endDate: this.endDate,
                    filter: this.filter,
                    limit: this.ownerList.length
                }
                this.$http.post(this.URL.base + '/admin-owner-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.totalCount = responseData.totalCount;
                    vm.ownerList = responseData.ownerList;
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            getMoreOwner() {
                this.working = true;
                var vm = this;
                var param = {
                    startDate: this.startDate,
                    endDate: this.endDate,
                    filter: this.filter,
                    offset: this.ownerList.length
                }
                this.$http.post(this.URL.base + '/admin-owner-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.totalCount = responseData.totalCount;
                    responseData.ownerList.forEach(item => {
                        vm.ownerList.push(item);
                    })
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            filterChange() {
                this.getOwnerList();
            }
        }
    }
</script>
