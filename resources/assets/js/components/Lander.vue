<template>
    <section class="section-background section-lander mt-0 text-center">
        <button type="button" class="btn btn-pure btn-toggle-menu position-fixed">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewbox="0 0 36 22" width="36" height="22"
             fill="#0883ed">
                <rect y="0" width="36" height="4" />
                <rect y="9" width="36" height="4" />
                <rect y="18" width="36" height="4" />
            </svg>
        </button>
        <h1 class="page-title mb-1">Enjoy Your Boat!</h1>
        <form :action="URL.base + '/find-captains'" class="input-group div-search" method="GET">
            <input id="search_input" name="search" type="text" class="form-control input-search" placeholder="Departure address...">
            <div class="input-group-append">
                <button class="btn btn-primary fa fa-search" type="submit"></button>
            </div>
        </form>
        <article class="article-modal">
            <h2 class="article-title mb-4">Are you a Boat Owner?</h2>
            <h5 class="article-subtitle">This is the paragraph where you can write more details about the service.
                <br/> Keep users engaged by providing meaningful information</h5>
            <div class="row mb-3">
                <div class="col-sm-4 article-item">
                    <div class="pt-3">
                        <img :src="URL.base + '/public/images/safety.png'"/>
                    </div>
                    <h4 class="font-twcenmt">Safety First</h4>
                </div>
                <div class="col-sm-4 article-item">
                    <div>
                        <img :src="URL.base + '/public/images/wheel.png'"/>
                    </div>
                    <h4 class="font-twcenmt">Licensed Captians</h4>
                </div>
                <div class="col-sm-4 article-item">
                    <div>
                        <img :src="URL.base + '/public/images/rum.png'"/>
                    </div>
                    <h4 class="font-twcenmt">Relax &amp; Enjoy</h4>
                </div>
            </div>
            <br/>
            <div class="text-center">
                <a :href="URL.base + '/owner/register'" class="btn btn-darkblue btn-size-360">Create Free Account</a>
            </div>
        </article>

        <article class="article-modal">
            <h2 class="article-title mb-4">Captains<span v-if="city && state"> Near {{ city }}, {{ state }}</span></h2>
            <h5 class="article-subtitle">This is the paragraph where you can write more details about the service.
                <br/> Keep users engaged by providing meaningful information</h5>
            <div class="row">
                <div class="col-sm-4 article-item" v-for="captain in captainList">
                    <div class="captain-face mx-auto mb-3">
                        <img v-if="captain.avatar" :src="URL.base + '/public/images/avatars/' + captain.avatar" />
                        <img v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                    </div>
                    <h4>
                        <a class="link-underline" :href="URL.base + '/' + captain.id +'/captain-bio'">Capt. {{ captain.firstName }}</a>
                    </h4>
                    <h6 class="article-item-subtitle font-italic mb-2">{{ captain.city }}, {{ captain.state }}</h6>
                    <rating-bar v-bind:param="{'value': captain.rating}"></rating-bar>
                </div>
            </div>
            <br/>
            <div class="text-center">
                <a :href="URL.base + '/captain/register'" class="btn btn-darkblue btn-size-360">Become a Captain</a>
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
                captainList: [],
                latlng: null,
                city: this.info.city,
                state: this.info.state,
                working: false
            }
        },
        mounted() {  
            var vm = this
            this.$nextTick(function () {
                if(typeof google !== "undefined")
                    google.maps.event.addDomListener(window, 'load', vm.initialize);  
                else
                    vm.getCaptainList();                    
            })            
        },

        created() {
            var prevScrollpos = window.pageYOffset;
            window.onscroll = function() {
                var currentScrollPos = window.pageYOffset;
                if(currentScrollPos > 200) {
                    $(".fixed-top").removeClass("hide");
                } else {
                    $(".fixed-top").addClass("hide");
                }
                prevScrollpos = currentScrollPos;
            }
        },

        methods: {
            getCaptainList() {
                this.working = true;
                var vm = this;
                var param = {
                    latlng: this.latlng,
                    limit: 3
                }
                this.$http.post(this.URL.base + '/captain-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.captainList = responseData.captainList;
                    if(vm.captainList.length == 0 && vm.latlng != null)
                    {
                        vm.city = null;
                        vm.state = null;
                        vm.latlng = null;
                        vm.getCaptainList();
                    }
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            initialize() {
                var vm = this;
                var input = document.getElementById('search_input');
                var autocomplete = new google.maps.places.Autocomplete(input);

                if(vm.info.city == null && vm.info.state == null)
                {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };

                            var geocoder = new google.maps.Geocoder;
                            vm.latlng = {lat: pos['lat'], lng: pos['lng']};
                            
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

                            vm.getCaptainList(); 
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
