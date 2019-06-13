@extends('frontend.default.master')
@section('content')
<div id="single-product">
    <div class="container">

        <div class="no-margin col-xs-12 col-sm-6 col-md-5 gallery-holder">
            <div class="product-item-holder size-big single-product-gallery small-gallery">

                <div id="owl-single-product">

                    @forelse ($product->attachments ?: [] as $key => $attachment)

                    <div class="single-product-gallery-item" id="slide{{$key + 1}}">
                        <a class="{{ $key == 0 ? 'active' : '' }}" data-rel="prettyphoto"
                            href="{{ asset('uploads/'. get_thumbnail($attachment->path,'_450x337')) }}">
                            <img class="img-responsive" alt=""
                                src="{{ asset('themes/default/assets/images/blank.gif')}}"
                                data-echo="{{ asset('uploads/'. get_thumbnail($attachment->path,'_450x337')) }}" />
                        </a>
                    </div><!-- /.single-product-gallery-item -->
                    @empty
                    <a data-rel="prettyphoto" href="{{ asset(get_thumbnail('images/no_image.jpg','_450x337')) }}">
                        <img alt="" src="{{ asset('themes/default/assets/images/blank.gif')}}"
                            data-echo="{{ asset(get_thumbnail('images/no_image.jpg','_450x337')) }}" alt="No_img" />
                    </a>
                    @endforelse



                </div><!-- /.single-product-slider -->


                <div class="single-product-gallery-thumbs gallery-thumbs">

                    <div id="owl-single-product-thumbnails">
                        @forelse ($product->attachments ?: [] as $key => $attachment)
                    <a class="horizontal-thumb {{ $key == 0 ? 'active' : '' }}" data-target="#owl-single-product" data-slide="{{$key}}"
                    href="#slide{{$key+1}}">
                            <img width="67" alt="" src="{{ asset('themes/default/assets/images/blank.gif')}}"
                                data-echo="{{ asset('uploads/'. get_thumbnail($attachment->path,'_80x80')) }}" />
                        </a>
                        @empty
                        
                            <img alt="" src="{{ asset('themes/default/assets/images/blank.gif')}}"
                                data-echo="{{ asset(get_thumbnail('images/no_image.jpg','_80x80')) }}" alt="No_img" />
                        
                        @endforelse



                    </div><!-- /#owl-single-product-thumbnails -->

                    <div class="nav-holder left hidden-xs">
                        <a class="prev-btn slider-prev" data-target="#owl-single-product-thumbnails" href="#prev"></a>
                    </div><!-- /.nav-holder -->

                    <div class="nav-holder right hidden-xs">
                        <a class="next-btn slider-next" data-target="#owl-single-product-thumbnails" href="#next"></a>
                    </div><!-- /.nav-holder -->

                </div><!-- /.gallery-thumbs -->

            </div><!-- /.single-product-gallery -->
        </div><!-- /.gallery-holder -->
        <div class="no-margin col-xs-12 col-sm-7 body-holder">
            <div class="body">
                <div class="star-holder inline">
                    <div class="star" data-score="4"></div>
                </div>
                <div class="availability"><label>Availability:</label><span class="available"> in stock</span>
                </div>
                <div class="title"><a href="#">{{ $product->name }}</a></div>
                <div class="brand">{{ $product->code }}</div>

                <div class="social-row">
                    <span class="st_facebook_hcount"></span>
                    <span class="st_twitter_hcount"></span>
                    <span class="st_pinterest_hcount"></span>
                </div>

                <div class="buttons-holder">
                    <a class="btn-add-to-wishlist" href="#">Thêm vào danh sách muốn mua</a>
                    <a class="btn-add-to-compare" href="#">Thêm vào danh sách so sánh</a>
                </div>

                <div class="excerpt">
                    <p>{{ $product->content }}</p>
                </div>

                <div class="prices">
                    <div class="price-current">{{number_format($product->sale_price)}}</div>
                    <div class="price-prev">{{ number_format( $product->regular_price )}}</div>
                </div>

                <div class="qnt-holder">
                    <div class="le-quantity">
                        <form>
                            <a class="minus" href="#reduce"></a>
                            <input name="quantity" readonly="readonly" type="text" value="1" />
                            <a class="plus" href="#add"></a>
                        </form>
                    </div>
                    <a id="addto-cart" href="cart.html" class="le-button huge">Thêm vào giỏ hàng</a>
                </div><!-- /.qnt-holder -->
            </div><!-- /.body -->

        </div><!-- /.body-holder -->
    </div><!-- /.container -->
