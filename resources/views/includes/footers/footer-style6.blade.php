<section class="pro-content header-section">
<!-- //footer style Six -->    
 <footer id="footerSix"  class="footer-area footer-six  d-lg-block d-xl-block">
  {{-- <div class="container-fluid p-0">
      <div class="search-content">
          <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 align-self-center">
                    <div class="newsletter">
                        <h5>{{ trans('lables.footer-latest-subscribe') }}</h5>
                        <div class="block">
                       
              <form class="form-inline" >
                <div class="search-field-module">                           
                  
                  <div class="search-field-wrap">
                      <input  type="search" placeholder="Search Products..." data-toggle="tooltip" data-placement="bottom" title="search item">
                      <button class="btn btn-secondary swipe-to-top" data-toggle="tooltip" 
                      data-placement="bottom" title="Search Products">
                      {{ trans('lables.footer-subscribe') }}</button>
                  </div>
                </div>
              </form>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
  </div>   --}}
  <div class="container">
    <div class="row">
      <div class="col-12 col-lg-2">
          <h5>{{ trans('lables.footer-contact-us') }}</h5>
          <div class="row">
            <div class="col-12 col-lg-8">
              <hr>
            </div>
          </div>
          <ul class="contact-list  pl-0 mb-0">
            <li> <i class="fas fa-map-marker"></i><span>{{isset(getSetting()['address']) ? getSetting()['address'] : '#' }}</span> </li>
            <li> <i class="fas fa-phone"></i><span>({{isset(getSetting()['phone_number']) ? getSetting()['phone_number'] : '#' }})</span> </li>
            <li> <i class="fas fa-envelope"></i><span> <a href="mailto:{{isset(getSetting()['email']) ? getSetting()['email'] : '#' }}">{{isset(getSetting()['email']) ? getSetting()['email'] : '#' }}</a> </span> </li>
      
          </ul>
      </div>
      <div class="col-12 col-md-6 col-lg-2">
        <div class="footer-block">
              <h5>{{ trans('lables.footer-our-services') }}</h5>
              <div class="row">
                  <div class="col-12 col-lg-8">
                    <hr>
                  </div>
                </div>
                <ul class="links-list pl-0 mb-0">
                  <li> <a href="{{ url('/') }}" title="{{ trans('lables.footer-home') }}"><i class="fa fa-angle-right"></i>{{ trans('lables.footer-home') }}</a> </li>
                  <li> <a href="{{ url('/shop') }}" title="{{ trans('lables.footer-home') }}"><i class="fa fa-angle-right"></i>{{ trans('lables.footer-shop') }}</a> </li>
                  <li> <a href="{{ url('/orders') }}" title="{{ trans('lables.footer-orders') }}"><i class="fa fa-angle-right"></i>{{ trans('lables.footer-orders') }}</a> </li>
                  <li> <a href="{{ url('/cart') }}" title="{{ trans('lables.footer-shopping-cart') }}"><i class="fa fa-angle-right"></i>{{ trans('lables.footer-shopping-cart') }}</a> </li>           
                    <li> <a href="{{ url('/wishlist') }}" title="{{ trans('lables.footer-wishlist') }}"><i class="fa fa-angle-right"></i>{{ trans('lables.footer-wishlist') }}</a> </li>           
                </ul>
            
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-2">
        <h5>{{ trans('lables.footer-information') }}</h5>

          <div class="row">
              <div class="col-12 col-lg-8">
                <hr>
              </div>
            </div>
            <ul class="links-list pl-0 mb-0">
              @foreach($data['pages'] as $page)
              @if(isset($page->page_detail))
              <li> <a href="{{ url("/page")."/".$page->slug }}"><i class="fa fa-angle-right" ></i>{{ $page->page_detail[0]->title }}</a> </li>
              @endif
              @endforeach 
            </ul>
      </div>
      
      <div class="col-12 col-lg-3">
        
        <div class="socials">
          <h5>{{ trans('lables.footer-follow-us') }}</h5>
            <div class="row">
                <div class="col-12 col-lg-8">
                  <hr>
                </div>
              </div>
              <ul class="list">
                @if (isset(getSetting()['facebook_url']))
                <li><a href="{{isset(getSetting()['facebook_url']) ? getSetting()['facebook_url'] : '#' }}" class="fab fa-facebook-f" data-toggle="tooltip" data-placement="bottom" title="{{ trans('lables.footer-facebook') }}"></a></li>
                @endif
                @if (isset(getSetting()['twitter_url']))
                <li><a href="{{isset(getSetting()['twitter_url']) ? getSetting()['twitter_url'] : '#' }}" class="fab fa-twitter" data-toggle="tooltip" data-placement="bottom" title="{{ trans('lables.footer-twitter') }}"></a></li>
                @endif
                @if (isset(getSetting()['google_url']))
                <li><a href="{{isset(getSetting()['google_url']) ? getSetting()['google_url'] : '#' }}" class="fab fa-google" data-toggle="tooltip" data-placement="bottom" title="{{ trans('lables.footer-google') }}"></a></li>
                @endif
                @if (isset(getSetting()['linkedin_url']))
                <li><a href="{{isset(getSetting()['google_url']) ? getSetting()['linkedin_url'] : '#' }}" class="fab fa-linkedin-in" data-toggle="tooltip" data-placement="bottom" title="{{ trans('lables.footer-linkedin') }}"></a></li>
                @endif
                @if (isset(getSetting()['instagram_url']))
                <li><a href="{{isset(getSetting()['instagram_url']) ? getSetting()['instagram_url'] : '#' }}" class="fab fa-instagram" data-toggle="tooltip" data-placement="bottom" title="{{ trans('lables.footer-instagram') }}"></a></li>
                @endif
              </ul>
              <div class="footer-image mb-3">
                
                <h5>{{ trans('lables.footer-payments') }}</h5>
                <div class="row">
                  <div class="col-12 col-lg-8">
                    <hr>
                  </div>
                </div>
                  <img class="img-fluid" src="{{ asset('assets/front/images/miscellaneous/payments.png') }}" alt="image">
              </div>
            
        </div>
      </div>
      <div class="col-12 col-lg-3">
        <div class="single-footer">
            <h5>
              Newsletter
            </h5>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="instagram-content">
              <form action="" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input class="form-control" type="email" name="email" required id="news_email" placeholder="Email" style="margin-bottom: 4px">
                <input class="form-control" type="text" name="name" id="news_name" placeholder="Name" style="margin-bottom: 4px">
                <div>
                  <button class="btn btn-secondary swipe-to-top" id="newsletter" type="submit">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
    </div>
  </div>
  <div class="container-fluid p-0">
      <div class="copyright-content">
          <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                  <div class="footer-info">
                    &copy;&nbsp;{{ trans('lables.footer-company') }}
                  </div>
                </div>
            </div>
          </div>
        </div>
  </div>
</footer>    

</section>