
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

var Vue = require('vue');
Vue.prototype.$eventHub = new Vue(); // Global event bus

import Transitions from 'vue2-transitions'
Vue.use(Transitions)


require('./bootstrap');
require('./bootstrap-datetimepicker');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('lander', require('./components/Lander.vue'));
Vue.component('owner-dashboard', require('./components/owner/Dashboard.vue'));
Vue.component('owner-menu', require('./components/owner/PageMenu.vue'));
Vue.component('owner-book-captain', require('./components/owner/BookCaptain.vue'));
Vue.component('owner-book-captain-confirm', require('./components/owner/BookCaptainConfirm.vue'));
Vue.component('owner-request-captain', require('./components/owner/RequestCaptain.vue'));
Vue.component('owner-view-bid', require('./components/owner/ViewBid.vue'));
Vue.component('owner-booking', require('./components/owner/Booking.vue'));
Vue.component('owner-booking-complete', require('./components/owner/BookingComplete.vue'));
Vue.component('owner-trips', require('./components/owner/Trips.vue'));
Vue.component('owner-trip-detail', require('./components/owner/TripDetail.vue'));
Vue.component('owner-profile', require('./components/owner/Profile.vue'));
Vue.component('owner-billing', require('./components/owner/Billing.vue'));
Vue.component('owner-reviews', require('./components/owner/Reviews.vue'));
Vue.component('owner-leave-review', require('./components/owner/LeaveReview.vue'));
Vue.component('contact-captain', require('./components/owner/ContactCaptain.vue'));

Vue.component('captain-dashboard', require('./components/captain/Dashboard.vue'));
Vue.component('captain-menu', require('./components/captain/PageMenu.vue'));
Vue.component('captain-trips', require('./components/captain/Trips.vue'));
Vue.component('captain-trip-detail', require('./components/captain/TripDetail.vue'));
Vue.component('captain-profile', require('./components/captain/Profile.vue'));
Vue.component('captain-billing', require('./components/captain/Billing.vue'));
Vue.component('captain-reviews', require('./components/captain/Reviews.vue'));
Vue.component('captain-leave-review', require('./components/captain/LeaveReview.vue'));
Vue.component('contact-owner', require('./components/captain/ContactOwner.vue'));
Vue.component('bid-request-detail', require('./components/captain/BidRequestDetail.vue'));
Vue.component('create-bid', require('./components/captain/CreateBid.vue'));
Vue.component('bid-submitted', require('./components/captain/BidSubmitted.vue'));

Vue.component('admin-dashboard', require('./components/admin/Dashboard.vue'));
Vue.component('admin-menu', require('./components/admin/PageMenu.vue'));
Vue.component('admin-trips', require('./components/admin/Trips.vue'));
Vue.component('admin-revenue', require('./components/admin/Revenue.vue'));
Vue.component('admin-capts', require('./components/admin/Captains.vue'));
Vue.component('admin-owners', require('./components/admin/Owners.vue'));
Vue.component('admin-reviews', require('./components/admin/Reviews.vue'));
Vue.component('admin-payments', require('./components/admin/Payments.vue'));
Vue.component('admin-fees', require('./components/admin/Fees.vue'));
Vue.component('admin-nets', require('./components/admin/Nets.vue'));
Vue.component('admin-captain-profile', require('./components/admin/CaptainProfile.vue'));
Vue.component('admin-captain-reviews', require('./components/admin/CaptainReviews.vue'));
Vue.component('admin-owner-profile', require('./components/admin/OwnerProfile.vue'));
Vue.component('admin-owner-reviews', require('./components/admin/OwnerReviews.vue'));

Vue.component('login', require('./components/Login.vue'));
Vue.component('regist', require('./components/Regist.vue'));
Vue.component('about', require('./components/About.vue'));
Vue.component('contact', require('./components/Contact.vue'));
Vue.component('terms', require('./components/Terms.vue'));
Vue.component('forgot-password', require('./components/ForgotPassword.vue'));
Vue.component('reset-password', require('./components/ResetPassword.vue'));
Vue.component('find-captains', require('./components/FindCaptains.vue'));
Vue.component('captain-bio', require('./components/CaptainBio.vue'));
Vue.component('captain-bio-reviews', require('./components/CaptainBioReviews.vue'));
Vue.component('page-header', require('./components/PageHeader.vue'));
Vue.component('page-footer', require('./components/PageFooter.vue'));
Vue.component('page-menu', require('./components/PageMenu.vue'));

