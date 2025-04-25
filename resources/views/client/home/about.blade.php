@extends('client.layouts.master')

@section('content')
<div class="breadcrumb-area ml-110">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-bg d-flex justify-content-center align-items-center">
                    <div class="breadcrumb-shape1 position-absolute top-0 end-0">
                        <img src="assets/images/shapes/bs-right.png" alt="">
                    </div>
                    <div class="breadcrumb-shape2 position-absolute bottom-0 start-0">
                        <img src="assets/images/shapes/bs-left.png" alt="">
                    </div>
                    <div class="breadcrumb-content text-center">
                        <h2 class="page-title">Về chúng tôi</h2>
                        <ul class="page-switcher d-flex ">
                            <li><a href="{{ route('client.index') }}">Trang chủ</a> <i class="flaticon-arrow-pointing-to-right"></i></li>
                            <li>Về chúng tôi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ===============Khu vực breadcrumb kết thúc=============== -->

<div class="about-wrapper mt-100 ml-110">
    <div class="container">
        <div class="about-row1">
            <div class="row align-items-center">
                <div class="col-xxl-7 col-xl-7">
                    <div class="about-details">
                        <h5>Về chúng tôi</h5>
                        <h2>Tất cả về Manso</h2>
                        <p>Chúng tôi tự hào mang đến trải nghiệm mua sắm tuyệt vời với chất lượng sản phẩm hàng đầu. Đội ngũ của chúng tôi luôn nỗ lực đổi mới và cung cấp những sản phẩm độc đáo, đáp ứng nhu cầu của bạn.</p>
                        <p>“Chúng tôi tin rằng mỗi sản phẩm không chỉ là một món hàng, mà còn là câu chuyện và cảm hứng. Sự hài lòng của khách hàng là động lực để chúng tôi không ngừng cải tiến và mang đến những giá trị tốt nhất.”</p>

                        <div class="row">
                            <div class="col-xxl-12">
                                <div class="about-service-wrap d-flex text-center">
                                    <div class="about-single-service">
                                        <div class="about-service-icon"><i class="flaticon-worldwide-shipping"></i></div>
                                        <h5>Giao hàng toàn cầu</h5>
                                    </div>
                                    <div class="about-single-service">
                                        <div class="about-service-icon"><i class="flaticon-exchange-1"></i></div>
                                        <h5>Chính sách đổi trả trong 14 ngày</h5>
                                    </div>
                                    <div class="about-single-service">
                                        <div class="about-service-icon"><i class="flaticon-credit-card"></i></div>
                                        <h5>Thanh toán bảo mật hàng đầu</h5>
                                    </div>
                                    <div class="about-single-service">
                                        <div class="about-service-icon"><i class="flaticon-delivery"></i></div>
                                        <h5>Giao hàng tận nhà miễn phí</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-5 col-xl-5">
                    <div class="about-feature-img">
                        <img src="/client/assets/images/about/about-x1.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
        <div class="about-row2 mt-100">
            <div class="row justify-content-center">
                <div class="col-xxl-12">
                    <div class="about-history-wrap text-center">
                        <h2>Lịch sử của chúng tôi</h2>
                        <p>Manso được thành lập với sứ mệnh mang đến những sản phẩm chất lượng và dịch vụ tuyệt vời. Qua nhiều năm, chúng tôi đã không ngừng phát triển, mở rộng quy mô và xây dựng niềm tin từ khách hàng trên toàn thế giới. Mỗi bước đi là một cột mốc, đánh dấu sự cam kết của chúng tôi trong việc đổi mới và phục vụ tốt hơn.</p>
                        <p>Chúng tôi luôn lắng nghe và cải tiến để đáp ứng nhu cầu ngày càng cao của khách hàng. Từ những sản phẩm đầu tiên đến nay, Manso đã trở thành điểm đến đáng tin cậy, mang lại trải nghiệm mua sắm tiện lợi và thú vị.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection