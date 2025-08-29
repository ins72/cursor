# Sales Page Blade Conversion

## Overview

This project demonstrates the conversion of a sales page to use the modern frontend styling from `/frontend/build/` while preserving all backend functionality. The sales page has been restructured to match the HTML classes and layout exactly from the reference files.

## Files Created

### 1. Layout File
- **`resources/views/layouts/taskly-modern.blade.php`** - Main layout that extends the frontend/build styling with Laravel Blade functionality

### 2. Sales Page
- **`resources/views/sales/index.blade.php`** - Sales page that extends the layout and matches shop.html structure

### 3. Backend Components
- **`app/Http/Controllers/SalesController.php`** - Controller handling all sales page functionality
- **`routes/web.php`** - Route definitions for the sales page and related functionality

## Key Features Preserved

### Backend Functionality
- ✅ **PHP Logic** - All business logic preserved in controller
- ✅ **Forms** - Search, filtering, and purchase forms with CSRF protection
- ✅ **i18n** - Internationalization using Laravel's `__()` helper
- ✅ **Auth** - Authentication checks and user-specific functionality
- ✅ **Variables** - Dynamic data passed from controller to view

### Frontend Styling
- ✅ **Exact HTML Structure** - Matches frontend/build/shop.html classes and layout
- ✅ **CSS Compatibility** - Uses existing CSS classes for automatic styling
- ✅ **Responsive Design** - Maintains responsive behavior from original design
- ✅ **Interactive Elements** - JavaScript functionality for tabs, filters, and sliders

## Structure Breakdown

### Layout (`taskly-modern.blade.php`)
```blade
@extends('layouts.taskly-modern')
```
- Header with search, notifications, and user dropdown
- Sidebar with navigation menu
- Help panel and theme toggle
- SVG icons for all UI elements
- CSRF token and asset loading

### Sales Page (`sales/index.blade.php`)
```blade
@section('content')
<div class="shop">
  <!-- Seller profile section -->
  <!-- Product filtering and search -->
  <!-- Product grid with pagination -->
  <!-- Followers/Following tabs -->
</div>
@endsection
```

## Backend Integration

### Controller Methods
1. **`index()`** - Main sales page with filtering and pagination
2. **`follow()`** - Follow/unfollow functionality
3. **`purchase()`** - Product purchase with payment processing
4. **`rate()`** - Product rating and review system
5. **`wishlist()`** - Add/remove from wishlist

### Data Flow
```
Request → Controller → Database Query → View → Rendered HTML
```

### Key Variables Passed to View
- `$products` - Paginated product collection
- `$seller` - Seller information (if viewing specific seller)
- `$followers` - Seller's followers list
- `$following` - Users the seller follows

## Usage Examples

### Basic Sales Page
```php
// Route: /sales
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
```

### Seller-Specific Page
```php
// Route: /sales/{seller}
Route::get('/sales/{seller}', [SalesController::class, 'index'])->name('sales.seller');
```

### Follow/Unfollow
```php
// POST to /follow with seller_id or user_id
Route::post('/follow', [SalesController::class, 'follow'])->name('follow.store');
```

## Filtering and Search

The sales page supports comprehensive filtering:

### Search
- Product title, description, and tags
- Real-time search with form submission

### Categories
- UI Kit, Illustration, Wireframe, Icons
- Multiple selection with checkboxes

### Price Range
- Slider component with min/max values
- Hidden inputs for form submission

### Rating Filter
- Dropdown for minimum rating (1-5 stars)
- Only shows products meeting rating criteria

### Sorting
- Most recent, Most new, Most popular
- Featured, Last, New options

## Authentication Integration

### Guest Users
- Can view products and seller profiles
- Redirected to login for interactive features
- Limited functionality (no purchase, follow, etc.)

### Authenticated Users
- Full access to all features
- Personal wishlist and purchase history
- Ability to rate purchased products
- Follow/unfollow creators

## Responsive Design

The sales page maintains the responsive design from the original frontend/build:

- **Mobile** - Stacked layout with collapsible filters
- **Tablet** - Side-by-side product grid
- **Desktop** - Full grid layout with sidebar filters

## JavaScript Functionality

### Tab Switching
```javascript
// Products, Followers, Following tabs
const tabLinks = document.querySelectorAll('.js-tabs-link');
const tabItems = document.querySelectorAll('.js-tabs-item');
```

### Price Range Slider
```javascript
// Range slider for price filtering
const priceSlider = document.querySelector('.js-slider');
// Updates hidden inputs for form submission
```

### Filter Toggle
```javascript
// Show/hide filter panel
const filterButton = document.querySelector('.filters__head');
const filterBody = document.querySelector('.filters__body');
```

## CSS Classes Used

The sales page uses the exact CSS classes from frontend/build:

- **Layout**: `shop`, `shop__tabs`, `shop__profile`, `shop__control`
- **Products**: `summary`, `summary__preview`, `summary__title`, `summary__price`
- **Filters**: `filters`, `filters__body`, `filters__group`, `filters__item`
- **Navigation**: `shop__nav`, `shop__link`, `js-tabs-link`, `js-tabs-item`
- **Pagination**: `pagination`, `pagination__list`, `pagination__item`

## Database Requirements

### Required Models
- `User` - Users and sellers
- `Product` - Products with ratings and purchases
- `Rating` - Product ratings and reviews
- `Purchase` - User purchase history
- `Follow` - User following relationships
- `Wishlist` - User wishlist items

### Key Relationships
```php
User::hasMany(Product::class);
Product::belongsTo(User::class);
Product::hasMany(Rating::class);
Product::hasMany(Purchase::class);
User::hasMany(Rating::class);
User::hasMany(Purchase::class);
```

## Installation

1. **Copy Files** - Place the blade files in your Laravel project
2. **Update Routes** - Add the routes to your `routes/web.php`
3. **Create Controller** - Add the `SalesController` to your controllers
4. **Set Up Models** - Ensure your models have the required relationships
5. **Configure Assets** - Point to your frontend/build directory

## Customization

### Styling
- Modify CSS classes in the blade files to match your design
- Update asset paths to point to your frontend build directory
- Customize colors and spacing in your CSS

### Functionality
- Add new filter options in the controller
- Extend the product model with additional fields
- Implement payment gateway integration
- Add more social features

### Localization
- Update translation keys in the blade files
- Add language files for your supported locales
- Customize date and number formatting

## Conclusion

The sales page has been successfully converted to use the modern frontend styling while preserving all backend functionality. The structure matches the original HTML exactly, ensuring CSS compatibility and maintaining the user experience from the design system.