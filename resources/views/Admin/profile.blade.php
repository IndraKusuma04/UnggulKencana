@extends('Layouts.app')
@section('title', 'Profile')
@section('content')
    <div class="page-wrapper">
        <div class="content settings-content">
            <div class="page-header settings-pg-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Settings</h4>
                        <h6>Manage your settings on portal</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw"
                                class="feather-rotate-ccw"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                                data-feather="chevron-up" class="feather-chevron-up"></i></a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="settings-wrapper d-flex">
                        <div class="sidebars settings-sidebar theiaStickySidebar" id="sidebar2">
                            <div class="sidebar-inner slimscroll">
                                <div id="sidebar-menu5" class="sidebar-menu">
                                    <ul>
                                        <li class="submenu-open">
                                            <ul>
                                                <li class="submenu">
                                                    <a href="javascript:void(0);" class="active subdrop"><i
                                                            data-feather="settings"></i><span>General
                                                            Settings</span><span class="menu-arrow"></span></a>
                                                    <ul>
                                                        <li><a href="general-settings.html" class="active">Profile</a></li>
                                                        <li><a href="security-settings.html">Security</a></li>
                                                        <li><a href="notification.html">Notifications</a></li>
                                                        <li><a href="connected-apps.html">Connected Apps</a></li>
                                                    </ul>
                                                </li>
                                                <li class="submenu">
                                                    <a href="javascript:void(0);"><i
                                                            data-feather="airplay"></i><span>Website
                                                            Settings</span><span class="menu-arrow"></span></a>
                                                    <ul>
                                                        <li><a href="system-settings.html">System Settings</a></li>
                                                        <li><a href="company-settings.html">Company Settings </a>
                                                        </li>
                                                        <li><a href="localization-settings.html">Localization</a>
                                                        </li>
                                                        <li><a href="prefixes.html">Prefixes</a></li>
                                                        <li><a href="preference.html">Preference</a></li>
                                                        <li><a href="appearance.html">Appearance</a></li>
                                                        <li><a href="social-authentication.html">Social
                                                                Authentication</a></li>
                                                        <li><a href="language-settings.html">Language</a></li>
                                                    </ul>
                                                </li>
                                                <li class="submenu">
                                                    <a href="javascript:void(0);"><i data-feather="archive"></i><span>App
                                                            Settings</span><span class="menu-arrow"></span></a>
                                                    <ul>
                                                        <li><a href="invoice-settings.html">Invoice</a></li>
                                                        <li><a href="printer-settings.html">Printer </a></li>
                                                        <li><a href="pos-settings.html">POS</a></li>
                                                        <li><a href="custom-fields.html">Custom Fields</a></li>
                                                    </ul>
                                                </li>
                                                <li class="submenu">
                                                    <a href="javascript:void(0);"><i data-feather="server"></i><span>System
                                                            Settings</span><span class="menu-arrow"></span></a>
                                                    <ul>
                                                        <li><a href="email-settings.html">Email</a></li>
                                                        <li><a href="sms-gateway.html">SMS Gateways</a></li>
                                                        <li><a href="otp-settings.html">OTP</a></li>
                                                        <li><a href="gdpr-settings.html">GDPR Cookies</a></li>
                                                    </ul>
                                                </li>
                                                <li class="submenu">
                                                    <a href="javascript:void(0);"><i
                                                            data-feather="credit-card"></i><span>Financial
                                                            Settings</span><span class="menu-arrow"></span></a>
                                                    <ul>
                                                        <li><a href="payment-gateway-settings.html">Payment
                                                                Gateway</a></li>
                                                        <li><a href="bank-settings-grid.html">Bank Accounts </a>
                                                        </li>
                                                        <li><a href="tax-rates.html">Tax Rates</a></li>
                                                        <li><a href="currency-settings.html">Currencies</a></li>
                                                    </ul>
                                                </li>
                                                <li class="submenu">
                                                    <a href="javascript:void(0);"><i data-feather="layout"></i><span>Other
                                                            Settings</span><span class="menu-arrow"></span></a>
                                                    <ul>
                                                        <li><a href="storage-settings.html">Storage</a></li>
                                                        <li><a href="ban-ip-address.html">Ban IP Address </a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="settings-page-wrap">
                            <form action="general-settings.html">
                                <div class="setting-title">
                                    <h4>Profile Settings</h4>
                                </div>
                                <div class="card-title-head">
                                    <h6><span><i data-feather="user" class="feather-chevron-up"></i></span>Employee
                                        Information</h6>
                                </div>
                                <div class="profile-pic-upload">
                                    <div class="profile-pic">
                                        <span><i data-feather="plus-circle" class="plus-down-add"></i> Profile
                                            Photo</span>
                                    </div>
                                    <div class="new-employee-field">
                                        <div class="mb-0">
                                            <div class="image-upload mb-0">
                                                <input type="file">
                                                <div class="image-uploads">
                                                    <h4>Change Image</h4>
                                                </div>
                                            </div>
                                            <span>For better preview recommended size is 450px x 450px. Max size
                                                5MB.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">User Name</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-title-head">
                                    <h6><span><i data-feather="map-pin" class="feather-chevron-up"></i></span>Our
                                        Address</h6>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Country</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">State / Province</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">City</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-4 col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Postal Code</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end settings-bottom-btn">
                                    <button type="button" class="btn btn-cancel me-2">Cancel</button>
                                    <button type="submit" class="btn btn-submit">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
