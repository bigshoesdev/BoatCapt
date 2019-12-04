<template>
    <footer>
        <p class="footer-title">BoatCaptain</p>
        <p class="footer-content">BoatCaptain, LLC
            <br/> Anytown, New York 22123
            <br/> (212) 555-1234</p>
        <p class="footer-link">
            <a href="#" class="fa fa-facebook-square"></a>
            <a href="#" class="fa fa-twitter"></a>
            <a href="#" class="fa fa-instagram"></a>
        </p>
    </footer>
</template>

<script>
    export default {
        mounted() {   
            $(".btn-toggle-menu,.aside-overlay").click(function(){
                $("body").toggleClass("menu");
            })         
        }
    }
</script>
