<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <title>@yield('title', 'Core - Dashboard Builder')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/build/img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/build/img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontend/build/img/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('frontend/build/img/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('frontend/build/img/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <meta name="description" content="@yield('description', 'Clean and minimal Dashboard UI Design Kit')">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" media="all" href="{{ asset('frontend/build/css/app.min.css') }}">
    
    <script>
      var viewportmeta = document.querySelector('meta[name="viewport"]');
      if (viewportmeta) {
        if (screen.width < 375) {
          var newScale = screen.width / 375;
          viewportmeta.content = 'width=375, minimum-scale=' + newScale + ', maximum-scale=1.0, user-scalable=no, initial-scale=' + newScale + '';
        } else {
          viewportmeta.content = 'width=device-width, maximum-scale=1.0, initial-scale=1.0';
        }
      }
    </script>
    
    @stack('styles')
  </head>
  <body>
    <script>
      console.log(localStorage.getItem('darkMode'));
      if (localStorage.getItem('darkMode') === "on") {
          document.body.classList.add("dark");
          document.addEventListener("DOMContentLoaded", function() {
          document.querySelector('.js-theme input').checked = true;
          });
      }
    </script>
    
    <!-- page-->
    <div class="page" id="page">
      <!-- header-->
      <header class="header @guest unauthorized @endguest" data-id="#header">
        <button class="header__burger"></button>
        <div class="search">
          <div class="search__head">
            <button class="search__start">
              <svg class="icon icon-search">
                <use xlink:href="#icon-search"></use>
              </svg>
            </button>
            <button class="search__direction">
              <svg class="icon icon-arrow-left">
                <use xlink:href="#icon-arrow-left"></use>
              </svg>
            </button>
            <input class="search__input" type="text" placeholder="{{ __('Search or type a command') }}">
            <button class="search__result">⌘ F</button>
            <button class="search__close">
              <svg class="icon icon-close-circle">
                <use xlink:href="#icon-close-circle"></use>
              </svg>
            </button>
          </div>
          <div class="search__body">
            <!-- Search results will be populated dynamically -->
          </div>
        </div>
        <button class="header__search">
          <svg class="icon icon-search">
            <use xlink:href="#icon-search"></use>
          </svg>
        </button>
        
        @auth
        <div class="header__control">
          <a class="button header__button" href="{{ route('products.create') }}">
            <svg class="icon icon-add">
              <use xlink:href="#icon-add"></use>
            </svg><span>{{ __('Create') }}</span>
          </a>
          
          <!-- Messages dropdown -->
          <div class="header__item header__item_messages">
            <button class="header__head active">
              <svg class="icon icon-message">
                <use xlink:href="#icon-message"></use>
              </svg>
            </button>
            <div class="header__body">
              <div class="header__top">
                <div class="header__title">{{ __('Message') }}</div>
                <div class="actions actions_small">
                  <button class="actions__button">
                    <svg class="icon icon-more-horizontal">
                      <use xlink:href="#icon-more-horizontal"></use>
                    </svg>
                  </button>
                  <div class="actions__body">
                    <button class="actions__option">
                      <svg class="icon icon-check">
                        <use xlink:href="#icon-check"></use>
                      </svg>{{ __('Mark as read') }}
                    </button>
                    <button class="actions__option">
                      <svg class="icon icon-trash">
                        <use xlink:href="#icon-trash"></use>
                      </svg>{{ __('Delete message') }}
                    </button>
                  </div>
                </div>
              </div>
              <div class="header__list">
                <!-- Messages will be populated dynamically -->
              </div>
              <a class="button header__button" href="{{ route('messages.index') }}">{{ __('View in message center') }}</a>
            </div>
          </div>
          
          <!-- Notifications dropdown -->
          <div class="header__item header__item_notifications">
            <button class="header__head active">
              <svg class="icon icon-notification">
                <use xlink:href="#icon-notification"></use>
              </svg>
            </button>
            <div class="header__body">
              <div class="header__top">
                <div class="header__title">{{ __('Notification') }}</div>
                <div class="actions actions_small">
                  <button class="actions__button">
                    <svg class="icon icon-more-horizontal">
                      <use xlink:href="#icon-more-horizontal"></use>
                    </svg>
                  </button>
                  <div class="actions__body">
                    <button class="actions__option">
                      <svg class="icon icon-check">
                        <use xlink:href="#icon-check"></use>
                      </svg>{{ __('Mark as read') }}
                    </button>
                    <button class="actions__option">
                      <svg class="icon icon-trash">
                        <use xlink:href="#icon-trash"></use>
                      </svg>{{ __('Delete notifications') }}
                    </button>
                  </div>
                </div>
              </div>
              <div class="header__list">
                <!-- Notifications will be populated dynamically -->
              </div>
              <a class="button header__button" href="{{ route('notifications.index') }}">{{ __('See all notifications') }}</a>
            </div>
          </div>
          
          <!-- User dropdown -->
          <div class="header__item header__item_user">
            <button class="header__head">
              <img src="{{ auth()->user()->avatar ?? asset('frontend/build/img/content/avatar.jpg') }}" alt="Avatar">
            </button>
            <div class="header__body">
              <div class="header__nav">
                <a class="header__link" href="{{ route('profile.show') }}">{{ __('Profile') }}</a>
                <a class="header__link" href="{{ route('profile.edit') }}">{{ __('Edit profile') }}</a>
              </div>
              <div class="header__nav">
                <a class="header__link" href="{{ route('analytics.index') }}">
                  <svg class="icon icon-bar-chart">
                    <use xlink:href="#icon-bar-chart"></use>
                  </svg>{{ __('Analytics') }}
                </a>
                <a class="header__link" href="{{ route('affiliate.index') }}">
                  <svg class="icon icon-ticket">
                    <use xlink:href="#icon-ticket"></use>
                  </svg>{{ __('Affiliate center') }}
                </a>
                <a class="header__link" href="{{ route('creators.explore') }}">
                  <svg class="icon icon-grid">
                    <use xlink:href="#icon-grid"></use>
                  </svg>{{ __('Explore creators') }}
                </a>
              </div>
              <div class="header__nav">
                <a class="header__link color" href="{{ route('upgrade.pro') }}">
                  <svg class="icon icon-leaderboard">
                    <use xlink:href="#icon-leaderboard"></use>
                  </svg>{{ __('Upgrade to Pro') }}
                </a>
              </div>
              <div class="header__nav">
                <a class="header__link" href="{{ route('settings.index') }}">{{ __('Account settings') }}</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                  @csrf
                  <button type="submit" class="header__link">{{ __('Log out') }}</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        @else
        <div class="header__btns">
          <a class="header__link" href="{{ route('login') }}">{{ __('Sign in') }}</a>
          <a class="button header__button" href="{{ route('register') }}">{{ __('Sign up') }}</a>
        </div>
        @endauth
      </header>
      
      <!-- sidebar-->
      <div class="sidebar">
        <button class="sidebar__close">
          <svg class="icon icon-close">
            <use xlink:href="#icon-close"></use>
          </svg>
        </button>
        <a class="sidebar__logo" href="{{ route('dashboard') }}">
          <img class="some-icon" src="{{ asset('frontend/build/img/logo-dark.png') }}" alt="Core">
          <img class="some-icon-dark" src="{{ asset('frontend/build/img/logo-light.png') }}" alt="Core">
        </a>
        <div class="sidebar__menu">
          <a class="sidebar__item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <svg class="icon icon-home">
              <use xlink:href="#icon-home"></use>
            </svg>
            <svg class="icon icon-home-fill">
              <use xlink:href="#icon-home-fill"></use>
            </svg>{{ __('Home') }}
          </a>
          
          <div class="sidebar__item sidebar__item_dropdown">
            <div class="sidebar__top">
              <button class="sidebar__head">
                <svg class="icon icon-diamond">
                  <use xlink:href="#icon-diamond"></use>
                </svg>
                <svg class="icon icon-diamond-fill">
                  <use xlink:href="#icon-diamond-fill"></use>
                </svg>{{ __('Products') }}
                <svg class="icon icon-arrow-down">
                  <use xlink:href="#icon-arrow-down"></use>
                </svg>
              </button>
              <a class="sidebar__add" href="{{ route('products.create') }}">
                <svg class="icon icon-plus">
                  <use xlink:href="#icon-plus"></use>
                </svg>
              </a>
            </div>
            <div class="sidebar__body">
              <a class="sidebar__link" href="{{ route('products.dashboard') }}">
                {{ __('Dashboard') }}
                <svg class="icon icon-arrow-next">
                  <use xlink:href="#icon-arrow-next"></use>
                </svg>
              </a>
              <a class="sidebar__link" href="{{ route('products.drafts') }}">
                {{ __('Drafts') }}
                <svg class="icon icon-arrow-next">
                  <use xlink:href="#icon-arrow-next"></use>
                </svg>
                @if($draftCount ?? 0 > 0)
                <div class="sidebar__counter" style="background-color: #FFBC99">{{ $draftCount }}</div>
                @endif
              </a>
              <a class="sidebar__link" href="{{ route('products.released') }}">
                {{ __('Released') }}
                <svg class="icon icon-arrow-next">
                  <use xlink:href="#icon-arrow-next"></use>
                </svg>
              </a>
              <a class="sidebar__link" href="{{ route('products.comments') }}">
                {{ __('Comments') }}
                <svg class="icon icon-arrow-next">
                  <use xlink:href="#icon-arrow-next"></use>
                </svg>
              </a>
              <a class="sidebar__link" href="{{ route('products.scheduled') }}">
                {{ __('Scheduled') }}
                <svg class="icon icon-arrow-next">
                  <use xlink:href="#icon-arrow-next"></use>
                </svg>
                @if($scheduledCount ?? 0 > 0)
                <div class="sidebar__counter" style="background-color: #B5E4CA">{{ $scheduledCount }}</div>
                @endif
              </a>
            </div>
          </div>
          
          <div class="sidebar__item sidebar__item_dropdown">
            <button class="sidebar__head">
              <svg class="icon icon-profile-circle">
                <use xlink:href="#icon-profile-circle"></use>
              </svg>
              <svg class="icon icon-profile-circle-fill">
                <use xlink:href="#icon-profile-circle-fill"></use>
              </svg>{{ __('Customers') }}
              <svg class="icon icon-arrow-down">
                <use xlink:href="#icon-arrow-down"></use>
              </svg>
            </button>
            <div class="sidebar__body">
              <a class="sidebar__link" href="{{ route('customers.overview') }}">
                {{ __('Overview') }}
                <svg class="icon icon-arrow-next">
                  <use xlink:href="#icon-arrow-next"></use>
                </svg>
              </a>
              <a class="sidebar__link" href="{{ route('customers.list') }}">
                {{ __('Customer list') }}
                <svg class="icon icon-arrow-next">
                  <use xlink:href="#icon-arrow-next"></use>
                </svg>
              </a>
            </div>
          </div>
          
          <a class="sidebar__item {{ request()->routeIs('shop.*') ? 'active' : '' }}" href="{{ route('shop.index') }}">
            <svg class="icon icon-store">
              <use xlink:href="#icon-store"></use>
            </svg>
            <svg class="icon icon-store-fill">
              <use xlink:href="#icon-store-fill"></use>
            </svg>{{ __('Shop') }}
          </a>
          
          <div class="sidebar__item sidebar__item_dropdown">
            <button class="sidebar__head">
              <svg class="icon icon-pie-chart">
                <use xlink:href="#icon-pie-chart"></use>
              </svg>
              <svg class="icon icon-pie-chart-fill">
                <use xlink:href="#icon-pie-chart-fill"></use>
              </svg>{{ __('Income') }}
              <svg class="icon icon-arrow-down">
                <use xlink:href="#icon-arrow-down"></use>
              </svg>
            </button>
            <div class="sidebar__body">
              <a class="sidebar__link" href="{{ route('income.earning') }}">
                {{ __('Earning') }}
                <svg class="icon icon-arrow-next">
                  <use xlink:href="#icon-arrow-next"></use>
                </svg>
              </a>
              <a class="sidebar__link" href="{{ route('income.refunds') }}">
                {{ __('Refunds') }}
                <svg class="icon icon-arrow-next">
                  <use xlink:href="#icon-arrow-next"></use>
                </svg>
              </a>
              <a class="sidebar__link" href="{{ route('income.payouts') }}">
                {{ __('Payouts') }}
                <svg class="icon icon-arrow-next">
                  <use xlink:href="#icon-arrow-next"></use>
                </svg>
              </a>
              <a class="sidebar__link" href="{{ route('income.statements') }}">
                {{ __('Statements') }}
                <svg class="icon icon-arrow-next">
                  <use xlink:href="#icon-arrow-next"></use>
                </svg>
              </a>
            </div>
          </div>
          
          <a class="sidebar__item {{ request()->routeIs('promote.*') ? 'active' : '' }}" href="{{ route('promote.index') }}">
            <svg class="icon icon-promotion">
              <use xlink:href="#icon-promotion"></use>
            </svg>
            <svg class="icon icon-promotion-fill">
              <use xlink:href="#icon-promotion-fill"></use>
            </svg>{{ __('Promote') }}
          </a>
        </div>
        
        <button class="sidebar__toggle">
          <svg class="icon icon-arrow-right">
            <use xlink:href="#icon-arrow-right"></use>
          </svg>
          <svg class="icon icon-close">
            <use xlink:href="#icon-close"></use>
          </svg>
        </button>
        
        <div class="sidebar__foot">
          <button class="sidebar__help">
            <svg class="icon icon-help">
              <use xlink:href="#icon-help"></use>
            </svg>{{ __('Help & getting started') }}
            <div class="sidebar__counter">8</div>
          </button>
          <label class="theme js-theme">
            <input class="theme__input" type="checkbox"/>
            <span class="theme__inner">
              <span class="theme__box">
                <svg class="icon icon-sun">
                  <use xlink:href="#icon-sun"></use>
                </svg>{{ __('Light') }}
              </span>
              <span class="theme__box">
                <svg class="icon icon-moon">
                  <use xlink:href="#icon-moon"></use>
                </svg>{{ __('Dark') }}
              </span>
            </span>
          </label>
        </div>
      </div>
      
      <div class="overlay"></div>
      
      <!-- help-->
      <div class="help">
        <div class="help__head">
          <svg class="icon icon-help">
            <use xlink:href="#icon-help"></use>
          </svg>{{ __('Help & getting started') }}
          <button class="help__close">
            <svg class="icon icon-close">
              <use xlink:href="#icon-close"></use>
            </svg>
          </button>
        </div>
        <div class="help__list">
          <!-- Help items will be populated dynamically -->
        </div>
        <div class="help__menu">
          <a class="help__link" href="{{ route('upgrade.pro') }}">
            <svg class="icon icon-lightning">
              <use xlink:href="#icon-lightning"></use>
            </svg>{{ __('Upgrade to Pro') }}
            <div class="help__arrow">
              <svg class="icon icon-arrow-next">
                <use xlink:href="#icon-arrow-next"></use>
              </svg>
            </div>
          </a>
          <a class="help__link" href="{{ route('download.app') }}">
            <svg class="icon icon-download">
              <use xlink:href="#icon-download"></use>
            </svg>{{ __('Download desktop app') }}
          </a>
          <a class="help__link" href="{{ route('messages.index') }}">
            <svg class="icon icon-message">
              <use xlink:href="#icon-message"></use>
            </svg>{{ __('Message center') }}
            <div class="help__counter">8</div>
          </a>
        </div>
      </div>
      <div class="overlay"></div>
      
      <!-- inner-->
      <div class="page__inner">
        <div class="page__container">
          @yield('content')
        </div>
      </div>
    </div>
    
    <!-- SVG Icons -->
    <svg style="display: none;">
      <defs>
        <!-- Add all the SVG icons used in the template -->
        <symbol id="icon-search" viewBox="0 0 24 24">
          <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </symbol>
        <symbol id="icon-close" viewBox="0 0 24 24">
          <path d="M6 18L18 6M6 6l12 12"/>
        </symbol>
        <symbol id="icon-arrow-left" viewBox="0 0 24 24">
          <path d="M19 12H5M12 19l-7-7 7-7"/>
        </symbol>
        <symbol id="icon-arrow-right" viewBox="0 0 24 24">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </symbol>
        <symbol id="icon-arrow-down" viewBox="0 0 24 24">
          <path d="M6 9l6 6 6-6"/>
        </symbol>
        <symbol id="icon-arrow-next" viewBox="0 0 24 24">
          <path d="M9 18l6-6-6-6"/>
        </symbol>
        <symbol id="icon-add" viewBox="0 0 24 24">
          <path d="M12 5v14M5 12h14"/>
        </symbol>
        <symbol id="icon-plus" viewBox="0 0 24 24">
          <path d="M12 5v14M5 12h14"/>
        </symbol>
        <symbol id="icon-message" viewBox="0 0 24 24">
          <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
        </symbol>
        <symbol id="icon-notification" viewBox="0 0 24 24">
          <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
        </symbol>
        <symbol id="icon-more-horizontal" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/>
        </symbol>
        <symbol id="icon-check" viewBox="0 0 24 24">
          <path d="M20 6L9 17l-5-5"/>
        </symbol>
        <symbol id="icon-trash" viewBox="0 0 24 24">
          <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
        </symbol>
        <symbol id="icon-bar-chart" viewBox="0 0 24 24">
          <path d="M12 20V10M18 20V4M6 20v-6"/>
        </symbol>
        <symbol id="icon-ticket" viewBox="0 0 24 24">
          <path d="M2 9V7a2 2 0 012-2h16a2 2 0 012 2v2M2 9v6a2 2 0 002 2h16a2 2 0 002-2V9M2 9h20"/>
        </symbol>
        <symbol id="icon-grid" viewBox="0 0 24 24">
          <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
        </symbol>
        <symbol id="icon-leaderboard" viewBox="0 0 24 24">
          <path d="M6 9H4.5a2.5 2.5 0 000 5H6M10 6H8.5a2.5 2.5 0 000 5H10M14 3h-1.5a2.5 2.5 0 000 5H14M18 9h-1.5a2.5 2.5 0 000 5H18"/>
        </symbol>
        <symbol id="icon-home" viewBox="0 0 24 24">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </symbol>
        <symbol id="icon-home-fill" viewBox="0 0 24 24">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </symbol>
        <symbol id="icon-diamond" viewBox="0 0 24 24">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </symbol>
        <symbol id="icon-diamond-fill" viewBox="0 0 24 24">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </symbol>
        <symbol id="icon-profile-circle" viewBox="0 0 24 24">
          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M16 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </symbol>
        <symbol id="icon-profile-circle-fill" viewBox="0 0 24 24">
          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M16 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </symbol>
        <symbol id="icon-store" viewBox="0 0 24 24">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </symbol>
        <symbol id="icon-store-fill" viewBox="0 0 24 24">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </symbol>
        <symbol id="icon-pie-chart" viewBox="0 0 24 24">
          <path d="M21.21 15.89A10 10 0 118 2.83M22 12A10 10 0 0012 2v10z"/>
        </symbol>
        <symbol id="icon-pie-chart-fill" viewBox="0 0 24 24">
          <path d="M21.21 15.89A10 10 0 118 2.83M22 12A10 10 0 0012 2v10z"/>
        </symbol>
        <symbol id="icon-promotion" viewBox="0 0 24 24">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </symbol>
        <symbol id="icon-promotion-fill" viewBox="0 0 24 24">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </symbol>
        <symbol id="icon-help" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3M12 17h.01"/>
        </symbol>
        <symbol id="icon-sun" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        </symbol>
        <symbol id="icon-moon" viewBox="0 0 24 24">
          <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
        </symbol>
        <symbol id="icon-lightning" viewBox="0 0 24 24">
          <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
        </symbol>
        <symbol id="icon-download" viewBox="0 0 24 24">
          <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
        </symbol>
        <symbol id="icon-close-circle" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/>
        </symbol>
        <symbol id="icon-edit" viewBox="0 0 24 24">
          <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
          <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </symbol>
        <symbol id="icon-star-fill" viewBox="0 0 24 24">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </symbol>
        <symbol id="icon-star-stroke" viewBox="0 0 24 24">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </symbol>
        <symbol id="icon-filter" viewBox="0 0 24 24">
          <polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46"/>
        </symbol>
        <symbol id="icon-shopping-bag" viewBox="0 0 24 24">
          <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18"/>
        </symbol>
        <symbol id="icon-activity" viewBox="0 0 24 24">
          <polyline points="22,12 18,12 15,21 9,3 6,12 2,12"/>
        </symbol>
        <symbol id="icon-arrow-top" viewBox="0 0 24 24">
          <path d="M18 15l-6-6-6 6"/>
        </symbol>
        <symbol id="icon-arrow-bottom" viewBox="0 0 24 24">
          <path d="M6 9l6 6 6-6"/>
        </symbol>
        <symbol id="icon-info" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
        </symbol>
        <symbol id="icon-schedule" viewBox="0 0 24 24">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </symbol>
        <symbol id="icon-design" viewBox="0 0 24 24">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </symbol>
        <symbol id="icon-photos" viewBox="0 0 24 24">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
          <circle cx="8.5" cy="8.5" r="1.5"/>
          <polyline points="21,15 16,10 5,21"/>
        </symbol>
        <symbol id="icon-heart-fill" viewBox="0 0 24 24">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </symbol>
        <symbol id="icon-twitter" viewBox="0 0 24 24">
          <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
        </symbol>
        <symbol id="icon-instagram" viewBox="0 0 24 24">
          <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
          <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
          <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
        </symbol>
        <symbol id="icon-pinterest" viewBox="0 0 24 24">
          <path d="M8 21h8a5 5 0 0 0 5-5V8a5 5 0 0 0-5-5H8a5 5 0 0 0-5 5v8a5 5 0 0 0 5 5z"/>
          <path d="M12 7v10M8 12h8"/>
        </symbol>
      </defs>
    </svg>
    
    @stack('scripts')
    <script src="{{ asset('frontend/build/js/app.min.js') }}"></script>
  </body>
</html>