</div><!-- /.single-product -->

<!-- ========================================= SINGLE PRODUCT TAB ========================================= -->
<section id="single-product-tab">
    <div class="container">
        <div class="tab-holder">

            <ul class="nav nav-tabs simple">
            <li class="{{ $errors->any() ? '' : 'active'}}"><a href="#description" data-toggle="tab">Mô tả</a></li>
                <li><a href="#additional-info" data-toggle="tab">Thông tin thêm</a></li>
            <li class="{{ $errors->any() ? 'active' : ''}}"><a href="#reviews" data-toggle="tab">Đánh giá ({{ count($product->comments)}})</a></li>
            </ul><!-- /.nav-tabs -->

            <div class="tab-content">
                <div class="tab-pane {{ $errors->any() ? '' : 'active'}}" id="description">
                    <p>{{ $product->content }}</p>

                    <p>Sed consequat orci vel rutrum blandit. Nam non leo vel risus cursus porta quis non nulla.
                        Vestibulum vitae pellentesque nunc. In hac habitasse platea dictumst. Cras egestas,
                        turpis a malesuada mollis, magna tortor scelerisque urna, in pellentesque diam tellus
                        sit amet velit. Donec vel rhoncus nisi, eget placerat elit. Phasellus dignissim nisl vel
                        lectus vehicula, eget vehicula nisl egestas. Duis pretium sed risus dapibus egestas. Nam
                        lectus felis, sodales sit amet turpis se.</p>

                    <div class="meta-row">
                        <div class="inline">
                            <label>Code:</label>
                            <span>{{ $product->code }}</span>
                        </div><!-- /.inline -->

                        <span class="seperator">/</span>

                        <div class="inline">
                            <label>categories:</label>
                            <span><a href="#">{{$product->category->name}}</a>,</span>

                        </div><!-- /.inline -->

                        <span class="seperator">/</span>

                        <div class="inline">
                            <label>tag:</label>
                            @forelse ($product->tags as $tag)
                            <span><a href="#">{{ $tag->name }}</a>,</span>
                            @empty
                            {{"không có tag nào"}}
                            @endforelse


                        </div><!-- /.inline -->
                    </div><!-- /.meta-row -->
                </div><!-- /.tab-pane #description -->
                @php
                //Convert attribute json to array
                if(!empty($product->attributes)){
                $attributes = json_decode($product->attributes,true);
                }else {
                $attributes = null;
                }

                // dd($attributes); hàm dd chỉ kiêm tra được biến có tồn tại ít nhất là null hoặc rỗng
                @endphp
                <div class="tab-pane" id="additional-info">
                    <ul class="tabled-data">
                        {{--hàm forelse chỉ trả về kết quả empty khi biến $attributes có tồn tại nhưng là một mảng rỗng, biểu thức điều kiện ?: cũng chỉ thực thi được khi giá trị trước nó có tồn tại ít nhất là null hoặc rỗng --}}
                        @forelse ($attributes ?: [] as $attribute)
                        <li>
                            <label>{{ $attribute['name']}}</label>
                            <div class="value">{{ $attribute['value']}}</div>
                        </li>
                        @empty
                        <h1>Sản phẩm không có thuộc tính</h1>
                        @endforelse

                    </ul><!-- /.tabled-data -->


                </div><!-- /.tab-pane #additional-info -->


                <div class="tab-pane {{ $errors->any() ? 'active' : ''}}" id="reviews">
                    <div class="comments">
                        
                        @forelse ($product->comments as $comment)
                        <div class="comment-item">
                                <div class="row no-margin">
                                    <div class="col-lg-1 col-xs-12 col-sm-2 no-margin">
                                        <div class="avatar">
                                        <img alt="avatar" src="{{ asset('themes/default/assets/images/default-avatar.jpg') }}">
                                        </div><!-- /.avatar -->
                                    </div><!-- /.col -->
    
                                    <div class="col-xs-12 col-lg-11 col-sm-10 no-margin">
                                        <div class="comment-body">
                                            <div class="meta-info">
                                                <div class="author inline">
                                                <a href="#" class="bold">{{ $comment->name }}</a>
                                                </div>
                                                <div class="star-holder inline">
                                                <div class="star" data-score="{{ $comment->ratings }}"></div>
                                                </div>
                                                <div class="date inline pull-right">
                                                    {{ $comment->created_at }}
                                                </div>
                                            </div><!-- /.meta-info -->
                                            <p class="comment-text">
                                                    {{ $comment->content }}
                                            </p><!-- /.comment-text -->
                                        </div><!-- /.comment-body -->
    
                                    </div><!-- /.col -->
    
                                </div><!-- /.row -->
                            </div><!-- /.comment-item -->
                        @empty
                            <div class="alert alert-primary" role="alert">
                               Không có bình luận nào
                            </div>
                        @endforelse
                    </div><!-- /.comments -->

                    <div class="add-review row">
                        <div class="col-sm-8 col-xs-12">
                            <div class="new-review-form">
                                <h2>Thêm Đánh Giá</h2>
                                <div>
                                    @if (session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                </div>
                            <form action="{{ route('frontend.product.comment',['id'=> $product->id]) }}" id="contact-form" class="contact-form" method="post">
                                {{ csrf_field() }}
                                    <div class="row field-row">
                                        <div class="col-xs-12 col-sm-6 {{ $errors->has('name') ? 'has-error' : ''}}">
                                            <label>Tên*</label>
                                            <input type="text" class="le-input" name="name">
                                            <span class="help-block">{{ $errors->first('name') }}</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 {{ $errors->has('email') ? 'has-error' : ''}}">
                                            <label>Email*</label>
                                            <input type="email" class="le-input" name="email">
                                            <span class="help-block">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div><!-- /.field-row -->

                                    <div class="field-row star-row">
                                        <label>Đánh giá của bạn</label>
                                        <div class="star-holder {{ $errors->has('score') ? 'has-error' : ''}}">
                                            <div class="star big" data-score="0"></div>
                                            <span class="help-block">{{ $errors->first('score') }}</span>
                                        </div>
                                    </div><!-- /.field-row -->

                                    <div class="field-row {{ $errors->has('content') ? 'has-error' : ''}}">
                                        <label>Đánh giá của bạn</label>
                                        <textarea rows="8" class="le-input" name="content"></textarea>
                                        <span class="help-block">{{ $errors->first('content') }}</span>
                                    </div><!-- /.field-row -->

                                    <div class="buttons-holder">
                                        <button type="submit" class="le-button huge">Gửi</button>
                                    </div><!-- /.buttons-holder -->
                                </form><!-- /.contact-form -->
                            </div><!-- /.new-review-form -->
                        </div><!-- /.col -->
                    </div><!-- /.add-review -->

                </div><!-- /.tab-pane #reviews -->
            </div><!-- /.tab-content -->

        </div><!-- /.tab-holder -->
    </div><!-- /.container -->
</section><!-- /#single-product-tab -->
<!-- ========================================= SINGLE PRODUCT TAB : END ========================================= -->
<!-- ========================================= RECENTLY VIEWED ========================================= -->
<section id="recently-reviewd" class="wow fadeInUp">
    <div class="container">
        <div class="carousel-holder hover">

            <div class="title-nav">
                <h2 class="h1">Recently Viewed</h2>
                <div class="nav-holder">
                    <a href="#prev" data-target="#owl-recently-viewed"
                        class="slider-prev btn-prev fa fa-angle-left"></a>
                    <a href="#next" data-target="#owl-recently-viewed"
                        class="slider-next btn-next fa fa-angle-right"></a>
                </div>
            </div><!-- /.title-nav -->

            <div id="owl-recently-viewed" class="owl-carousel product-grid-holder">
                <div class="no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">
                        <div class="ribbon red"><span>sale</span></div>
                        <div class="image">
                            <img alt="" src="assets/images/blank.gif"
                                data-echo="{{asset('themes/default/assets/images/products/product-11.jpg') }}" />
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">LC-70UD1U 70" class aquos 4K ultra HD</a>
                            </div>
                            <div class="brand">Sharp</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to Cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div><!-- /.product-item -->
                </div><!-- /.product-item-holder -->

                <div class="no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">
                        <div class="ribbon blue"><span>new!</span></div>
                        <div class="image">
                            <img alt="" src="assets/images/blank.gif"
                                data-echo="{{asset('themes/default/assets/images/products/product-12.jpg') }}" />
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">cinemizer OLED 3D virtual reality TV Video</a>
                            </div>
                            <div class="brand">zeiss</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div><!-- /.product-item -->
                </div><!-- /.product-item-holder -->

                <div class=" no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">

                        <div class="image">
                            <img alt="" src="assets/images/blank.gif"
                                data-echo="{{asset('themes/default/assets/images/products/product-13.jpg') }}" />
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">s2340T23" full HD multi-Touch Monitor</a>
                            </div>
                            <div class="brand">dell</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div><!-- /.product-item -->
                </div><!-- /.product-item-holder -->

                <div class=" no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">
                        <div class="ribbon blue"><span>new!</span></div>
                        <div class="image">
                            <img alt="" src="assets/images/blank.gif"
                                data-echo="{{asset('themes/default/assets/images/products/product-14.jpg') }}" />
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">kardon BDS 7772/120 integrated 3D</a>
                            </div>
                            <div class="brand">harman</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div><!-- /.product-item -->
                </div><!-- /.product-item-holder -->

                <div class=" no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">
                        <div class="ribbon green"><span>bestseller</span></div>
                        <div class="image">
                            <img alt="" src="assets/images/blank.gif"
                                data-echo="{{asset('themes/default/assets/images/products/product-15.jpg') }}" />
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">netbook acer travel B113-E-10072</a>
                            </div>
                            <div class="brand">acer</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div><!-- /.product-item -->
                </div><!-- /.product-item-holder -->

                <div class=" no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">

                        <div class="image">
                            <img alt="" src="assets/images/blank.gif"
                                data-echo="{{asset('themes/default/assets/images/products/product-16.jpg') }}" />
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">iPod touch 5th generation,64GB, blue</a>
                            </div>
                            <div class="brand">apple</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div><!-- /.product-item -->
                </div><!-- /.product-item-holder -->

                <div class=" no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">

                        <div class="image">
                            <img alt="" src="assets/images/blank.gif"
                                data-echo="{{asset('themes/default/assets/images/products/product-13.jpg') }}" />
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">s2340T23" full HD multi-Touch Monitor</a>
                            </div>
                            <div class="brand">dell</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div><!-- /.product-item -->
                </div><!-- /.product-item-holder -->

                <div class=" no-margin carousel-item product-item-holder size-small hover">
                    <div class="product-item">
                        <div class="ribbon blue"><span>new!</span></div>
                        <div class="image">
                            <img alt="" src="assets/images/blank.gif"
                                data-echo="{{asset('themes/default/assets/images/products/product-14.jpg') }}" />
                        </div>
                        <div class="body">
                            <div class="title">
                                <a href="single-product.html">kardon BDS 7772/120 integrated 3D</a>
                            </div>
                            <div class="brand">harman</div>
                        </div>
                        <div class="prices">
                            <div class="price-current text-right">$1199.00</div>
                        </div>
                        <div class="hover-area">
                            <div class="add-cart-button">
                                <a href="single-product.html" class="le-button">Add to cart</a>
                            </div>
                            <div class="wish-compare">
                                <a class="btn-add-to-wishlist" href="#">Add to Wishlist</a>
                                <a class="btn-add-to-compare" href="#">Compare</a>
                            </div>
                        </div>
                    </div><!-- /.product-item -->
                </div><!-- /.product-item-holder -->
            </div><!-- /#recently-carousel -->

        </div><!-- /.carousel-holder -->
    </div><!-- /.container -->
</section><!-- /#recently-reviewd -->
<!-- ========================================= RECENTLY VIEWED : END ========================================= -->
@endsection