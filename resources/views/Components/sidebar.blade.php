<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li><a href="/admin/dashboard"><i data-feather="grid"></i><span>Dashboard</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Master</h6>
                    <ul>
                        <li><a href="/admin/jabatan"><i data-feather="circle"></i><span>Jabatan</span></a>
                        </li>
                        <li><a href="/admin/role"><i data-feather="circle"></i><span>Role</span></a>
                        </li>
                        <li><a href="/admin/kondisi"><i data-feather="circle"></i><span>Kondisi</span></a>
                        </li>
                        <li><a href="/admin/diskon"><i data-feather="circle"></i><span>Diskon</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">User Management</h6>
                    <ul>
                        <li><a href="/admin/pegawai"><i data-feather="user-check"></i><span>Pegawai</span></a></li>
                        <li><a href="/admin/users"><i data-feather="shield"></i><span>Users</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Produk</h6>
                    <ul>
                        <li><a href="/admin/jenisproduk"><i data-feather="circle"></i><span>Jenis Produk</span></a>
                        </li>
                        <li><a href="/admin/role"><i data-feather="circle"></i><span>Role</span></a>
                        </li>
                        <li><a href="/admin/role"><i data-feather="circle"></i><span>Kondisi</span></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>


<div class="sidebar collapsed-sidebar" id="collapsed-sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu-2" class="sidebar-menu sidebar-menu-three">
            <aside id="aside" class="ui-aside">
                <ul class="tab nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="tablinks nav-link" href="#home" id="home-tab" data-bs-toggle="tab"
                            data-bs-target="#home" role="tab" aria-selected="true">
                            <img src="{{ asset('assets') }}/img/icons/menu-icon.svg" alt>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="tablinks nav-link active" href="#messages" id="messages-tab" data-bs-toggle="tab"
                            data-bs-target="#product" role="tab" aria-selected="false">
                            <img src="{{ asset('assets') }}/img/icons/product.svg" alt>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="tablinks nav-link" href="#profile" id="profile-tab" data-bs-toggle="tab"
                            data-bs-target="#sales" role="tab" aria-selected="false">
                            <img src="{{ asset('assets') }}/img/icons/sales1.svg" alt>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="tablinks nav-link" href="#report" id="report-tab" data-bs-toggle="tab"
                            data-bs-target="#purchase" role="tab" aria-selected="true">
                            <img src="{{ asset('assets') }}/img/icons/purchase1.svg" alt>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="tablinks nav-link" href="#set" id="set-tab" data-bs-toggle="tab"
                            data-bs-target="#user" role="tab" aria-selected="true">
                            <img src="{{ asset('assets') }}/img/icons/users1.svg" alt>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="tablinks nav-link" href="#set2" id="set-tab2" data-bs-toggle="tab"
                            data-bs-target="#employee" role="tab" aria-selected="true">
                            <img src="{{ asset('assets') }}/img/icons/calendars.svg" alt>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="tablinks nav-link" href="#set3" id="set-tab3" data-bs-toggle="tab"
                            data-bs-target="#report" role="tab" aria-selected="true">
                            <img src="{{ asset('assets') }}/img/icons/printer.svg" alt>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="tablinks nav-link" href="#set4" id="set-tab4" data-bs-toggle="tab"
                            data-bs-target="#document" role="tab" aria-selected="true">
                            <i data-feather="user"></i>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="tablinks nav-link" href="#set5" id="set-tab6" data-bs-toggle="tab"
                            data-bs-target="#permission" role="tab" aria-selected="true">
                            <i data-feather="file-text"></i>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="tablinks nav-link" href="#set6" id="set-tab5" data-bs-toggle="tab"
                            data-bs-target="#settings" role="tab" aria-selected="true">
                            <i data-feather="settings"></i>
                        </a>
                    </li>

                </ul>
            </aside>
            <div class="tab-content tab-content-four pt-2">
                <ul class="tab-pane" id="home" aria-labelledby="home-tab">
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Dashboard</span> <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="index.html">Admin Dashboard</a></li>
                            <li><a href="sales-dashboard.html">Sales Dashboard</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Application</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="chat.html">Chat</a></li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);"><span>Call</span><span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="video-call.html">Video Call</a></li>
                                    <li><a href="audio-call.html">Audio Call</a></li>
                                    <li><a href="call-history.html">Call History</a></li>
                                </ul>
                            </li>
                            <li><a href="calendar.html">Calendar</a></li>
                            <li><a href="email.html">Email</a></li>
                            <li><a href="todo.html">To Do</a></li>
                            <li><a href="notes.html">Notes</a></li>
                            <li><a href="file-manager.html">File Manager</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="tab-pane active" id="product" aria-labelledby="messages-tab">
                    <li><a href="product-list.html" class="active"><span>Products</span></a></li>
                    <li><a href="add-product.html"><span>Create Product</span></a></li>
                    <li><a href="expired-products.html"><span>Expired Products</span></a></li>
                    <li><a href="low-stocks.html"><span>Low Stocks</span></a></li>
                    <li><a href="category-list.html"><span>Category</span></a></li>
                    <li><a href="sub-categories.html"><span>Sub Category</span></a></li>
                    <li><a href="brand-list.html"><span>Brands</span></a></li>
                    <li><a href="units.html"><span>Units</span></a></li>
                    <li><a href="varriant-attributes.html"><span>Variant Attributes</span></a></li>
                    <li><a href="warranty.html"><span>Warranties</span></a></li>
                    <li><a href="barcode.html"><span>Print Barcode</span></a></li>
                    <li><a href="qrcode.html"><span>Print QR Code</span></a></li>
                </ul>
                <ul class="tab-pane" id="sales" aria-labelledby="profile-tab">
                    <li><a href="sales-list.html"><span>Sales</span></a></li>
                    <li><a href="invoice-report.html"><span>Invoices</span></a></li>
                    <li><a href="sales-returns.html"><span>Sales Return</span></a></li>
                    <li><a href="quotation-list.html"><span>Quotation</span></a></li>
                    <li><a href="pos.html"><span>POS</span></a></li>
                    <li><a href="coupons.html"><span>Coupons</span></a></li>
                </ul>
                <ul class="tab-pane" id="purchase" aria-labelledby="report-tab">
                    <li><a href="purchase-list.html"><span>Purchases</span></a></li>
                    <li><a href="purchase-order-report.html"><span>Purchase Order</span></a></li>
                    <li><a href="purchase-returns.html"><span>Purchase Return</span></a></li>
                    <li><a href="manage-stocks.html"><span>Manage Stock</span></a></li>
                    <li><a href="stock-adjustment.html"><span>Stock Adjustment</span></a></li>
                    <li><a href="stock-transfer.html"><span>Stock Transfer</span></a></li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Expenses</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="expense-list.html">Expenses</a></li>
                            <li><a href="expense-category.html">Expense Category</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="tab-pane" id="user" aria-labelledby="set-tab">
                    <li><a href="customers.html"><span>Customers</span></a></li>
                    <li><a href="suppliers.html"><span>Suppliers</span></a></li>
                    <li><a href="store-list.html"><span>Stores</span></a></li>
                    <li><a href="warehouse.html"><span>Warehouses</span></a></li>
                </ul>
                <ul class="tab-pane" id="employee" aria-labelledby="set-tab2">
                    <li><a href="employees-grid.html"><span>Employees</span></a></li>
                    <li><a href="department-grid.html"><span>Departments</span></a></li>
                    <li><a href="designation.html"><span>Designation</span></a></li>
                    <li><a href="shift.html"><span>Shifts</span></a></li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Attendence</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="attendance-employee.html">Employee Attendence</a></li>
                            <li><a href="attendance-admin.html">Admin Attendence</a></li>
                        </ul>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Leaves</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="leaves-admin.html">Admin Leaves</a></li>
                            <li><a href="leaves-employee.html">Employee Leaves</a></li>
                            <li><a href="leave-types.html">Leave Types</a></li>
                        </ul>
                    </li>
                    <li><a href="holidays.html"><span>Holidays</span></a></li>
                    <li class="submenu">
                        <a href="payroll-list.html"><span>Payroll</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="payroll-list.html">Employee Salary</a></li>
                            <li><a href="payslip.html">Payslip</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="tab-pane" id="report" aria-labelledby="set-tab3">
                    <li><a href="sales-report.html"><span>Sales Report</span></a></li>
                    <li><a href="purchase-report.html"><span>Purchase report</span></a></li>
                    <li><a href="inventory-report.html"><span>Inventory Report</span></a></li>
                    <li><a href="invoice-report.html"><span>Invoice Report</span></a></li>
                    <li><a href="supplier-report.html"><span>Supplier Report</span></a></li>
                    <li><a href="customer-report.html"><span>Customer Report</span></a></li>
                    <li><a href="expense-report.html"><span>Expense Report</span></a></li>
                    <li><a href="income-report.html"><span>Income Report</span></a></li>
                    <li><a href="tax-reports.html"><span>Tax Report</span></a></li>
                    <li><a href="profit-and-loss.html"><span>Profit & Loss</span></a></li>
                </ul>
                <ul class="tab-pane" id="permission" aria-labelledby="set-tab4">
                    <li><a href="users.html"><span>Users</span></a></li>
                    <li><a href="roles-permissions.html"><span>Roles & Permissions</span></a></li>
                    <li><a href="delete-account.html"><span>Delete Account Request</span></a></li>
                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <span>Base UI</span><span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="ui-alerts.html">Alerts</a></li>
                            <li><a href="ui-accordion.html">Accordion</a></li>
                            <li><a href="ui-avatar.html">Avatar</a></li>
                            <li><a href="ui-badges.html">Badges</a></li>
                            <li><a href="ui-borders.html">Border</a></li>
                            <li><a href="ui-buttons.html">Buttons</a></li>
                            <li><a href="ui-buttons-group.html">Button Group</a></li>
                            <li><a href="ui-breadcrumb.html">Breadcrumb</a></li>
                            <li><a href="ui-cards.html">Card</a></li>
                            <li><a href="ui-carousel.html">Carousel</a></li>
                            <li><a href="ui-colors.html">Colors</a></li>
                            <li><a href="ui-dropdowns.html">Dropdowns</a></li>
                            <li><a href="ui-grid.html">Grid</a></li>
                            <li><a href="ui-images.html">Images</a></li>
                            <li><a href="ui-lightbox.html">Lightbox</a></li>
                            <li><a href="ui-media.html">Media</a></li>
                            <li><a href="ui-modals.html">Modals</a></li>
                            <li><a href="ui-offcanvas.html">Offcanvas</a></li>
                            <li><a href="ui-pagination.html">Pagination</a></li>
                            <li><a href="ui-popovers.html">Popovers</a></li>
                            <li><a href="ui-progress.html">Progress</a></li>
                            <li><a href="ui-placeholders.html">Placeholders</a></li>
                            <li><a href="ui-rangeslider.html">Range Slider</a></li>
                            <li><a href="ui-spinner.html">Spinner</a></li>
                            <li><a href="ui-sweetalerts.html">Sweet Alerts</a></li>
                            <li><a href="ui-nav-tabs.html">Tabs</a></li>
                            <li><a href="ui-toasts.html">Toasts</a></li>
                            <li><a href="ui-tooltips.html">Tooltips</a></li>
                            <li><a href="ui-typography.html">Typography</a></li>
                            <li><a href="ui-video.html">Video</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <span>Advanced UI</span><span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="ui-ribbon.html">Ribbon</a></li>
                            <li><a href="ui-clipboard.html">Clipboard</a></li>
                            <li><a href="ui-drag-drop.html">Drag & Drop</a></li>
                            <li><a href="ui-rangeslider.html">Range Slider</a></li>
                            <li><a href="ui-rating.html">Rating</a></li>
                            <li><a href="ui-text-editor.html">Text Editor</a></li>
                            <li><a href="ui-counter.html">Counter</a></li>
                            <li><a href="ui-scrollbar.html">Scrollbar</a></li>
                            <li><a href="ui-stickynote.html">Sticky Note</a></li>
                            <li><a href="ui-timeline.html">Timeline</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Charts</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="chart-apex.html">Apex Charts</a></li>
                            <li><a href="chart-c3.html">Chart C3</a></li>
                            <li><a href="chart-js.html">Chart Js</a></li>
                            <li><a href="chart-morris.html">Morris Charts</a></li>
                            <li><a href="chart-flot.html">Flot Charts</a></li>
                            <li><a href="chart-peity.html">Peity Charts</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Icons</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
                            <li><a href="icon-feather.html">Feather Icons</a></li>
                            <li><a href="icon-ionic.html">Ionic Icons</a></li>
                            <li><a href="icon-material.html">Material Icons</a></li>
                            <li><a href="icon-pe7.html">Pe7 Icons</a></li>
                            <li><a href="icon-simpleline.html">Simpleline Icons</a></li>
                            <li><a href="icon-themify.html">Themify Icons</a></li>
                            <li><a href="icon-weather.html">Weather Icons</a></li>
                            <li><a href="icon-typicon.html">Typicon Icons</a></li>
                            <li><a href="icon-flag.html">Flag Icons</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <span>Forms</span><span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li class="submenu submenu-two">
                                <a href="javascript:void(0);">Form Elements<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="form-basic-inputs.html">Basic Inputs</a></li>
                                    <li><a href="form-checkbox-radios.html">Checkbox & Radios</a></li>
                                    <li><a href="form-input-groups.html">Input Groups</a></li>
                                    <li><a href="form-grid-gutters.html">Grid & Gutters</a></li>
                                    <li><a href="form-select.html">Form Select</a></li>
                                    <li><a href="form-mask.html">Input Masks</a></li>
                                    <li><a href="form-fileupload.html">File Uploads</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two">
                                <a href="javascript:void(0);">Layouts<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="form-horizontal.html">Horizontal Form</a></li>
                                    <li><a href="form-vertical.html">Vertical Form</a></li>
                                    <li><a href="form-floating-labels.html">Floating Labels</a></li>
                                </ul>
                            </li>
                            <li><a href="form-validation.html">Form Validation</a></li>
                            <li><a href="form-select2.html">Select2</a></li>
                            <li><a href="form-wizard.html">Form Wizard</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Tables</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="tables-basic.html">Basic Tables </a></li>
                            <li><a href="data-tables.html">Data Table </a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="tab-pane" id="document" aria-labelledby="set-tab5">
                    <li><a href="profile.html"><span>Profile</span></a></li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Authentication</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Login<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="signin.html">Cover</a></li>
                                    <li><a href="signin-2.html">Illustration</a></li>
                                    <li><a href="signin-3.html">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Register<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="register.html">Cover</a></li>
                                    <li><a href="register-2.html">Illustration</a></li>
                                    <li><a href="register-3.html">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Forgot
                                    Password<span class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="forgot-password.html">Cover</a></li>
                                    <li><a href="forgot-password-2.html">Illustration</a></li>
                                    <li><a href="forgot-password-3.html">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Reset
                                    Password<span class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="reset-password.html">Cover</a></li>
                                    <li><a href="reset-password-2.html">Illustration</a></li>
                                    <li><a href="reset-password-3.html">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Email
                                    Verification<span class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="email-verification.html">Cover</a></li>
                                    <li><a href="email-verification-2.html">Illustration</a></li>
                                    <li><a href="email-verification-3.html">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">2 Step
                                    Verification<span class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="two-step-verification.html">Cover</a></li>
                                    <li><a href="two-step-verification-2.html">Illustration</a></li>
                                    <li><a href="two-step-verification-3.html">Basic</a></li>
                                </ul>
                            </li>
                            <li><a href="lock-screen.html">Lock Screen</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Error Pages</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="error-404.html">404 Error </a></li>
                            <li><a href="error-500.html">500 Error </a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Places</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="countries.html">Countries</a></li>
                            <li><a href="states.html">States</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank-page.html"><span>Blank Page</span> </a>
                    </li>
                    <li>
                        <a href="coming-soon.html"><span>Coming Soon</span> </a>
                    </li>
                    <li>
                        <a href="under-maintenance.html"><span>Under Maintenance</span> </a>
                    </li>
                </ul>
                <ul class="tab-pane" id="settings" aria-labelledby="set-tab6">
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>General Settings</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="general-settings.html">Profile</a></li>
                            <li><a href="security-settings.html">Security</a></li>
                            <li><a href="notification.html">Notifications</a></li>
                            <li><a href="connected-apps.html">Connected Apps</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Website Settings</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="system-settings.html">System Settings</a></li>
                            <li><a href="company-settings.html">Company Settings </a></li>
                            <li><a href="localization-settings.html">Localization</a></li>
                            <li><a href="prefixes.html">Prefixes</a></li>
                            <li><a href="preference.html">Preference</a></li>
                            <li><a href="appearance.html">Appearance</a></li>
                            <li><a href="social-authentication.html">Social Authentication</a></li>
                            <li><a href="language-settings.html">Language</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>App Settings</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="invoice-settings.html">Invoice</a></li>
                            <li><a href="printer-settings.html">Printer</a></li>
                            <li><a href="pos-settings.html">POS</a></li>
                            <li><a href="custom-fields.html">Custom Fields</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>System Settings</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="email-settings.html">Email</a></li>
                            <li><a href="sms-gateway.html">SMS Gateways</a></li>
                            <li><a href="otp-settings.html">OTP</a></li>
                            <li><a href="gdpr-settings.html">GDPR Cookies</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Financial Settings</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="payment-gateway-settings.html">Payment Gateway</a></li>
                            <li><a href="bank-settings-grid.html">Bank Accounts</a></li>
                            <li><a href="tax-rates.html">Tax Rates</a></li>
                            <li><a href="currency-settings.html">Currencies</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Other Settings</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="storage-settings.html">Storage</a></li>
                            <li><a href="ban-ip-address.html">Ban IP Address</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);"><span>Documentation</span></a></li>
                    <li><a href="javascript:void(0);"><span>Changelog v2.0.7</span></a></li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Multi Level</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="javascript:void(0);">Level 1.1</a></li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Level 1.2<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="javascript:void(0);">Level 2.1</a></li>
                                    <li class="submenu submenu-two submenu-three"><a href="javascript:void(0);">Level
                                            2.2<span class="menu-arrow inside-submenu inside-submenu-two"></span></a>
                                        <ul>
                                            <li><a href="javascript:void(0);">Level 3.1</a></li>
                                            <li><a href="javascript:void(0);">Level 3.2</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="sidebar horizontal-sidebar">
    <div id="sidebar-menu-3" class="sidebar-menu">
        <ul class="nav">
            <li class="submenu">
                <a href="index.html"><i data-feather="grid"></i><span> Main Menu</span> <span
                        class="menu-arrow"></span></a>
                <ul>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Dashboard</span> <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="index.html">Admin Dashboard</a></li>
                            <li><a href="sales-dashboard.html">Sales Dashboard</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Application</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="chat.html">Chat</a></li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);"><span>Call</span><span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="video-call.html">Video Call</a></li>
                                    <li><a href="audio-call.html">Audio Call</a></li>
                                    <li><a href="call-history.html">Call History</a></li>
                                </ul>
                            </li>
                            <li><a href="calendar.html">Calendar</a></li>
                            <li><a href="email.html">Email</a></li>
                            <li><a href="todo.html">To Do</a></li>
                            <li><a href="notes.html">Notes</a></li>
                            <li><a href="file-manager.html">File Manager</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="submenu">
                <a href="javascript:void(0);" class="active subdrop"><img
                        src="{{ asset('assets') }}/img/icons/product.svg" alt="img"><span> Inventory
                    </span> <span class="menu-arrow"></span></a>
                <ul>
                    <li><a href="product-list.html" class="active"><span>Products</span></a></li>
                    <li><a href="add-product.html"><span>Create Product</span></a></li>
                    <li><a href="expired-products.html"><span>Expired Products</span></a></li>
                    <li><a href="low-stocks.html"><span>Low Stocks</span></a></li>
                    <li><a href="category-list.html"><span>Category</span></a></li>
                    <li><a href="sub-categories.html"><span>Sub Category</span></a></li>
                    <li><a href="brand-list.html"><span>Brands</span></a></li>
                    <li><a href="units.html"><span>Units</span></a></li>
                    <li><a href="varriant-attributes.html"><span>Variant Attributes</span></a></li>
                    <li><a href="warranty.html"><span>Warranties</span></a></li>
                    <li><a href="barcode.html"><span>Print Barcode</span></a></li>
                    <li><a href="qrcode.html"><span>Print QR Code</span></a></li>
                </ul>
            </li>
            <li class="submenu">
                <a href="javascript:void(0);"><img src="{{ asset('assets') }}/img/icons/purchase1.svg"
                        alt="img"><span>Sales
                        &amp; Purchase</span> <span class="menu-arrow"></span></a>
                <ul>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Sales</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="sales-list.html"><span>Sales</span></a></li>
                            <li><a href="invoice-report.html"><span>Invoices</span></a></li>
                            <li><a href="sales-returns.html"><span>Sales Return</span></a></li>
                            <li><a href="quotation-list.html"><span>Quotation</span></a></li>
                            <li><a href="pos.html"><span>POS</span></a></li>
                            <li><a href="coupons.html"><span>Coupons</span></a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Purchase</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="purchase-list.html"><span>Purchases</span></a></li>
                            <li><a href="purchase-order-report.html"><span>Purchase Order</span></a></li>
                            <li><a href="purchase-returns.html"><span>Purchase Return</span></a></li>
                            <li><a href="manage-stocks.html"><span>Manage Stock</span></a></li>
                            <li><a href="stock-adjustment.html"><span>Stock Adjustment</span></a></li>
                            <li><a href="stock-transfer.html"><span>Stock Transfer</span></a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Expenses</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="expense-list.html">Expenses</a></li>
                            <li><a href="expense-category.html">Expense Category</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="submenu">
                <a href="javascript:void(0);"><img src="{{ asset('assets') }}/img/icons/users1.svg"
                        alt="img"><span>User
                        Management</span> <span class="menu-arrow"></span></a>
                <ul>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>People</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="customers.html"><span>Customers</span></a></li>
                            <li><a href="suppliers.html"><span>Suppliers</span></a></li>
                            <li><a href="store-list.html"><span>Stores</span></a></li>
                            <li><a href="warehouse.html"><span>Warehouses</span></a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Roles &amp; Permissions</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="roles-permissions.html"><span>Roles & Permissions</span></a></li>
                            <li><a href="delete-account.html"><span>Delete Account Request</span></a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Base UI</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="ui-alerts.html">Alerts</a></li>
                            <li><a href="ui-accordion.html">Accordion</a></li>
                            <li><a href="ui-avatar.html">Avatar</a></li>
                            <li><a href="ui-badges.html">Badges</a></li>
                            <li><a href="ui-borders.html">Border</a></li>
                            <li><a href="ui-buttons.html">Buttons</a></li>
                            <li><a href="ui-buttons-group.html">Button Group</a></li>
                            <li><a href="ui-breadcrumb.html">Breadcrumb</a></li>
                            <li><a href="ui-cards.html">Card</a></li>
                            <li><a href="ui-carousel.html">Carousel</a></li>
                            <li><a href="ui-colors.html">Colors</a></li>
                            <li><a href="ui-dropdowns.html">Dropdowns</a></li>
                            <li><a href="ui-grid.html">Grid</a></li>
                            <li><a href="ui-images.html">Images</a></li>
                            <li><a href="ui-lightbox.html">Lightbox</a></li>
                            <li><a href="ui-media.html">Media</a></li>
                            <li><a href="ui-modals.html">Modals</a></li>
                            <li><a href="ui-offcanvas.html">Offcanvas</a></li>
                            <li><a href="ui-pagination.html">Pagination</a></li>
                            <li><a href="ui-popovers.html">Popovers</a></li>
                            <li><a href="ui-progress.html">Progress</a></li>
                            <li><a href="ui-placeholders.html">Placeholders</a></li>
                            <li><a href="ui-rangeslider.html">Range Slider</a></li>
                            <li><a href="ui-spinner.html">Spinner</a></li>
                            <li><a href="ui-sweetalerts.html">Sweet Alerts</a></li>
                            <li><a href="ui-nav-tabs.html">Tabs</a></li>
                            <li><a href="ui-toasts.html">Toasts</a></li>
                            <li><a href="ui-tooltips.html">Tooltips</a></li>
                            <li><a href="ui-typography.html">Typography</a></li>
                            <li><a href="ui-video.html">Video</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Advanced UI</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="ui-ribbon.html">Ribbon</a></li>
                            <li><a href="ui-clipboard.html">Clipboard</a></li>
                            <li><a href="ui-drag-drop.html">Drag & Drop</a></li>
                            <li><a href="ui-rangeslider.html">Range Slider</a></li>
                            <li><a href="ui-rating.html">Rating</a></li>
                            <li><a href="ui-text-editor.html">Text Editor</a></li>
                            <li><a href="ui-counter.html">Counter</a></li>
                            <li><a href="ui-scrollbar.html">Scrollbar</a></li>
                            <li><a href="ui-stickynote.html">Sticky Note</a></li>
                            <li><a href="ui-timeline.html">Timeline</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Charts</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="chart-apex.html">Apex Charts</a></li>
                            <li><a href="chart-c3.html">Chart C3</a></li>
                            <li><a href="chart-js.html">Chart Js</a></li>
                            <li><a href="chart-morris.html">Morris Charts</a></li>
                            <li><a href="chart-flot.html">Flot Charts</a></li>
                            <li><a href="chart-peity.html">Peity Charts</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Primary Icons</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
                            <li><a href="icon-feather.html">Feather Icons</a></li>
                            <li><a href="icon-ionic.html">Ionic Icons</a></li>
                            <li><a href="icon-material.html">Material Icons</a></li>
                            <li><a href="icon-pe7.html">Pe7 Icons</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Secondary Icons</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="icon-simpleline.html">Simpleline Icons</a></li>
                            <li><a href="icon-themify.html">Themify Icons</a></li>
                            <li><a href="icon-weather.html">Weather Icons</a></li>
                            <li><a href="icon-typicon.html">Typicon Icons</a></li>
                            <li><a href="icon-flag.html">Flag Icons</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span> Forms</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li class="submenu submenu-two">
                                <a href="javascript:void(0);"><span>Form Elements</span><span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="form-basic-inputs.html">Basic Inputs</a></li>
                                    <li><a href="form-checkbox-radios.html">Checkbox & Radios</a></li>
                                    <li><a href="form-input-groups.html">Input Groups</a></li>
                                    <li><a href="form-grid-gutters.html">Grid & Gutters</a></li>
                                    <li><a href="form-select.html">Form Select</a></li>
                                    <li><a href="form-mask.html">Input Masks</a></li>
                                    <li><a href="form-fileupload.html">File Uploads</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two">
                                <a href="javascript:void(0);"><span> Layouts</span><span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="form-horizontal.html">Horizontal Form</a></li>
                                    <li><a href="form-vertical.html">Vertical Form</a></li>
                                    <li><a href="form-floating-labels.html">Floating Labels</a></li>
                                </ul>
                            </li>
                            <li><a href="form-validation.html">Form Validation</a></li>
                            <li><a href="form-select2.html">Select2</a></li>
                            <li><a href="form-wizard.html">Form Wizard</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Tables</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="tables-basic.html">Basic Tables </a></li>
                            <li><a href="data-tables.html">Data Table </a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="submenu">
                <a href="javascript:void(0);"><i data-feather="user"></i><span>Profile</span> <span
                        class="menu-arrow"></span></a>
                <ul>
                    <li><a href="profile.html"><span>Profile</span></a></li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Authentication</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Login<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="signin.html">Cover</a></li>
                                    <li><a href="signin-2.html">Illustration</a></li>
                                    <li><a href="signin-3.html">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Register<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="register.html">Cover</a></li>
                                    <li><a href="register-2.html">Illustration</a></li>
                                    <li><a href="register-3.html">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Forgot
                                    Password<span class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="forgot-password.html">Cover</a></li>
                                    <li><a href="forgot-password-2.html">Illustration</a></li>
                                    <li><a href="forgot-password-3.html">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Reset
                                    Password<span class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="reset-password.html">Cover</a></li>
                                    <li><a href="reset-password-2.html">Illustration</a></li>
                                    <li><a href="reset-password-3.html">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Email
                                    Verification<span class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="email-verification.html">Cover</a></li>
                                    <li><a href="email-verification-2.html">Illustration</a></li>
                                    <li><a href="email-verification-3.html">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">2 Step
                                    Verification<span class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="two-step-verification.html">Cover</a></li>
                                    <li><a href="two-step-verification-2.html">Illustration</a></li>
                                    <li><a href="two-step-verification-3.html">Basic</a></li>
                                </ul>
                            </li>
                            <li><a href="lock-screen.html">Lock Screen</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Pages</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="error-404.html">404 Error </a></li>
                            <li><a href="error-500.html">500 Error </a></li>
                            <li>
                                <a href="blank-page.html"><span>Blank Page</span> </a>
                            </li>
                            <li>
                                <a href="coming-soon.html"><span>Coming Soon</span> </a>
                            </li>
                            <li>
                                <a href="under-maintenance.html"><span>Under Maintenance</span> </a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Places</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="countries.html">Countries</a></li>
                            <li><a href="states.html">States</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Employees</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="employees-grid.html"><span>Employees</span></a></li>
                            <li><a href="department-grid.html"><span>Departments</span></a></li>
                            <li><a href="designation.html"><span>Designation</span></a></li>
                            <li><a href="shift.html"><span>Shifts</span></a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Attendence</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="attendance-employee.html">Employee Attendence</a></li>
                            <li><a href="attendance-admin.html">Admin Attendence</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Leaves &amp; Holidays</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="leaves-admin.html">Admin Leaves</a></li>
                            <li><a href="leaves-employee.html">Employee Leaves</a></li>
                            <li><a href="leave-types.html">Leave Types</a></li>
                            <li><a href="holidays.html"><span>Holidays</span></a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="payroll-list.html"><span>Payroll</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="payroll-list.html">Employee Salary</a></li>
                            <li><a href="payslip.html">Payslip</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="submenu">
                <a href="javascript:void(0);"><img src="{{ asset('assets') }}/img/icons/printer.svg"
                        alt="img"><span>Reports</span> <span class="menu-arrow"></span></a>
                <ul>
                    <li><a href="sales-report.html"><span>Sales Report</span></a></li>
                    <li><a href="purchase-report.html"><span>Purchase report</span></a></li>
                    <li><a href="inventory-report.html"><span>Inventory Report</span></a></li>
                    <li><a href="invoice-report.html"><span>Invoice Report</span></a></li>
                    <li><a href="supplier-report.html"><span>Supplier Report</span></a></li>
                    <li><a href="customer-report.html"><span>Customer Report</span></a></li>
                    <li><a href="expense-report.html"><span>Expense Report</span></a></li>
                    <li><a href="income-report.html"><span>Income Report</span></a></li>
                    <li><a href="tax-reports.html"><span>Tax Report</span></a></li>
                    <li><a href="profit-and-loss.html"><span>Profit & Loss</span></a></li>
                </ul>
            </li>
            <li class="submenu">
                <a href="javascript:void(0);"><img src="{{ asset('assets') }}/img/icons/settings.svg"
                        alt="img"><span>
                        Settings</span> <span class="menu-arrow"></span></a>
                <ul>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>General Settings</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="general-settings.html">Profile</a></li>
                            <li><a href="security-settings.html">Security</a></li>
                            <li><a href="notification.html">Notifications</a></li>
                            <li><a href="connected-apps.html">Connected Apps</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Website Settings</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="system-settings.html">System Settings</a></li>
                            <li><a href="company-settings.html">Company Settings </a></li>
                            <li><a href="localization-settings.html">Localization</a></li>
                            <li><a href="prefixes.html">Prefixes</a></li>
                            <li><a href="preference.html">Preference</a></li>
                            <li><a href="appearance.html">Appearance</a></li>
                            <li><a href="social-authentication.html">Social Authentication</a></li>
                            <li><a href="language-settings.html">Language</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>App Settings</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="invoice-settings.html">Invoice</a></li>
                            <li><a href="printer-settings.html">Printer</a></li>
                            <li><a href="pos-settings.html">POS</a></li>
                            <li><a href="custom-fields.html">Custom Fields</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>System Settings</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="email-settings.html">Email</a></li>
                            <li><a href="sms-gateway.html">SMS Gateways</a></li>
                            <li><a href="otp-settings.html">OTP</a></li>
                            <li><a href="gdpr-settings.html">GDPR Cookies</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Financial Settings</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="payment-gateway-settings.html">Payment Gateway</a></li>
                            <li><a href="bank-settings-grid.html">Bank Accounts</a></li>
                            <li><a href="tax-rates.html">Tax Rates</a></li>
                            <li><a href="currency-settings.html">Currencies</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Other Settings</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="storage-settings.html">Storage</a></li>
                            <li><a href="ban-ip-address.html">Ban IP Address</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);"><span>Documentation</span></a></li>
                    <li><a href="javascript:void(0);"><span>Changelog v2.0.7</span></a></li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Multi Level</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="javascript:void(0);">Level 1.1</a></li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Level 1.2<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="javascript:void(0);">Level 2.1</a></li>
                                    <li class="submenu submenu-two submenu-three"><a href="javascript:void(0);">Level
                                            2.2<span class="menu-arrow inside-submenu inside-submenu-two"></span></a>
                                        <ul>
                                            <li><a href="javascript:void(0);">Level 3.1</a></li>
                                            <li><a href="javascript:void(0);">Level 3.2</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
