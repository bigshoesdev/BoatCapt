<template>
    <div :class="['fixed-top', dark ? 'navbar-shadow-dark' : 'navbar-shadow-light', param.hide && 'hide']">
        <nav class="navbar navbar-expand-sm">
            <a class="navbar-brand" :href="URL.base">BoatCaptain</a>
            <form :action="URL.base + '/find-captains'" class="input-group div-search" v-if="param.searchable" method="GET">
                <input id="search" name="search" v-model="param.search" type="text" class="form-control input-search" placeholder="Departure address...">
                <div class="input-group-append">
                    <button class="btn btn-primary fa fa-search" type="submit"></button>
                </div>
            </form>
            <div v-if="!param.searchable" class="div-search"></div>
            <template v-if="param.login">
                <a class="link-avatar-circle" :href="URL.base + '/dashboard'" v-if="param.avatar">
                    <img :src="URL.base + '/public/images/avatars/' + param.avatar" />
                </a>
                <a class="link-avatar-circle" :href="URL.base + '/dashboard'" v-else>
                    <img :src="URL.base + '/public/images/default-avatar.jpg'" />
                </a>
            </template>            
            <button type="button" class="btn btn-pure btn-toggle-menu">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewbox="0 0 36 22" width="36" height="22"
                 fill="#0883ed">
                    <rect y="0" width="36" height="4" />
                    <rect y="9" width="36" height="4" />
                    <rect y="18" width="36" height="4" />
                </svg>
            </button>
        </nav>
    </div>
</template>

<script>
    export default {

        props: ['param', 'dark'],

        data() {
            return {
                URL: window.URL,
            }
        },
        mounted() {    
            var vm = this
            this.$nextTick(function () {
                if(typeof google !== "undefined")
                    google.maps.event.addDomListener(window, 'load', vm.initialize);                   
            })          
        },
        created() {
        },
        methods: {
            initialize() {
                var input = document.getElementById('search');
                var autocomplete = new google.maps.places.Autocomplete(input);
            }
        }
    }
</script>
