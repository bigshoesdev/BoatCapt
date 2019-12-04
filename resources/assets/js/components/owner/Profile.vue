<template>
    <section>
        <article class="article-page container-768">
            <h2 class="article-title mb-3">
                {{userName}}’s Profile                
            </h2>
            <h5 class="article-subtitle">
                <span v-if="profile.city && profile.state">{{profile.city}}, {{profile.state}} |</span>
                <a :href="URL.base + '/owner-billing'" class="link-underline">Billing Profile</a>
            </h5>
            <div class="captain-view">
                <div class="captain-face captain-face-220 m-auto">
                    <img class="img-avatar" v-if="profile.avatar" :src="URL.base + '/public/images/avatars/' + profile.avatar" />
                    <img class="img-avatar" v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                </div>
                <div class="aside-left">
                    <template v-if="profile.towCoverage">
                        <p>
                            <img :src="URL.base + '/public/images/grad.png'" /> Tow Coverage
                        </p>
                    </template>                    
                </div>
                <div class="aside-right">
                    <template v-if="profile.boatInsurance">
                        <p>
                            <img :src="URL.base + '/public/images/veteran.png'" /> Boat Insurance
                        </p>
                    </template>
                    <template v-if="profile.validSafetyGear">
                        <p>
                            <img :src="URL.base + '/public/images/drug.png'" /> Valid Safety Gear
                        </p>
                    </template>                    
                </div>
            </div>
            <br />
            <div class="text-center mt-2" v-if="profile.rating">
                <rating-bar v-bind:param="{'value':profile.rating,'size':'md'}"></rating-bar>
            </div>
            <div class="h22 text-center mt-2">
                <a class="link-underline" :href="URL.base + '/owner-reviews'">{{ profile.reviews | filterComma }} Reviews</a>
            </div>
            <br/>
            <br/>
            <form class="form-md container-440" :action="URL.base + '/update-owner-profile'" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="_token" :value="token">
                <h4 class="article-title mb-3">Account Profile</h4>
                <div class="row">
                    <div class="col mb-1">
                        <div :class="['alert', 'alert-dismissible', message.status == 'success' ? 'alert-success' : 'alert-danger']" v-if="message">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <div v-for="msg in message.body">
                                {{msg}}
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="custom-file">
                            <input name="avatar" type="file" class="form-control custom-file-input" id="customFile" @change="changePhoto" accept="image/*">
                            <label class="form-control form-control-md custom-file-label" for="customFile">Change photo...</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input v-model="profile.firstName" name="firstName" type="text" class="form-control" placeholder="First name..." />
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input v-model="profile.lastName" name="lastName" type="text" class="form-control" placeholder="Last name..." />
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input v-model="profile.email" name="email" type="email" class="form-control" placeholder="Email address..." required="required" />
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input v-model="profile.phone" name="phone" type="text" class="form-control" placeholder="Phone number..." />
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group-location">
                            <input id="fullAddress" v-model="profile.fullAddress" name="fullAddress" type="text" class="form-control" placeholder="Your location..." />
                            <span class="right-icon fa fa-map-marker"></span>
                        </div>
                    </div>
                </div>
                <div class="row" v-show="false">
                    <div class="col">
                        <input id="street_number" v-model="profile.address" name="address" type="text" class="form-control" placeholder="Address..." />
                    </div>
                </div>
                <div class="row" v-show="false">
                    <div class="col">
                        <input id="route" v-model="profile.address2" name="address2" type="text" class="form-control" placeholder="Address line 2..." />
                    </div>
                </div>
                <div class="row" v-show="false">
                    <div class="col">
                        <input id="locality" v-model="profile.city" name="city" type="text" class="form-control" placeholder="City..." />
                    </div>
                </div>
                <div class="row" v-show="false">
                    <div class="col">
                        <input id="administrative_area_level_1" v-model="profile.state" name="state" type="text" class="form-control" placeholder="State..." />
                    </div>
                </div>
                <div class="row" v-show="false">
                    <div class="col">
                        <input id="country" v-model="profile.country" name="country" type="text" class="form-control" placeholder="Country..." />
                    </div>
                </div>                
                <div class="row" v-show="false">
                    <div class="col">
                        <input id="postal_code" v-model="profile.zipcode" name="zipcode" type="text" class="form-control" placeholder="Zip code..." />
                    </div>
                </div>
                <div class="row" v-show="false">
                    <div class="col">
                        <input id="lat" v-model="profile.lat" name="lat" type="text" class="form-control" placeholder="Latitude..." />
                    </div>
                </div>
                <div class="row" v-show="false">
                    <div class="col">
                        <input id="lon" v-model="profile.lon" name="lon" type="text" class="form-control" placeholder="Longitude..." />
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="div-select">
                            <select v-model="profile.boatClass" name="boatClass" class="custom-select custom-select-lg">
                                <option hidden="hidden" disabled="disabled" value="null">Boat class...</option>
                                <option value="TBD">TBD</option>
                            </select>
                            <button type="button" class="btn btn-pure dropdown-toggle">
                            </button>
                        </div>
                    </div>
                    <div class="col">
                        <input v-model="profile.boatModel" name="boatModel" type="text" class="form-control min-width-160" placeholder="Boat model..." />
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="div-select">
                            <select v-model="profile.boatYear" name="boatYear" class="custom-select custom-select-lg">
                                <option selected="selected" hidden="hidden" disabled="disabled" value="null">Boat year...</option>
                                <template v-for="year in years">
                                    <option :value="year">{{year}}</option>
                                </template>
                            </select>
                            <button type="button" class="btn btn-pure dropdown-toggle">
                            </button>
                        </div>
                    </div>
                    <div class="col">
                        <input v-model="profile.draft" name="draft" type="text" class="form-control min-width-160" placeholder="Draft..." />
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input v-model="profile.length" name="length" type="text" class="form-control min-width-160" placeholder="Length..." />
                    </div>
                    <div class="col">
                        <input v-model="profile.beam" name="beam" type="text" class="form-control min-width-160" placeholder="Beam..." />
                    </div>
                </div>
                <h4 class="text-center font-weight-bold  mt-4 mb-3">Do you have the following?</h4>
                <div class="row ml-3">
                    <div class="col">
                        <div class="custom-control custom-checkbox min-width-160 my-1">
                            <input v-model="profile.boatInsurance" name="boatInsurance" type="checkbox" class="custom-control-input" id="check-boat-insurance">
                            <label class="custom-control-label" for="check-boat-insurance">Boat Insurance</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-control custom-checkbox min-width-160 my-1">
                            <input v-model="profile.towCoverage" name="towCoverage" type="checkbox" class="custom-control-input" id="check-tow-coverage">
                            <label class="custom-control-label" for="check-tow-coverage">Tow Coverage</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-control custom-checkbox min-width-160 my-1">
                            <input v-model="profile.validSafetyGear" name="validSafetyGear" type="checkbox" class="custom-control-input" id="check-valid-safety-gear">
                            <label class="custom-control-label" for="check-valid-safety-gear">Valid Safety Gear</label>
                        </div>
                    </div>
                </div>
                <h4 class="text-center font-weight-bold mt-4 mb-3">Describe the boat's electronics:</h4>
                <div class="row">
                    <div class="col">
                        <textarea v-model="profile.describe" name="describe" class="form-control"></textarea>
                    </div>
                </div>
                <br/>
                <div class="text-center mt-1">
                    <button type="submit" class="btn btn-darkblue btn-size-360">Update Profile</button>
                </div>
            </form>
        </article>
    </section>
