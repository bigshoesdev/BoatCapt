<template>
    <section>
        <article class="article-page container-768">
            <h2 class="article-title mb-3">
                {{userName}}’s Profile                       
            </h2>
            <h5 class="article-subtitle">
                <span v-if="profile.city && profile.state">{{profile.city}}, {{profile.state}} |</span>
                <a :href="URL.base + '/captain-billing'" class="link-underline">Billing Profile</a>
            </h5>   
            <div class="captain-view">
                <div class="captain-face captain-face-220 m-auto">
                    <img class="img-avatar" v-if="profile.avatar" :src="URL.base + '/public/images/avatars/' + profile.avatar" />
                    <img class="img-avatar" v-else :src="URL.base + '/public/images/default-avatar.jpg'" />
                </div>
                <div class="aside-left">
                    <template v-if="profile.firstResponder">
                        <p>
                            <img :src="URL.base + '/public/images/responder.png'"/> First Responder
                        </p>
                    </template>
                    <template v-if="profile.maritimeGrad">
                        <p>
                            <img :src="URL.base + '/public/images/grad.png'"/> Maritime Grad
                        </p>
                    </template>                    
                </div>
                <div class="aside-right">
                    <template v-if="profile.militaryVeteran">
                        <p>
                            <img :src="URL.base + '/public/images/veteran.png'"/> Military/Veteran
                        </p>
                    </template>
                    <template v-if="profile.drugFree">
                        <p>
                            <img :src="URL.base + '/public/images/drug.png'"/> Drug Free
                        </p>
                    </template> 
                </div>
            </div>
            <br />
            <div class="text-center mt-2" v-if="profile.rating">
                <rating-bar v-bind:param="{'value':profile.rating,'size':'md'}"></rating-bar>
            </div>
            <div class="h22 text-center mt-2">
                <a class="link-underline" :href="URL.base + '/captain-reviews'">{{ profile.reviews | filterComma }} Reviews</a>
            </div>
            <br />
            <br />
            <form class="form-md container-440" :action="URL.base + '/update-captain-profile'" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="_token" :value="token">
                <h4 class="article-title mb-4">Account Profile</h4>
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
                        <input v-model="profile.uscgLicense" name="uscgLicense" type="text" class="form-control" placeholder="USCG License number..." />
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="div-select">
                            <select name="licenseTonnage" v-model="profile.licenseTonnage" class="custom-select custom-select-lg" required="required">
                                <option selected="selected" hidden="hidden" disabled="disabled" value="null">License Tonnage Class</option>
                                <option value="6-pack">6-pack</option>
                                <option value="50_ton">50 ton</option>
                                <option value="100_ton">100 ton</option>
                                <option value="200_ton">200 ton</option>
                                <option value="1600_ton">1600 ton</option>
                                <option value="Other">Other</option>
                            </select>
                            <button type="button" class="btn btn-pure dropdown-toggle">
                            </button>
                        </div>
                    </div>
                </div>
                <h4 class="text-center font-weight-bold  mt-4 mb-3">Are you any of the following?</h4>
                <div class="row ml-3">
                    <div class="col">
                        <div class="custom-control custom-checkbox min-width-160 my-1">
                            <input v-model="profile.firstResponder" name="firstResponder" type="checkbox" class="custom-control-input" id="check-police-fire-ems">
                            <label class="custom-control-label text-nowrap" for="check-police-fire-ems">Police/Fire/EMS</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-control custom-checkbox min-width-160 my-1">
                            <input v-model="profile.militaryVeteran" name="militaryVeteran" type="checkbox" class="custom-control-input" id="check-active-military-veteran">
                            <label class="custom-control-label text-nowrap" for="check-active-military-veteran">Active Military/Veteran</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-control custom-checkbox min-width-160 my-1">
                            <input v-model="profile.maritimeGrad" name="maritimeGrad" type="checkbox" class="custom-control-input" id="check-maritime-academy">
                            <label class="custom-control-label text-nowrap" for="check-maritime-academy">Maritime Academy</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-control custom-checkbox min-width-160 my-1">
                            <input v-model="profile.drugFree" name="drugFree" type="checkbox" class="custom-control-input" id="check-drug-free-certified">
                            <label class="custom-control-label text-nowrap" for="check-drug-free-certified">Drug Free Certified</label>
                        </div>
                    </div>
                </div>
                <h4 class="text-center font-weight-bold mt-4 mb-3">Describe your boating experience:</h4>
                <div class="row">
                    <div class="col">
                        <textarea v-model="profile.describe" name="describe" class="form-control fontsize-md"></textarea>
                    </div>
                </div>
                <br />
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
                    uscgLicense: this.oldInput && this.oldInput.uscgLicense ? this.oldInput.uscgLicense : this.userInfo.uscgLicense,
                    licenseTonnage: this.oldInput && this.oldInput.licenseTonnage ? this.oldInput.licenseTonnage : this.userInfo.licenseTonnage,                                             
                    firstResponder: this.oldInput && this.oldInput.firstResponder ? this.oldInput.firstResponder : this.userInfo.firstResponder,   
                    militaryVeteran: this.oldInput && this.oldInput.militaryVeteran ? this.oldInput.militaryVeteran : this.userInfo.militaryVeteran,                 
                    maritimeGrad: this.oldInput && this.oldInput.maritimeGrad ? this.oldInput.maritimeGrad : this.userInfo.maritimeGrad,                    
                    drugFree: this.oldInput && this.oldInput.drugFree ? this.oldInput.drugFree : this.userInfo.drugFree,
                    describe: this.oldInput && this.oldInput.describe ? this.oldInput.describe : this.userInfo.describe,                    
                }
            }
        },
        mounted() {    
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
