@php
    $setting = \App\Models\SystemSetting::first();
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <!-- App Brand and Logo Section -->
    <div class="app-brand demo">
        <a href="" class="app-brand-link">
            @if ($setting && $setting->logo)
                <!-- Display the logo if it exists in system settings -->
                <img src="{{ asset($setting->logo) }}" style="height: 95px;width: 176px;" alt="Logo">
            @else
                <!-- Display default logo if system settings logo is not available -->
                <img src="{{ asset('backend/images/logo.png') }}" style="height: 60px;width: 110px;" alt="Default Logo">
            @endif
        </a>
        <!-- Menu toggle button for mobile view -->
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="align-middle bx bx-chevron-left bx-sm"></i>
        </a>
    </div>
    <!-- Dashboard Menu Section -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Dashboard</span></li>

    <ul class="py-1 menu-inner">
        <!-- Dashboard Menu Item -->
        <li class="menu-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.dashboard') }}">
                <i class="menu-icon tf-icons bx bx-home-circle" style="color: #2A6880;"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <!-- Core Publications Menu Item -->
        <li class="menu-item {{ Request::routeIs('admin.core_publication.*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.core_publication.index') }}">
                <i class="menu-icon tf-icons bx bx-book-alt" style="color: #2A6880;"></i>
                <span class="menu-title">Core Publications</span>
            </a>
        </li>
        <!-- Key Documents Menu Item -->
        <li class="menu-item {{ Request::routeIs('admin.key.document.*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.key.document.index') }}">
                <i class="menu-icon tf-icons bx bx-file" style="color: #2A6880;"></i>
                <span class="menu-title">Key Documents</span>
            </a>
        </li>

        <!-- Preceding Councils Menu Item -->
        <li class="menu-item {{ Request::routeIs('admin.presiding_councils.*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.presiding_councils.index') }}">
                <i class="menu-icon tf-icons bx bx-group" style="color: #2A6880;"></i>
                <span class="menu-title">Presiding Council</span>
            </a>
        </li>

        <!-- Category Menu Item -->
        <li class="menu-item {{ Request::routeIs('admin.category.*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.category.index') }}">
                <i class="menu-icon tf-icons bx bx-category" style="color: #2A6880;"></i>
                <span class="menu-title">
                    MO Category</span>
            </a>
        </li>

        <!-- Publication Create Item -->
        <li class="menu-item {{ Request::routeIs('admin.publication.*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.publication.index') }}">
                <i class="menu-icon tf-icons bx bx-book-open" style="color: #2A6880;"></i>
                <span class="menu-title">MO Articles</span>
            </a>
        </li>


        <!-- Membership Create Item -->
        <li class="menu-item {{ Request::routeIs('admin.membership.*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.membership.index') }}">
                <i class="menu-icon tf-icons bx bx-user-plus" style="color: #2A6880;"></i>
                <span class="menu-title">Create Membership</span>
            </a>
        </li>
        <!-- Membership  History Item -->
        <li class="menu-item {{ Request::routeIs('admin.membership-history.*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.membership-history.index') }}">
                <i class="menu-icon tf-icons bx bx-id-card" style="color: #2A6880;"></i>
                <span class="menu-title">Membership History</span>
            </a>
        </li>
        <!-- Donation History Item -->
        <li class="menu-item {{ Request::routeIs('admin.donation-history.*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.donation-history.index') }}">
                <i class="menu-icon tf-icons bx bx-credit-card" style="color: #2A6880;"></i>
                <span class="menu-title">Donation History</span>
            </a>
        </li>
        <!-- Conaact list History Item -->
        <li class="menu-item {{ Request::routeIs('admin.contact-history.*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.contact-history.index') }}">
                <i class="menu-icon tf-icons bx bx-envelope" style="color: #2A6880;"></i>
                <span class="menu-title">Contact List</span>
            </a>
        </li>
        <!-- Free membership -->
        <li class="menu-item {{ Request::routeIs('admin.free-membership.*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.free-membership.index') }}">
                <i class="menu-icon tf-icons bx bx-gift" style="color: #2A6880;"></i>
                <span class="menu-title">Free membership</span>
            </a>
        </li>
        <!-- CMS Section -->
        <li class="menu-item {{ request()->routeIs('admin.cms.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog" style="color: #2A6880;"></i>
                <div data-i18n="Layouts">CMS</div>
            </a>

            <ul class="menu-sub">
                <!-- Home Section -->
                <li
                    class="menu-item {{ request()->routeIs('admin.cms.home.*') && !request()->routeIs('admin.cms.home.index') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-home" style="color: #2A6880;"></i>
                        <div data-i18n="Layouts">Home</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->routeIs('admin.cms.home.banner.index') ? 'active' : '' }}">
                            <a class="menu-link" href="{{ route('admin.cms.home.banner.index') }}">
                                <i class="menu-icon tf-icons bx bx-image" style="color: #2A6880;"></i>
                                <span class="menu-title">Banner Section</span>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('admin.cms.home.about.index') ? 'active' : '' }}">
                            <a class="menu-link" href="{{ route('admin.cms.home.about.index') }}">
                                <i class="menu-icon tf-icons bx bx-info-circle" style="color: #2A6880;"></i>
                                <span class="menu-title">About Section</span>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ request()->routeIs('admin.cms.home.core.publication.index') ? 'active' : '' }}">
                            <a class="menu-link" href="{{ route('admin.cms.home.core.publication.index') }}">
                                <i class="menu-icon tf-icons bx bx-book-open" style="color: #2A6880;"></i>
                                <span class="menu-title">Core Publication</span>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ request()->routeIs('admin.cms.home.presiding.council.index') ? 'active' : '' }}">
                            <a class="menu-link" href="{{ route('admin.cms.home.presiding.council.index') }}">
                                <i class="menu-icon tf-icons bx bx-group" style="color: #2A6880;"></i>
                                <span class="menu-title">Presiding Council</span>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ request()->routeIs('admin.cms.home.how.join.group.index') ? 'active' : '' }}">
                            <a class="menu-link" href="{{ route('admin.cms.home.how.join.group.index') }}">
                                <i class="menu-icon tf-icons bx bx-joystick" style="color: #2A6880;"></i>
                                <span class="menu-title">How To Join Group</span>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ request()->routeIs('admin.cms.home.history.index') ? 'active' : '' }}">
                            <a class="menu-link" href="{{ route('admin.cms.home.history.index') }}">
                                <i class="menu-icon tf-icons bx bx-time" style="color: #2A6880;"></i>
                                <span class="menu-title">History</span>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ request()->routeIs('admin.cms.home.donation.index') ? 'active' : '' }}">
                            <a class="menu-link" href="{{ route('admin.cms.home.donation.index') }}">
                                <i class="menu-icon tf-icons bx bx-gift" style="color: #2A6880;"></i>
                                <span class="menu-title">Donation</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Key Document Section -->
                <li class="menu-item {{ request()->routeIs('admin.cms.key.document.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-file" style="color: #2A6880;"></i>
                        Key Document
                    </a>
                    <ul class="menu-sub">
                        <li
                            class="menu-item {{ request()->routeIs('admin.cms.key.document.banner.index') ? 'active' : '' }}">
                            <a class="menu-link" href="{{ route('admin.cms.key.document.banner.index') }}">
                                <i class="menu-icon tf-icons bx bx-image" style="color: #2A6880;"></i>
                                <span class="menu-title">Banner Section</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Contact Section -->
                <li class="menu-item {{ request()->routeIs('admin.cms.contact.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-mail-send" style="color: #2A6880;"></i>
                        Contact
                    </a>
                    <ul class="menu-sub">
                        <li
                            class="menu-item {{ request()->routeIs('admin.cms.contact.banner.index') ? 'active' : '' }}">
                            <a class="menu-link" href="{{ route('admin.cms.contact.banner.index') }}">
                                <i class="menu-icon tf-icons bx bx-phone" style="color: #2A6880;"></i>
                                <span class="menu-title">Contact Banner</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Presiding Council Section -->
                <li class="menu-item {{ request()->routeIs('admin.cms.presiding.council.*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-user" style="color: #2A6880;"></i>
                        Presiding Council
                    </a>
                    <ul class="menu-sub">
                        <li
                            class="menu-item {{ request()->routeIs('admin.cms.presiding.council.banner.index') ? 'active' : '' }}">
                            <a class="menu-link" href="{{ route('admin.cms.presiding.council.banner.index') }}">
                                <i class="menu-icon tf-icons bx bx-image" style="color: #2A6880;"></i>
                                <span class="menu-title">Banner Section</span>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ request()->routeIs('admin.cms.presiding.council.about.index') ? 'active' : '' }}">
                            <a class="menu-link" href="{{ route('admin.cms.presiding.council.about.index') }}">
                                <i class="menu-icon tf-icons bx bx-info-circle" style="color: #2A6880;"></i>
                                <span class="menu-title">About Section</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Membership Section -->
                <li class="menu-item {{ request()->routeIs('admin.cms.membership.index') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.cms.membership.index') }}">
                        <i class="menu-icon tf-icons bx bx-id-card" style="color: #2A6880;"></i>
                        <span class="menu-title">Membership</span>
                    </a>
                </li>
                <!--default membership articles-->
                <li
                    class="menu-item {{ request()->routeIs('admin.cms.default.membership.article.index') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.cms.default.membership.article.index') }}">
                        <i class="menu-icon tf-icons bx bx-id-card" style="color: #2A6880;"></i>
                        <span class="menu-title">Default Articles</span>
                    </a>
                </li>
            </ul>
            <!--/ CMS Section -->
        </li>

        <!-- Settings Section -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Settings</span></li>

        <!-- Settings Menu Item -->
        <li
            class="menu-item {{ Request::routeIs('admin.system-settings') || Request::routeIs('admin.mail-settings') || Request::routeIs('admin.stripe-settings') || Request::routeIs('admin.paypal-settings') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog" style="color: #2A6880;"></i>
                <div data-i18n="Layouts">Settings</div>
            </a>

            <ul class="menu-sub">
                <!-- System Settings Menu Item -->
                <li class="menu-item {{ Request::routeIs('admin.system-settings') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.system-settings') }}">
                        <i class="menu-icon tf-icons bx bx-cog" style="color: #2A6880;"></i>
                        System Settings
                    </a>
                </li>

                <!-- Mail Settings Menu Item -->
                <li class="menu-item {{ Request::routeIs('admin.mail-settings') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.mail-settings') }}">
                        <i class="menu-icon tf-icons bx bx-envelope" style="color: #2A6880;"></i>
                        Mail Setting
                    </a>
                </li>
                <!-- Paypal Settings Menu Item -->
                <li class="menu-item {{ Request::routeIs('admin.paypal-settings') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.paypal-settings') }}">
                        <i class="menu-icon tf-icons bx bx-money" style="color: #2A6880;"></i>
                        Paypal Setting
                    </a>
                </li>
                <!-- Stripe Settings Menu Item -->
                <li class="menu-item {{ Request::routeIs('admin.stripe-settings') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('admin.stripe-settings') }}">
                        <i class="menu-icon tf-icons bx bx-credit-card" style="color: #2A6880;"></i>
                        Stripe Setting
                    </a>
                </li>
            </ul>
        </li>

        <!--terms&&condition-->
        <li class="menu-item {{ Request::routeIs('admin.terms.condition.index') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.terms.condition.index') }}">
                <i class="menu-icon tf-icons bx bx-file"></i>


                <div data-i18n="Support">Terms & Condition</div>
            </a>
        </li>


        <!--privacyPolicy-->
        <li class="menu-item {{ Request::routeIs('admin.privacy.policy.index') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.privacy.policy.index') }}">
                <i class="menu-icon tf-icons bx bx-lock"></i>

                <div data-i18n="Support">Privacy Policy</div>
            </a>
        </li>


        <!-- Profile Settings Menu Item -->
        <li class="menu-item {{ Request::routeIs('admin.profile') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('admin.profile') }}">
                <i class="menu-icon tf-icons bx bxs-user" style="color: #2A6880;"></i>
                <div data-i18n="Support">Profile Setting</div>
            </a>
        </li>
    </ul>
</aside>
