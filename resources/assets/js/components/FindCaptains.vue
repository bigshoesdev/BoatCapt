<template>
    <section class="section-background section-find-captains">
        <p class="page-title mb-5">Nearby Captains</p>

        <article class="article-modal">
            <h2 class="article-title mb-3">Captains<span v-if="city && state"> Near {{ city }}, {{ state }}</span></h2>
            <template v-if="totalCount == 0">
                <h5 class="article-subtitle">Try search another location...</h5>
            </template>
            <template v-else>
                <h5 class="article-subtitle">{{ totalCount | filterComma }} Captains Found</h5>
                <div class="div-select right-radius w-75">
                    <select @change="filterChange" v-model="filter" class="custom-select custom-select-lg shadow">
                        <option selected="selected" hidden="hidden" disabled="disabled" value="null">Filter results by...</option>
                        <option value="rating_desc">Rating high to low</option>
                        <option value="rating_asc">Rating low to high</option>
                        <option value="distance_asc">Distance near to far</option>
                        <option value="distance_desc">Distance far to near</option>
                        <option value="name_asc">Name A to Z</option>
                        <option value="name_desc">Name Z to A</option>
                    </select>
                    <button type="button" class="btn btn-primary dropdown-toggle">
                    </button>
                </div>
            </template>
            
            <div class="captain-list">
                <template v-for="captain in captainList">
                    <captain-item v-bind:item="captain" v-bind:param="param"></captain-item>
                </template>                
            </div>
            <br/>
            <div class="text-center" v-if="totalCount > captainList.length">
                <a @click.prevent="getMoreCaptain" href="#" class="btn btn-darkblue btn-size-360">Show More Captains</a>
            </div>
        </article>
        <working-indicator v-if="working"></working-indicator>
    </section>
</template>

<script>

    export default {

        props: ['info'],

        data() {
            return {
                URL: window.URL,
                param: {
                    link: 'captain-bio',
                    buttonText: 'View Bio'
                },
                totalCount: 0,
                captainList: [],
                filter: null,
                latlng: null,
                city: this.info.city,
                state: this.info.state,
                working: false
            }
        },
        mounted() {  
            var vm = this
            this.$nextTick(function () {
                // if(typeof google !== "undefined" && vm.info.city == null && vm.info.state == null)
                //     google.maps.event.addDomListener(window, 'load', vm.initialize);  
                // else
                    vm.getCaptainList();                  
            })          
        },

        created() {
            
        },

        methods: {
            getCaptainList() {
                this.working = true;
                var vm = this;
                var param = {
                    search: this.info.search,
                    lat: this.info.lat,
                    lon: this.info.lon,
                    filter: this.filter,
                    // latlng: this.latlng,
                    limit: this.captainList.length
                }
                this.$http.post(this.URL.base + '/captain-list', param, {})
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
                    search: this.info.search,
                    lat: this.info.lat,
                    lon: this.info.lon,
                    filter: this.filter,
                    // latlng: this.latlng,
                    offset: this.captainList.length
                }
                this.$http.post(this.URL.base + '/captain-list', param, {})
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
            },

            initialize() {
                var vm = this;
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        var geocoder = new google.maps.Geocoder;
                        vm.latlng = {lat: pos['lat'], lng: pos['lng']};

                        vm.getCaptainList(); 
                        geocoder.geocode({'location': vm.latlng}, function(results, status) {
                            if (status === 'OK') 
                            {
                                if (results[0]) 
                                {
                                    for (var i = 0; i < results[0].address_components.length; i++) {
                                        var addressType = results[0].address_components[i].types[0];
                                        if (addressType == 'locality') {
                                            vm.city = results[0].address_components[i]['long_name'];
                                        }

                                        if (addressType == 'administrative_area_level_1') {
                                            vm.state = results[0].address_components[i]['short_name'];
                                        }
                                    }
                                } 
                                else 
                                {
                                    vm.getCaptainList();
                                }
                            } 
                            else 
                            {
                                vm.handleLocationError(true);
                            }
                        });
                    }, function() {
                        vm.handleLocationError(false);
                    });
                } 
                else 
                {
                    // Browser doesn't support Geolocation
                    handleLocationError(true);
                    // If you see the error "The Geolocation service
                    // failed.", it means you probably did not give permission for the browser to
                    // locate you.
                }
            },

            handleLocationError(browserHasGeolocation) {
              alert(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
              this.getCaptainList();
            }
        }
    }
</script>
