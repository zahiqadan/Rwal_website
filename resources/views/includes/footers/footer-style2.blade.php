<section class="pro-content header-section">
  <!-- //footer style Two -->
  <footer id="footerTwo" class="footer-area footer-two  d-lg-block d-xl-block">
    <div class="container">
      <div class="row">
        <div class="col-12 col-lg-2">
          <div class="single-footer">
            <h5>{{ trans('lables.footer-about-store') }}</h5>
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
        </div>
        <div class="col-12 col-md-6 col-lg-2">
          <div class="footer-block">
              <div class="single-footer single-footer-left">
                <h5>{{ trans('lables.footer-our-services') }}</h5>
                <div class="row">
                    <div class="col-12 col-lg-8">
                      <hr>
                    </div>
                  </div>
                  <ul class="links-list pl-0">
                    <li> <a href="{{ url('/') }}" data-toggle="tooltip" data-placement="left" title="{{ trans('lables.footer-home') }}">{{ trans('lables.footer-home') }}</a> </li>
                    <li> <a href="{{ url('/shop') }}" data-toggle="tooltip" data-placement="left" title="{{ trans('lables.footer-shop') }}">{{ trans('lables.footer-shop') }}</a> </li>
                    <li> <a href="{{ url('/cart') }}" data-toggle="tooltip" data-placement="left" title="{{ trans('lables.footer-shopping-cart') }}">{{ trans('lables.footer-shopping-cart') }}</a> </li>           
                    <li> <a href="{{ url('/wishlist') }}" data-toggle="tooltip" data-placement="left" title="{{  trans("lables.header-wishlist") }}">{{  trans("lables.header-wishlist") }}</a> </li>
                    <li> <a href="{{ url('/compare') }}" data-toggle="tooltip" data-placement="left" title="{{  trans("lables.header-compare") }}">{{  trans("lables.header-compare") }}</a> </li>          
                  </ul>
              </div>
              
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-2">
          <div class="single-footer single-footer-right">
            <h5>{{ trans('lables.footer-information') }}</h5>
            <div class="row">
                <div class="col-12 col-lg-8">
                  <hr>
                </div>
              </div>
            <ul class="links-list pl-0 mb-0">

              @foreach($data['pages'] as $page)
              @if(isset($page->page_detail))
              <li> <a href="{{ url("/page")."/".$page->slug }}" data-toggle="tooltip" data-placement="left" title="{{ $page->page_detail[0]->title }}">{{ $page->page_detail[0]->title }}</a> </li>
              @endif
              @endforeach
            
            </ul>
          </div>
        </div>
        
        <div class="col-12 col-lg-3">
          <div class="single-footer">
             
              
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
                  <div class="col-12 col-md-6">
                    <div class="footer-image">
                        <img class="img-fluid" src="{{ asset('assets/front/images/miscellaneous/payments.png') }}">
                    </div>
                      
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="footer-info">
                      ©&nbsp;{{ trans('lables.footer-company') }}
                    </div>
                      
                  </div>
              </div>
            </div>
          </div>
    </div>
</footer>
  </section>