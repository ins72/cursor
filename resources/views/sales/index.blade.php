@extends('layouts.taskly-modern')

@section('title', __('Sales Page') . ' - ' . config('app.name'))

@section('description', __('Browse and purchase amazing products from our creators'))

@section('content')
<div class="shop">
  <div class="shop__background">
    <img src="{{ asset('frontend/build/img/content/bg-shop.jpg') }}" alt="Background">
  </div>
  
  <div class="shop__tabs card js-tabs">
    <div class="shop__profile">
      <div class="shop__details">
        <div class="shop__avatar">
          <img src="{{ $seller->avatar ?? asset('frontend/build/img/content/avatar.jpg') }}" alt="Avatar">
          @auth
          @if(auth()->user()->id === $seller->id)
          <button class="shop__add">
            <svg class="icon icon-add">
              <use xlink:href="#icon-add"></use>
            </svg>
          </button>
          @endif
          @endauth
        </div>
        <div class="shop__wrap">
          <div class="h4 shop__man">{{ $seller->name ?? 'Featured Creator' }}</div>
          <div class="shop__info">{{ $seller->bio ?? 'Dream big. Think different. Do great!' }}</div>
        </div>
      </div>
      <div class="shop__contacts">
        @if($seller->social_links ?? false)
        <div class="shop__socials">
          @if($seller->social_links->twitter)
          <a class="shop__social" href="{{ $seller->social_links->twitter }}" target="_blank">
            <svg class="icon icon-twitter">
              <use xlink:href="#icon-twitter"></use>
            </svg>
          </a>
          @endif
          @if($seller->social_links->instagram)
          <a class="shop__social" href="{{ $seller->social_links->instagram }}" target="_blank">
            <svg class="icon icon-instagram">
              <use xlink:href="#icon-instagram"></use>
            </svg>
          </a>
          @endif
          @if($seller->social_links->pinterest)
          <a class="shop__social" href="{{ $seller->social_links->pinterest }}" target="_blank">
            <svg class="icon icon-pinterest">
              <use xlink:href="#icon-pinterest"></use>
            </svg>
          </a>
          @endif
        </div>
        @endif
        @auth
        @if(auth()->user()->id !== ($seller->id ?? null))
        <form method="POST" action="{{ route('follow.store') }}" class="inline">
          @csrf
          <input type="hidden" name="seller_id" value="{{ $seller->id ?? '' }}">
          <button type="submit" class="button shop__button">
            {{ auth()->user()->isFollowing($seller->id ?? 0) ? __('Unfollow') : __('Follow') }}
          </button>
        </form>
        @endif
        @else
        <a href="{{ route('login') }}" class="button shop__button">{{ __('Follow') }}</a>
        @endauth
      </div>
    </div>
    
    <div class="shop__control">
      <div class="shop__nav">
        <a class="shop__link js-tabs-link active" href="#">{{ __('Products') }}</a>
        <a class="shop__link js-tabs-link" href="#">{{ __('Followers') }}</a>
        <a class="shop__link js-tabs-link" href="#">{{ __('Following') }}</a>
      </div>
      
      <div class="shop__select">
        <select class="select select_small" name="sort" onchange="this.form.submit()">
          <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>{{ __('Most recent') }}</option>
          <option value="new" {{ request('sort') == 'new' ? 'selected' : '' }}>{{ __('Most new') }}</option>
          <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>{{ __('Most popular') }}</option>
        </select>
      </div>
      
      <div class="filters">
        <button class="button-square-stroke button-small filters__head">
          <svg class="icon icon-filter">
            <use xlink:href="#icon-filter"></use>
          </svg>
        </button>
        <div class="filters__body">
          <div class="filters__top">
            <div class="title-red filters__title">{{ __('Showing :count of :total products', ['count' => $products->count(), 'total' => $products->total()]) }}</div>
            <button class="filters__close">
              <svg class="icon icon-close">
                <use xlink:href="#icon-close"></use>
              </svg>
            </button>
          </div>
          
          <form class="form" method="GET" action="{{ route('sales.index') }}">
            <input class="form__input" type="text" name="search" placeholder="{{ __('Search for products') }}" value="{{ request('search') }}" required="required" autocomplete="off"/>
            <button class="form__button">
              <svg class="icon icon-search">
                <use xlink:href="#icon-search"></use>
              </svg>
            </button>
          </form>
          
          <div class="filters__group">
            <div class="filters__item">
              <div class="field">
                <div class="field__label">{{ __('Sort by') }}</div>
                <div class="field__wrap">
                  <select class="select" name="sort_by" onchange="this.form.submit()">
                    <option value="featured" {{ request('sort_by') == 'featured' ? 'selected' : '' }}>{{ __('Featured') }}</option>
                    <option value="last" {{ request('sort_by') == 'last' ? 'selected' : '' }}>{{ __('Last') }}</option>
                    <option value="new" {{ request('sort_by') == 'new' ? 'selected' : '' }}>{{ __('New') }}</option>
                  </select>
                </div>
              </div>
            </div>
            
            <div class="filters__item">
              <div class="filters__label">{{ __('Showing') }}</div>
              <div class="filters__list">
                <label class="checkbox checkbox_reverse">
                  <input class="checkbox__input" type="checkbox" name="category[]" value="all" {{ in_array('all', request('category', [])) ? 'checked' : '' }}>
                  <span class="checkbox__inner">
                    <span class="checkbox__tick"></span>
                    <span class="checkbox__text">{{ __('All products') }}</span>
                  </span>
                </label>
                <label class="checkbox checkbox_reverse">
                  <input class="checkbox__input" type="checkbox" name="category[]" value="ui-kit" {{ in_array('ui-kit', request('category', [])) ? 'checked' : '' }}>
                  <span class="checkbox__inner">
                    <span class="checkbox__tick"></span>
                    <span class="checkbox__text">{{ __('UI Kit') }}</span>
                  </span>
                </label>
                <label class="checkbox checkbox_reverse">
                  <input class="checkbox__input" type="checkbox" name="category[]" value="illustration" {{ in_array('illustration', request('category', [])) ? 'checked' : '' }}>
                  <span class="checkbox__inner">
                    <span class="checkbox__tick"></span>
                    <span class="checkbox__text">{{ __('Illustration') }}</span>
                  </span>
                </label>
                <label class="checkbox checkbox_reverse">
                  <input class="checkbox__input" type="checkbox" name="category[]" value="wireframe" {{ in_array('wireframe', request('category', [])) ? 'checked' : '' }}>
                  <span class="checkbox__inner">
                    <span class="checkbox__tick"></span>
                    <span class="checkbox__text">{{ __('Wireframe kit') }}</span>
                  </span>
                </label>
                <label class="checkbox checkbox_reverse">
                  <input class="checkbox__input" type="checkbox" name="category[]" value="icons" {{ in_array('icons', request('category', [])) ? 'checked' : '' }}>
                  <span class="checkbox__inner">
                    <span class="checkbox__tick"></span>
                    <span class="checkbox__text">{{ __('Icons') }}</span>
                  </span>
                </label>
              </div>
            </div>
            
            <div class="filters__item">
              <div class="filters__label">{{ __('Price') }}</div>
              <div class="filters__range js-slider" data-min="0" data-max="100" data-start="{{ request('price_min', 20) }}" data-end="{{ request('price_max', 50) }}" data-step="1" data-tooltips="true" data-prefix="$"></div>
              <input type="hidden" name="price_min" value="{{ request('price_min', 20) }}">
              <input type="hidden" name="price_max" value="{{ request('price_max', 50) }}">
            </div>
            
            <div class="filters__item">
              <div class="filters__box">
                <div class="field">
                  <div class="field__label">{{ __('Rating') }}</div>
                  <div class="field__wrap">
                    <select class="select select_up" name="rating">
                      <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>{{ __('1 and up') }}</option>
                      <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>{{ __('2 and up') }}</option>
                      <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>{{ __('3 and up') }}</option>
                      <option value="4" {{ request('rating') == '4' || !request('rating') ? 'selected' : '' }}>{{ __('4 and up') }}</option>
                      <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>{{ __('5') }}</option>
                    </select>
                  </div>
                </div>
                <svg class="icon icon-heart-fill">
                  <use xlink:href="#icon-heart-fill"></use>
                </svg>
              </div>
            </div>
          </div>
          
          <div class="filters__btns">
            <a href="{{ route('sales.index') }}" class="button-stroke filters__button">{{ __('Reset') }}</a>
            <button type="submit" class="button filters__button">{{ __('Apply') }}</button>
          </div>
        </div>
        <div class="filters__overlay"></div>
      </div>
    </div>
    
    <div class="shop__container">
      <div class="shop__tab js-tabs-item" style="display: block;">
        <div class="shop__products">
          @forelse($products as $product)
          <div class="summary">
            <div class="summary__preview">
              <img srcSet="{{ $product->image_2x ?? $product->image }}" src="{{ $product->image }}" alt="{{ $product->title }}"/>
              @auth
              @if(auth()->user()->id === $product->user_id)
              <div class="summary__control">
                <a href="{{ route('products.edit', $product) }}" class="summary__button">
                  <svg class="icon icon-edit">
                    <use xlink:href="#icon-edit"></use>
                  </svg>
                </a>
                <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this product?') }}')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="summary__button">
                    <svg class="icon icon-trash">
                      <use xlink:href="#icon-trash"></use>
                    </svg>
                  </button>
                </form>
                <a href="{{ route('products.show', $product) }}" class="summary__button">
                  <svg class="icon icon-arrow-right">
                    <use xlink:href="#icon-arrow-right"></use>
                  </svg>
                </a>
              </div>
              @endif
              @endauth
            </div>
            <div class="summary__line">
              <div class="summary__title">{{ $product->title }}</div>
              <div class="summary__price">
                @if($product->price > 0)
                ${{ number_format($product->price, 2) }}
                @else
                <div class="summary__empty">$0</div>
                @endif
              </div>
            </div>
            <div class="summary__rating {{ $product->rating_count == 0 ? 'summary__rating_empty' : '' }}">
              @if($product->rating_count > 0)
              <svg class="icon icon-star-fill">
                <use xlink:href="#icon-star-fill"></use>
              </svg>
              {{ number_format($product->rating, 1) }}
              <div class="summary__counter">({{ $product->rating_count }})</div>
              @else
              <svg class="icon icon-star-stroke">
                <use xlink:href="#icon-star-stroke"></use>
              </svg>
              {{ __('No ratings') }}
              @endif
            </div>
          </div>
          @empty
          <div class="empty-state">
            <div class="empty-state__icon">
              <svg class="icon icon-shopping-bag">
                <use xlink:href="#icon-shopping-bag"></use>
              </svg>
            </div>
            <div class="empty-state__title">{{ __('No products found') }}</div>
            <div class="empty-state__text">{{ __('Try adjusting your filters or search terms') }}</div>
            @auth
            @if(auth()->user()->id === ($seller->id ?? null))
            <a href="{{ route('products.create') }}" class="button empty-state__button">{{ __('Create your first product') }}</a>
            @endif
            @endauth
          </div>
          @endforelse
        </div>
        
        @if($products->hasPages())
        <div class="pagination">
          <div class="pagination__list">
            @if($products->onFirstPage())
            <div class="pagination__item pagination__item_prev disabled">
              <svg class="icon icon-arrow-left">
                <use xlink:href="#icon-arrow-left"></use>
              </svg>
            </div>
            @else
            <a href="{{ $products->previousPageUrl() }}" class="pagination__item pagination__item_prev">
              <svg class="icon icon-arrow-left">
                <use xlink:href="#icon-arrow-left"></use>
              </svg>
            </a>
            @endif
            
            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            @if($page == $products->currentPage())
            <div class="pagination__item active">{{ $page }}</div>
            @else
            <a href="{{ $url }}" class="pagination__item">{{ $page }}</a>
            @endif
            @endforeach
            
            @if($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="pagination__item pagination__item_next">
              <svg class="icon icon-arrow-right">
                <use xlink:href="#icon-arrow-right"></use>
              </svg>
            </a>
            @else
            <div class="pagination__item pagination__item_next disabled">
              <svg class="icon icon-arrow-right">
                <use xlink:href="#icon-arrow-right"></use>
              </svg>
            </div>
            @endif
          </div>
        </div>
        @endif
      </div>
      
      <div class="shop__tab js-tabs-item">
        <div class="followers">
          @forelse($followers ?? [] as $follower)
          <div class="follower">
            <div class="follower__avatar">
              <img src="{{ $follower->avatar ?? asset('frontend/build/img/content/avatar.jpg') }}" alt="Avatar">
            </div>
            <div class="follower__details">
              <div class="follower__name">{{ $follower->name }}</div>
              <div class="follower__username">@{{ $follower->username }}</div>
            </div>
            @auth
            @if(auth()->user()->id !== $follower->id)
            <form method="POST" action="{{ route('follow.store') }}" class="inline">
              @csrf
              <input type="hidden" name="user_id" value="{{ $follower->id }}">
              <button type="submit" class="button-stroke follower__button">
                {{ auth()->user()->isFollowing($follower->id) ? __('Unfollow') : __('Follow') }}
              </button>
            </form>
            @endif
            @else
            <a href="{{ route('login') }}" class="button-stroke follower__button">{{ __('Follow') }}</a>
            @endauth
          </div>
          @empty
          <div class="empty-state">
            <div class="empty-state__title">{{ __('No followers yet') }}</div>
            <div class="empty-state__text">{{ __('Share your work to get more followers') }}</div>
          </div>
          @endforelse
        </div>
      </div>
      
      <div class="shop__tab js-tabs-item">
        <div class="following">
          @forelse($following ?? [] as $followed)
          <div class="follower">
            <div class="follower__avatar">
              <img src="{{ $followed->avatar ?? asset('frontend/build/img/content/avatar.jpg') }}" alt="Avatar">
            </div>
            <div class="follower__details">
              <div class="follower__name">{{ $followed->name }}</div>
              <div class="follower__username">@{{ $followed->username }}</div>
            </div>
            @auth
            @if(auth()->user()->id !== $followed->id)
            <form method="POST" action="{{ route('follow.store') }}" class="inline">
              @csrf
              <input type="hidden" name="user_id" value="{{ $followed->id }}">
              <button type="submit" class="button-stroke follower__button">
                {{ auth()->user()->isFollowing($followed->id) ? __('Unfollow') : __('Follow') }}
              </button>
            </form>
            @endif
            @else
            <a href="{{ route('login') }}" class="button-stroke follower__button">{{ __('Follow') }}</a>
            @endauth
          </div>
          @empty
          <div class="empty-state">
            <div class="empty-state__title">{{ __('Not following anyone yet') }}</div>
            <div class="empty-state__text">{{ __('Discover and follow amazing creators') }}</div>
            <a href="{{ route('creators.explore') }}" class="button empty-state__button">{{ __('Explore creators') }}</a>
          </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize price range slider
  const priceSlider = document.querySelector('.js-slider');
  if (priceSlider) {
    const minInput = document.querySelector('input[name="price_min"]');
    const maxInput = document.querySelector('input[name="price_max"]');
    
    // Update hidden inputs when slider changes
    priceSlider.addEventListener('change', function(e) {
      const values = e.detail;
      minInput.value = values[0];
      maxInput.value = values[1];
    });
  }
  
  // Initialize tabs
  const tabLinks = document.querySelectorAll('.js-tabs-link');
  const tabItems = document.querySelectorAll('.js-tabs-item');
  
  tabLinks.forEach(function(link, index) {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      
      // Remove active class from all links and items
      tabLinks.forEach(l => l.classList.remove('active'));
      tabItems.forEach(item => item.style.display = 'none');
      
      // Add active class to clicked link
      this.classList.add('active');
      
      // Show corresponding tab item
      if (tabItems[index]) {
        tabItems[index].style.display = 'block';
      }
    });
  });
});
</script>
@endpush
@endsection