@extends('layouts.taskly-modern')

@section('title', __('Create New Product') . ' - ' . config('app.name'))

@section('description', __('Create and publish your new product'))

@section('content')
<div class="page__title h3">{{ __('New product') }}</div>
<div class="create">
  <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="create__row">
    @csrf
    <div class="create__col">
      <div class="create__card card">
        <div class="card__head">
          <div class="title-green card__title">{{ __('Name & description') }}</div>
          <a class="button-stroke button-small card__button" href="{{ route('products.dashboard') }}">
            <svg class="icon icon-arrow-left">
              <use xlink:href="#icon-arrow-left"></use>
            </svg><span>{{ __('Back') }}</span>
          </a>
        </div>
        
        <div class="field">
          <div class="field__label">{{ __('Product title') }}
            <div class="tooltip" title="{{ __('Maximum 100 characters. No HTML or emoji allowed') }}">
              <svg class="icon icon-info">
                <use xlink:href="#icon-info"></use>
              </svg>
            </div>
          </div>
          <div class="field__wrap">
            <input class="field__input @error('title') field__input_error @enderror" type="text" name="title" value="{{ old('title') }}" required maxlength="100">
            @error('title')
            <div class="field__error">{{ $message }}</div>
            @enderror
          </div>
        </div>
        
        <div class="editor">
          <div class="editor__label">{{ __('Description') }}
            <div class="tooltip" title="{{ __('Description') }}">
              <svg class="icon icon-info">
                <use xlink:href="#icon-info"></use>
              </svg>
            </div>
          </div>
          <textarea class="editor__textarea js-editor @error('description') field__input_error @enderror" name="description" required>{{ old('description') }}</textarea>
          @error('description')
          <div class="field__error">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="create__group">
          <div class="field">
            <div class="field__label">{{ __('Key features') }}
              <div class="tooltip" title="{{ __('Maximum 100 characters. No HTML or emoji allowed') }}">
                <svg class="icon icon-info">
                  <use xlink:href="#icon-info"></use>
                </svg>
              </div>
            </div>
            <div class="field__wrap">
              <input class="field__input" type="text" name="features[]" placeholder="{{ __('Value') }}" maxlength="100">
            </div>
          </div>
          <div class="field">
            <div class="field__wrap">
              <input class="field__input" type="text" name="features[]" placeholder="{{ __('Value') }}" maxlength="100">
            </div>
          </div>
          <div class="field">
            <div class="field__wrap">
              <input class="field__input" type="text" name="features[]" placeholder="{{ __('Value') }}" maxlength="100">
            </div>
          </div>
          <div class="field">
            <div class="field__wrap">
              <input class="field__input" type="text" name="features[]" placeholder="{{ __('Value') }}" maxlength="100">
            </div>
          </div>
        </div>
      </div>
      
      <div class="create__card card">
        <div class="card__head">
          <div class="title-blue card__title">{{ __('Images & CTA') }}</div>
        </div>
        
        <div class="file">
          <div class="file__label">{{ __('Cover images') }}
            <div class="tooltip" title="{{ __('Maximum 100 characters. No HTML or emoji allowed') }}" data-position="right">
              <svg class="icon icon-info">
                <use xlink:href="#icon-info"></use>
              </svg>
            </div>
          </div>
          <div class="file__wrap">
            <input class="file__input @error('image') field__input_error @enderror" type="file" name="image" accept="image/*"/>
            <div class="file__box">
              <svg class="icon icon-upload">
                <use xlink:href="#icon-upload"></use>
              </svg>{{ __('Click or drop image') }}
            </div>
          </div>
          @error('image')
          <div class="field__error">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="field">
          <div class="field__label">{{ __('Category') }}
            <div class="tooltip" title="{{ __('Select product category') }}">
              <svg class="icon icon-info">
                <use xlink:href="#icon-info"></use>
              </svg>
            </div>
          </div>
          <div class="field__wrap">
            <select class="select @error('category') field__input_error @enderror" name="category" required>
              <option value="">{{ __('Select category') }}</option>
              <option value="ui-kit" {{ old('category') == 'ui-kit' ? 'selected' : '' }}>{{ __('UI Kit') }}</option>
              <option value="illustration" {{ old('category') == 'illustration' ? 'selected' : '' }}>{{ __('Illustration') }}</option>
              <option value="wireframe" {{ old('category') == 'wireframe' ? 'selected' : '' }}>{{ __('Wireframe kit') }}</option>
              <option value="icons" {{ old('category') == 'icons' ? 'selected' : '' }}>{{ __('Icons') }}</option>
              <option value="template" {{ old('category') == 'template' ? 'selected' : '' }}>{{ __('Template') }}</option>
              <option value="plugin" {{ old('category') == 'plugin' ? 'selected' : '' }}>{{ __('Plugin') }}</option>
            </select>
            @error('category')
            <div class="field__error">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
      
      <div class="create__card card">
        <div class="card__head">
          <div class="title-green card__title">{{ __('Price') }}</div>
        </div>
        
        <div class="field field_currency">
          <div class="field__label">{{ __('Amount') }}
            <div class="tooltip" title="{{ __('Small description') }}">
              <svg class="icon icon-info">
                <use xlink:href="#icon-info"></use>
              </svg>
            </div>
          </div>
          <div class="field__wrap">
            <input class="field__input @error('price') field__input_error @enderror" type="number" name="price" value="{{ old('price', 0) }}" step="0.01" min="0" required>
            <div class="field__currency">$</div>
          </div>
          @error('price')
          <div class="field__error">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="create__line">
          <div class="create__info">{{ __('Allow customers to pay what they want') }}
            <div class="tooltip" title="{{ __('Let customers choose their own price') }}">
              <svg class="icon icon-info">
                <use xlink:href="#icon-info"></use>
              </svg>
            </div>
          </div>
          <label class="switch">
            <input class="switch__input" type="checkbox" name="pay_what_you_want" {{ old('pay_what_you_want') ? 'checked' : '' }}/>
            <span class="switch__inner">
              <span class="switch__box"></span>
            </span>
          </label>
        </div>
        
        <div class="create__fieldset">
          <div class="field field_currency">
            <div class="field__label">{{ __('Minimum amount') }}</div>
            <div class="field__wrap">
              <input class="field__input" type="number" name="minimum_amount" value="{{ old('minimum_amount', 0) }}" step="0.01" min="0">
              <div class="field__currency">$</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="create__col">
      <div class="create__card card">
        <div class="card__head">
          <div class="title-purple card__title">{{ __('Settings') }}</div>
        </div>
        
        <div class="field">
          <div class="field__label">{{ __('Status') }}</div>
          <div class="field__wrap">
            <select class="select" name="status">
              <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
              <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>{{ __('Published') }}</option>
              <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>{{ __('Scheduled') }}</option>
            </select>
          </div>
        </div>
        
        <div class="field">
          <div class="field__label">{{ __('Release date') }}</div>
          <div class="field__wrap">
            <input class="field__input" type="datetime-local" name="release_date" value="{{ old('release_date') }}">
          </div>
        </div>
        
        <div class="field">
          <div class="field__label">{{ __('Tags') }}</div>
          <div class="field__wrap">
            <input class="field__input" type="text" name="tags" value="{{ old('tags') }}" placeholder="{{ __('Enter tags separated by commas') }}">
          </div>
        </div>
        
        <div class="field">
          <div class="field__label">{{ __('License type') }}</div>
          <div class="field__wrap">
            <select class="select" name="license_type">
              <option value="personal" {{ old('license_type') == 'personal' ? 'selected' : '' }}>{{ __('Personal') }}</option>
              <option value="commercial" {{ old('license_type') == 'commercial' ? 'selected' : '' }}>{{ __('Commercial') }}</option>
              <option value="extended" {{ old('license_type') == 'extended' ? 'selected' : '' }}>{{ __('Extended') }}</option>
            </select>
          </div>
        </div>
        
        <div class="create__line">
          <div class="create__info">{{ __('Feature this product') }}</div>
          <label class="switch">
            <input class="switch__input" type="checkbox" name="is_featured" {{ old('is_featured') ? 'checked' : '' }}/>
            <span class="switch__inner">
              <span class="switch__box"></span>
            </span>
          </label>
        </div>
      </div>
      
      <div class="create__card card">
        <div class="card__head">
          <div class="title-red card__title">{{ __('Files') }}</div>
        </div>
        
        <div class="file">
          <div class="file__label">{{ __('Product file') }}</div>
          <div class="file__wrap">
            <input class="file__input @error('product_file') field__input_error @enderror" type="file" name="product_file" required/>
            <div class="file__box">
              <svg class="icon icon-upload">
                <use xlink:href="#icon-upload"></use>
              </svg>{{ __('Click or drop file') }}
            </div>
          </div>
          @error('product_file')
          <div class="field__error">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="field">
          <div class="field__label">{{ __('Demo URL') }}</div>
          <div class="field__wrap">
            <input class="field__input" type="url" name="demo_url" value="{{ old('demo_url') }}" placeholder="{{ __('https://example.com') }}">
          </div>
        </div>
        
        <div class="field">
          <div class="field__label">{{ __('Documentation URL') }}</div>
          <div class="field__wrap">
            <input class="field__input" type="url" name="documentation_url" value="{{ old('documentation_url') }}" placeholder="{{ __('https://example.com/docs') }}">
          </div>
        </div>
        
        <div class="field">
          <div class="field__label">{{ __('Support email') }}</div>
          <div class="field__wrap">
            <input class="field__input" type="email" name="support_email" value="{{ old('support_email') }}" placeholder="{{ __('support@example.com') }}">
          </div>
        </div>
      </div>
      
      <div class="create__card card">
        <div class="card__head">
          <div class="title-blue card__title">{{ __('Preview') }}</div>
        </div>
        
        <div class="create__preview">
          <div class="summary">
            <div class="summary__preview">
              <img src="{{ asset('frontend/build/img/content/product-pic-1.jpg') }}" alt="{{ __('Product preview') }}"/>
            </div>
            <div class="summary__line">
              <div class="summary__title" id="preview-title">{{ __('Product title will appear here') }}</div>
              <div class="summary__price" id="preview-price">$0</div>
            </div>
            <div class="summary__rating">
              <svg class="icon icon-star-stroke">
                <use xlink:href="#icon-star-stroke"></use>
              </svg>{{ __('No ratings') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Preview functionality
  const titleInput = document.querySelector('input[name="title"]');
  const priceInput = document.querySelector('input[name="price"]');
  const previewTitle = document.getElementById('preview-title');
  const previewPrice = document.getElementById('preview-price');
  
  if (titleInput && previewTitle) {
    titleInput.addEventListener('input', function() {
      previewTitle.textContent = this.value || '{{ __("Product title will appear here") }}';
    });
  }
  
  if (priceInput && previewPrice) {
    priceInput.addEventListener('input', function() {
      const price = parseFloat(this.value) || 0;
      previewPrice.textContent = price > 0 ? '$' + price.toFixed(2) : '$0';
    });
  }
  
  // File upload preview
  const imageInput = document.querySelector('input[name="image"]');
  if (imageInput) {
    imageInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.querySelector('.summary__preview img');
          if (preview) {
            preview.src = e.target.result;
          }
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  }
});
</script>
@endpush
@endsection