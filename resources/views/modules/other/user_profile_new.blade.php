@if( ( Auth::user()->roleId == Config::get('constants.RETAILER')) || ( Auth::user()->roleId == Config::get('constants.DISTRIBUTOR')) )
                    <li class="nav-item dropdown">
                            <!-- <a class="nav-link dropdown-toggle waves-effect waves-dark user" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> -->
                            <a class="nav-link dropdown-toggle waves-effect waves-dark " href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ Auth::dpPath() ? Auth::dpPath() : asset('template_assets\assets\images\profile-pic-thumbnail.jpg') }}" alt="user" class="rounded-circle_remove" width="42">
                                <span class="ml-2 font-medium">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span><span class="fas fa-angle-down ml-2"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <div class="d-flex no-block align-items-center p-3 mb-2 border-bottom">
                                    <div class="">
                                        <a href="javascript:void(0)" data-target="#updateProfilePicMdl" data-toggle="modal">
                                        <img src="{{ Auth::dpPath() ? Auth::dpPath() : asset('template_assets\assets\images\profile-pic-thumbnail.jpg') }}" alt="user" class="rounded" width="80">
                                        </a>
                                    </div>
                                    <div class="ml-2">
                                        <h4 class="mb-0">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                                        <p class=" mb-0 text-muted">({{ Auth::user()->username ? Auth::user()->username : '- -' }})</p>
                                        
                                        <a href="{{ Auth::user()->roleId==1 ? route('edit_user',Auth::user()->userId) : route('edit_retailer_user',Auth::user()->userId) }}" class="btn btn-sm btn-danger text-white mt-2 btn-rounded">View Profile</a>
                                    </div>
                                </div>
                                
                              
                                <a class="dropdown-item" href="{{ route('my_commission') }}"><img src="{{ asset('template_new/img/profile/commission.png') }}"> My Commission</a>
                                 <a class="dropdown-item" href="javascript:void(0)" id="security"><img src="{{ asset('template_new/img/profile/security_ic.png') }}"> Security</a>
                                  <a class="dropdown-item" href="{{ route('operator_helpline') }}"><img src="{{ asset('template_new/img/profile/supportblue_ic.png') }}">Operator Helpline</a>
                                   <a class="dropdown-item" id="{{isset(Auth::userKycId()['status']) ?  (Auth::userKycId()['status'] == 'APPROVED' ? '' : 'update-kyc-btn') : 'update-kyc-btn' }}" href="javascript:void(0)">
                                       <img src="{{ asset('template_new/img/profile/kyc_icon.png') }}"> {{isset(Auth::userKycId()['status']) ? 'KYC ['.Auth::userKycId()['status'].']' : 'Update KYC' }}
                                      @if( isset(Auth::userKycId()['status']) && (Auth::userKycId()['status'] == 'APPROVED') )
                                      <img src="{{ asset('template_new/img/verify_ic.png') }}" alt="verified" style="width: 25px;">
                                      @endif
                                    </a>
                                    <a class="dropdown-item" href="{{ route('view_tds', Auth::user()->userId) }}"><img src="{{ asset('template_assets/TDS Certificate_ic.png') }}"> TDS Certificate</a>
                                   <a class="dropdown-item" href="distributor_info" onclick="showParentInfo()"><img src="{{ asset('template_assets/FOS_ic.png') }}">FOS & Distributor Info</a>
                                    <!-- <a class="dropdown-item" id="user_certificate" href="{{-- route('user_certificate') --}}" target="_blank"><img src="{{-- asset('template_new/img/profile/document.svg') --}}"> Authorize Certificate</a> -->
                                    <a class="dropdown-item" id="user_certificate" href="javascript:void(0)"><img src="{{ asset('template_assets/192x192.png') }}">Authorized Certificate</a>
                                    <a class="dropdown-item" id="two_factor" href="{{ route('two_factor') }}"><img src="{{ asset('template_new/img/profile/security_ic.png') }}"> 2FA Authentication</a>
                               
                                <a class="dropdown-item" href="{{ route('logout') }}"><img src="{{ asset('template_new/img/sidebar/logout_ic.png') }}"> Logout</a>
                            </div>
                        </li>
@else
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="{{ Auth::dpPath() ? Auth::dpPath() : asset('template_assets\assets\images\profile-pic-thumbnail.jpg') }}" alt="profile-pic" class="rounded-circle" style="max-width: 36px;">
        <!-- <img src="../../assets/images/users/1.jpg" alt="user" class="rounded-circle" width="36"> -->
        <span class="ml-2 font-medium ">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span><span class="fas fa-angle-down ml-2"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY menu-board shadow-box">
        <div class="d-flex no-block align-items-center p-3 menu-head">
            <div class="">
                <a href="javascript:void(0)" data-target="#updateProfilePicMdl" data-toggle="modal" >
                    <img src="{{ Auth::dpPath() ? Auth::dpPath() : asset('template_assets\assets\images\profile-pic-thumbnail.jpg') }}" class="profile-image" alt="profile-pic" style="max-width: 36px;">
                </a>
            </div>
            <div class="ml-2">
                <h5 class="mb-0 pf-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} <a href="{{ route('edit_user',Auth::id()) }}" class="text-right  hide-this"><i class="mdi mdi-pen"></i></a></h5>
                <p class=" mb-0 pf-subtitle">({{ Auth::user()->username ? Auth::user()->username : '- -' }})</p>
            </div>
        </div>
        <div class="d-flex no-block align-items-center menu-head">
                <span class="pf-sub-info w-50 text-center">{{ Auth::user()->store_name ? Auth::user()->store_name : '--' }}</span>
                <span class="pf-sub-info  w-50 text-center">Available Balance</span>
        </div>
        <div class="d-flex no-block align-items-center pb-10 mb-2 border-bottom menu-head last-menu-head">
                <span class="pf-sub-info  w-50 text-center"><i class="mdi mdi-phone"></i> {{ Auth::user()->mobile }}</span>
                <span class="pf-sub-info  w-50 text-center"><i class="mdi mdi-currency-inr"></i> {{ Auth::user()->wallet_balance ? sprintf('%.2f', Auth::user()->wallet_balance) : 0.00}} </span>
        </div>
    
        <a href="{{ route('edit_user',Auth::user()->userId) }} "class="dropdown-item prf-anc" id="view-profile-btn"><i class="ti-user mr-1 ml-1"></i> View Profile</a>
        
        <a class="dropdown-item prf-anc" id="chg-pwd-btn" href="javascript:void(0)"><i class="ti-user mr-1 ml-1"></i> Change Password</a>
        <a class="dropdown-item prf-anc" id="chg-mpin-btn" href="javascript:void(0)"><i class="ti-user mr-1 ml-1"></i> Change Mpin</a>
        <a class="dropdown-item prf-anc" id="two_factor" href="{{ route('two_factor') }}"><img src="{{ asset('template_new/img/profile/security_ic.png') }}"> 2FA Authentication</a>
        <a class="dropdown-item prf-anc" id="{{isset(Auth::userKycId()['status']) ?  (Auth::userKycId()['status'] == 'APPROVED' ? '' : 'update-kyc-btn') : 'update-kyc-btn' }}"
         href="javascript:void(0)"><i class="ti-user mr-1 ml-1"></i> {{isset(Auth::userKycId()['status']) ? 'KYC ['.Auth::userKycId()['status'].']' : 'Update KYC' }}</a>
        
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-center" title="Logout" href="{{ route('logout') }}"><i class="fa fa-power-off mr-1 ml-1"></i></a>
    </div>
</li>
@endif