Vue.component('captain-item', require('./components/CaptainItem.vue'));
Vue.component('trip-item', require('./components/TripItem.vue'));

Vue.component('working-indicator', {
    props: ['param'],
    template:
        '<div class="workingIndicator__wrap" transition="fade">' +
        '<div class="workingIndicator"></div>' +
        '</div>'
});

Vue.component('rating-bar', {
    props: ['param'],
    template:
        '<span :class="[\'rating-bar\', param.size && \'rating-bar-\'+param.size]" data-mark="anchor" :data-value="param.value">' +
        '<span class="rating-item" data-value="1"></span>' +
        '<span class="rating-item" data-value="2"></span>' +
        '<span class="rating-item" data-value="3"></span>' +
        '<span class="rating-item" data-value="4"></span>' +
        '<span class="rating-item" data-value="5"></span>' +
        '</span>'
});

Vue.component('article-close', {
    props: ['link'],
    template: '\
        <a class="btn-close-article" :href="link">\
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 42" width="42" height="42" stroke="#0883ee" stroke-width="2">\
                <circle cx="21" cy="21" r="20" fill="none" />\
                <line x1="13" y1="13" x2="29" y2="29" />\
                <line x1="29" y1="13" x2="13" y2="29" />\
            </svg>\
        </a>\
    '
});

Vue.filter('filterComma', function (value) {
    if (!value) return '0'
    var result = "";
    var s = (parseInt(value)).toString();
    var len = s.length;
    var k = 0;
    for (var i = 0; i < len; i++) {
        k++;
        if (k > 3) {
            result = "," + result;
            k = 1;
        }
        result = s.charAt(len - i - 1) + result;
    }
    return result;
});

Vue.directive('sup', function (el, binding) {
    var value=parseInt(binding.value);
    if (!value) value = 0;
    var prefix=el.dataset.prefix;
    if(!prefix)prefix='$';
    var suffix=el.dataset.suffix;
    if(!suffix)suffix='';
    var result = "";
    var s = (parseInt(value / 100)).toString();
    var len = s.length;
    var k = 0;
    for (var i = 0; i < len; i++) {
        k++;
        if (k > 3) {
            result = "," + result;
            k = 1;
        }
        result = s.charAt(len - i - 1) + result;
    }
    var c = value % 100;
    if (c < 10) c = '0' + c;
    el.innerHTML =  prefix + result + '.<sup>' + c + '</sup>' + suffix;
});

Vue.directive('br', function (el, binding) {
    el.innerHTML = binding.value.replace(/\r\n/g, '<br/>').replace(/\n/g, '<br/>');
});

Vue.directive('readmore', function (el, binding) {
    var threshold = parseInt(el.dataset.index);
    el.classList.remove("show");
    var html = binding.value || '';
    var initialLength = html.length;
    if (!html.includes('<span class="span-read-more-show">') && initialLength > threshold + 20) {
        var i = 0;
        var lineCount = 0;
        var tagOpened = false;
        while (i < initialLength) {
            var c = html.charAt(i);
            if (c === '<') tagOpened = true;
            else if (c === '>') tagOpened = false;
            // else if (c === '\n') {
            //     lineCount++;
            //     if (lineCount === 2) {
            //         threshold = i + 32;
            //     }
            // }
            else if (i >= threshold && !tagOpened && c === ' ') break;
            i++;
        }
        var showHtml = html.substring(0, i);
        var hideHtml = html.substring(i);
        html = showHtml +
            '<span class="span-read-more-show">... <a class="link-underline" href="#read-more">Read more</a></span><span class="span-read-more-hidden">' +
            hideHtml +
            '</span>';
    }
    el.innerHTML = html.replace(/\r\n/g, '<br/>').replace(/\n/g, '<br/>');
    $(el).find("[href='#read-more']").click(function (e) {
        e.preventDefault();
        $(this).closest(".text-read-more").addClass("show");
    });
});

Vue.config.productionTip = false
const app = new Vue({
    el: '#app'
});

