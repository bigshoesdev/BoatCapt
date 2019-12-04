@extends('templates.email')

@section('title', 'Booking Complete')
@section('content')
    <article class="article-page">
        <h2 class="article-title mb-3">Booking Complete!</h2>
        <h5 class="article-subtitle container-640">An email confirmation will be sent to owner@owner.com with the trip
            details, and you can contact your caption to make any
            final arrangements:</h5>
        <div class="captain-face captain-face-220 m-auto"><img src="http://192.168.99.181:7070/public/images/avatars/profile-2.jpg"></div>
        <h3 class="article-title mt-5 mb-3">Captain Howard Stern</h3>
        <h4 class="article-subtitle font-italic mb-3">Collins Avenue, FL</h4>
        <div class="text-center mb-3"><span data-mark="anchor" data-value="3" class="rating-bar"><span data-value="1"
                    class="rating-item"></span><span data-value="2" class="rating-item"></span><span data-value="3"
                    class="rating-item"></span><span data-value="4" class="rating-item"></span><span data-value="5"
                    class="rating-item"></span></span></div>
        <table class="table-pair-detail h4">
            <tbody>
                <tr>
                    <th>Phone:</th>
                    <td>123-4567</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>captain@captain.com</td>
                </tr>
            </tbody>
        </table>
    </article>
@endsection