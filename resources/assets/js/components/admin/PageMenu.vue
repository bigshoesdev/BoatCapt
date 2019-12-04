<template>
    <aside>
        <div>
            <p class="aside-title">BoatCaptain</p>
            <button type="button" class="btn btn-pure btn-toggle-menu">&times;</button>
            <ul class="nav flex-column align-items-center mt-5">
                <li class="nav-item">
                    <a class="nav-link nav-link-big" :href="URL.base + '/dashboard'">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-big" :href="URL.base + '/admin-trips'">Trips</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-big" :href="URL.base + '/admin-revenue'">Revenue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-big" :href="URL.base + '/admin-capts'">Capts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-big" :href="URL.base + '/admin-owners'">Owners</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-big" :href="URL.base + '/admin-reviews'">Reviews</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-big" :href="URL.base + '/admin-payments'">Payments</a>
                </li>                
                <li class="nav-item">
                    <a class="nav-link nav-link-big" :href="URL.base + '/admin-fees'">Fees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-big" :href="URL.base + '/admin-nets'">Net</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-big" :href="URL.base + '/contact'">Contact</a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link" :href="URL.base + '/terms'">Terms of Use</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Cacellation Policy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Privacy Policy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :href="URL.base + '/logout'">Logout</a>
                </li>
                <li class="nav-item">
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <img :src="URL.base + '/public/images/anchor-big.png'"/>
                </li>
            </ul>
        </div>
    </aside>
</template>

<script>
    export default {
        data() {
            return {
                URL: window.URL
            }
        },
        mounted() {            
        },
        created() {
        },
    }
</script>