</template>

<script>
    export default {

        props: ['message', 'userInfo', 'userName', 'oldInput'],

        data() {
            return {
                URL: window.URL,
                token: window.token,
                years: [],
                profile: {                    
                    firstName: this.oldInput && this.oldInput.firstName ? this.oldInput.firstName : this.userInfo.firstName,
                    lastName: this.oldInput && this.oldInput.lastName ? this.oldInput.lastName : this.userInfo.lastName,
                    email: this.oldInput && this.oldInput.email ? this.oldInput.email : this.userInfo.email,
                    phone: this.oldInput && this.oldInput.phone ? this.oldInput.phone : this.userInfo.phone,
                    fullAddress: this.oldInput && this.oldInput.fullAddress ? this.oldInput.fullAddress : this.userInfo.fullAddress,
                    address: this.oldInput && this.oldInput.address ? this.oldInput.address : this.userInfo.address,
                    address2: this.oldInput && this.oldInput.address2 ? this.oldInput.address2 : this.userInfo.address2,
                    city: this.oldInput && this.oldInput.city ? this.oldInput.city : this.userInfo.city,
                    country: this.oldInput && this.oldInput.country ? this.oldInput.country : this.userInfo.country,
                    state: this.oldInput && this.oldInput.state ? this.oldInput.state : this.userInfo.state,
                    zipcode: this.oldInput && this.oldInput.zipcode ? this.oldInput.zipcode : this.userInfo.zipcode,
                    lat: this.oldInput && this.oldInput.lat ? this.oldInput.lat : this.userInfo.lat,
                    lon: this.oldInput && this.oldInput.lon ? this.oldInput.lon : this.userInfo.lon,
                    boatClass: this.oldInput && this.oldInput.boatClass ? this.oldInput.boatClass : this.userInfo.boatClass,
                    boatModel: this.oldInput && this.oldInput.boatModel ? this.oldInput.boatModel : this.userInfo.boatModel,
                    boatYear: this.oldInput && this.oldInput.boatYear ? this.oldInput.boatYear : this.userInfo.boatYear,
                    draft: this.oldInput && this.oldInput.draft ? this.oldInput.draft : this.userInfo.draft,
                    length: this.oldInput && this.oldInput.length ? this.oldInput.length : this.userInfo.length,
                    beam: this.oldInput && this.oldInput.beam ? this.oldInput.beam : this.userInfo.beam,
                    boatInsurance: this.oldInput && this.oldInput.boatInsurance ? this.oldInput.boatInsurance : this.userInfo.boatInsurance,   
                    towCoverage: this.oldInput && this.oldInput.towCoverage ? this.oldInput.towCoverage : this.userInfo.towCoverage,                 
                    validSafetyGear: this.oldInput && this.oldInput.validSafetyGear ? this.oldInput.validSafetyGear : this.userInfo.validSafetyGear,      
                    describe: this.oldInput && this.oldInput.describe ? this.oldInput.describe : this.userInfo.describe,                    
                }
            }
        },
        mounted() {   
            
            for(var i = new Date().getFullYear(); i >= 1900; i--)
            {
                this.years.push(i);
            }     
            if(typeof google !== "undefined")
                google.maps.event.addDomListener(window, 'load', this.initialize);    
        },

        created() {
        },

        methods: {
            changePhoto(e) {
                var filename=e.target.files[0].name;
                $(e.target).next("label").text(filename);
                $(e.target).next("label").css('color', '#495057');
                //if (event.target.files == undefined || event.target.files[0] == undefined) return;
                var imageReader = new FileReader();
                imageReader.readAsDataURL(e.target.files[0]);
                imageReader.onload = function (e) {
                    $(".img-avatar").attr("src",e.target.result);
                }
            },

            initialize() {
                var vm = this;
                var input = document.getElementById('fullAddress');
                var autocomplete = new google.maps.places.Autocomplete(input);
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    var place = autocomplete.getPlace();
                    for (var i = 0; i < place.address_components.length; i++) {
                        var addressType = place.address_components[i].types[0];

                        if (addressType == 'street_number') {
                            vm.profile.address = place.address_components[i]['short_name'];
                        }

                        if (addressType == 'route') {
                            vm.profile.address2 = place.address_components[i]['long_name'];
                        }

                        if (addressType == 'locality') {
                            vm.profile.city = place.address_components[i]['long_name'];
                        }

                        if (addressType == 'administrative_area_level_1') {
                            vm.profile.state = place.address_components[i]['short_name'];
                        }

                        if (addressType == 'country') {
                            vm.profile.country = place.address_components[i]['short_name'];
                        }

                        if (addressType == 'postal_code') {
                            vm.profile.zipcode = place.address_components[i]['short_name'];
                        }

                    }

                    vm.profile.lat = place.geometry.location.lat();
                    vm.profile.lon = place.geometry.location.lng();

                });
            }
        }
    }
</script>
