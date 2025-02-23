@extends('client.layouts.master')
@section('content')
@include('client.layouts.partials.lelf-navbar')

<div class="dashbord-wrapper ml-110 mt-100">
    <div class="container">
        <div class="row">
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12">
                <div class="dashbord-switcher">
                    <a href="dashboard.html"><i class="flaticon-dashboard"></i> Dashboard</a>
                    <a href="profile.html"><i class="flaticon-user"></i> My Profile</a>
                    <a href="order.html" class="active"><i class="flaticon-shopping-bag"></i> My Order</a>
                    <a href="setting.html"><i class="flaticon-settings"></i> Account Setting</a>
                    <a href="#"><i class="flaticon-logout"></i> Logout</a>
                </div>
            </div>
            <div class="col-xxl-8 col-xl-8 col-lg-8">
                <div class="order-details">
                    <table class="table order-table mb-0">
                        <thead>
                            <tr>

                                <th scope="col" class="order-id">Order ID</th>
                                <th scope="col" class="order-date">Order Date</th>
                                <th scope="col" class="order-status">Status</th>
                                <th scope="col" class="order-amount">Total</th>
                                <th scope="col" class="order-active">Active</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="order-id">#56986</td>
                                <td class="order-date">21 August 2021</td>
                                <td class="order-status">Pending</td>
                                <td class="order-amount">$ 985.23 for 85 Items</td>
                                <td class="order-active"><a href="#"><i class="flaticon-visibility"></i></a></td>
                            </tr>

                            <tr>
                                <td class="order-id">#56987</td>
                                <td class="order-date">25 April 2021</td>
                                <td class="order-status"> Picked</td>
                                <td class="order-amount"> $ 985.23 for 85 Items </td>
                                <td class="order-active"> <a href="#"><i class="flaticon-visibility"></i></a></td>
                            </tr>

                            <tr>
                                <td class="order-id">#56988</td>
                                <td class="order-date">3rd June 2021</td>
                                <td class="order-status">Completed</td>
                                <td class="order-amount">$ 985.23 for 85 Items</td>
                                <td class="order-active"><a href="#"><i class="flaticon-visibility"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ===============  newslatter area start  =============== -->
<div class="newslatter-area ml-110 mt-100">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="newslatter-wrap text-center">
                    <h5>Connect To EG</h5>
                    <h2 class="newslatter-title">Join Our Newsletter</h2>
                    <p>Hey you, sign up it only, Get this limited-edition T-shirt Free!</p>

                    <form action="#" method="POST">
                        <div class="newslatter-form">
                            <input type="text" placeholder="Type Your Email">
                            <button type="submit">Send <i class="bi bi-envelope-fill"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ===============  newslatter area end  =============== -->
@endsection