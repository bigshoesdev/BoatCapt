<template>
    <section>
        <article class="article-page container-768">
            <h2 class="article-title mb-3">Dashboard</h2>
            <br/>
            <div class="container-720">
                <form class="row mb-4">
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
                </form>
                <div class="row div-box-container">
                    <div class="col">
                        <a href="admin-trips" class="div-box div-box-info">
                            <h1>{{ values.trip | filterComma }}</h1>
                            <h3>TRIPS</h3>
                            <div v-if="newTripsCount>0" class="div-box-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248z" fill="#ffffff"/>
                                    <path d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zm-248 50c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z" fill="#f2a605"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="admin-revenue" class="div-box">
                            <h1 v-sup="values.revenue"></h1>
                            <h3>REVENUE</h3>
                        </a>
                    </div>
                    <div class="col">
                        <a href="admin-capts" class="div-box">
                            <h1>{{ values.captain | filterComma }}</h1>
                            <h3>CAPTS</h3>
                        </a>
                    </div>
                    <div class="col">
                        <a href="admin-owners" class="div-box">
                            <h1>{{ values.owner | filterComma }}</h1>
                            <h3>OWNERS</h3>
                        </a>
                    </div>
                    <div class="col">
                        <a href="admin-reviews" class="div-box">
                            <h1>{{ values.review | filterComma }}</h1>
                            <h3>REVIEWS</h3>
                        </a>
                    </div>
                    <div class="col">
                        <a href="admin-payments" class="div-box">
                            <h1 v-sup="values.payment" data-prefix="($" data-suffix=")"></h1>
                            <h3>PAYMENTS</h3>
                        </a>
                    </div>
                    <div class="col">
                        <a href="admin-fees" class="div-box">
                            <h1 v-sup="values.fee" data-prefix="($" data-suffix=")"></h1>
                            <h3>FEES</h3>
                        </a>
                    </div>
                </div>
                <div class="row div-box-container">
                    <div class="col">
                        <a href="admin-nets" class="div-box div-box-green">
                            <h1 v-sup="values.net"></h1>
                            <h3>NET</h3>
                        </a>
                    </div>
                </div>
            </div>
            <br/>
            <br/>
            <h4 class="text-center">
                <a :href="URL.base + '/logout'" class="link-underline">Log Out</a>
            </h4>
        </article>
        <working-indicator v-if="working"></working-indicator>
    </section>
</template>

<script>
    export default {

        props: ['newTripsCount'],

        data() {
            return {
                URL: window.URL,
                values: {},
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
                    vm.getValues();
                })
                $('#endDate').datetimepicker({
                    format: 'MM/DD/YYYY'
                })
                .on("dp.change", function(e) {
                    if(vm.endDate == e.target.value)
                        return;
                    vm.endDate = e.target.value;
                    vm.getValues();
                })
                vm.getValues();
            })          
        },

        created() {
        },

        methods: {
            getValues() {
                this.working = true;
                var vm = this;
                var param = {
                    startDate: this.startDate,
                    endDate: this.endDate,
                }
                this.$http.post(this.URL.base + '/admin-dashboard-list', param, {})
                .then(response => {
                    vm.values = response.body;
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            }
        },
    }
</script>
