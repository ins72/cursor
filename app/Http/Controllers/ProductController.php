<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['user', 'ratings'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'category' => 'required|in:ui-kit,illustration,wireframe,icons,template,plugin',
            'status' => 'required|in:draft,published,scheduled',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_file' => 'required|file|max:102400', // 100MB max
            'tags' => 'nullable|string|max:500',
            'demo_url' => 'nullable|url|max:255',
            'documentation_url' => 'nullable|url|max:255',
            'support_email' => 'nullable|email|max:255',
            'license_type' => 'required|in:personal,commercial,extended',
            'release_date' => 'nullable|date',
            'minimum_amount' => 'nullable|numeric|min:0|max:999999.99',
            'is_featured' => 'boolean',
            'pay_what_you_want' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id();
        $data['is_featured'] = $request->has('is_featured');
        $data['pay_what_you_want'] = $request->has('pay_what_you_want');

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products/images', 'public');
            $data['image'] = Storage::url($imagePath);
        }

        // Handle product file upload
        if ($request->hasFile('product_file')) {
            $filePath = $request->file('product_file')->store('products/files', 'public');
            $data['file_path'] = $filePath;
            $data['file_size'] = $request->file('product_file')->getSize();
            $data['file_type'] = $request->file('product_file')->getClientOriginalExtension();
        }

        // Process tags
        if (!empty($data['tags'])) {
            $tags = array_map('trim', explode(',', $data['tags']));
            $data['tags'] = array_filter($tags);
        }

        // Set release date if scheduled
        if ($data['status'] === 'scheduled' && !empty($data['release_date'])) {
            $data['release_date'] = $data['release_date'];
        }

        $product = Product::create($data);

        return redirect()->route('products.show', $product)
            ->with('success', __('Product created successfully!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Increment view count
        $product->incrementViewCount();

        // Load relationships
        $product->load(['user', 'ratings.user', 'comments.user']);

        // Get related products
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('status', 'published')
            ->limit(6)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Check if user owns this product
        if ($product->user_id !== Auth::id()) {
            abort(403, __('Unauthorized action.'));
        }

        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Check if user owns this product
        if ($product->user_id !== Auth::id()) {
            abort(403, __('Unauthorized action.'));
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'category' => 'required|in:ui-kit,illustration,wireframe,icons,template,plugin',
            'status' => 'required|in:draft,published,scheduled,archived',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_file' => 'nullable|file|max:102400', // 100MB max
            'tags' => 'nullable|string|max:500',
            'demo_url' => 'nullable|url|max:255',
            'documentation_url' => 'nullable|url|max:255',
            'support_email' => 'nullable|email|max:255',
            'license_type' => 'required|in:personal,commercial,extended',
            'release_date' => 'nullable|date',
            'minimum_amount' => 'nullable|numeric|min:0|max:999999.99',
            'is_featured' => 'boolean',
            'pay_what_you_want' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_featured'] = $request->has('is_featured');
        $data['pay_what_you_want'] = $request->has('pay_what_you_want');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
            }
            
            $imagePath = $request->file('image')->store('products/images', 'public');
            $data['image'] = Storage::url($imagePath);
        }

        // Handle product file upload
        if ($request->hasFile('product_file')) {
            // Delete old file
            if ($product->file_path) {
                Storage::disk('public')->delete($product->file_path);
            }
            
            $filePath = $request->file('product_file')->store('products/files', 'public');
            $data['file_path'] = $filePath;
            $data['file_size'] = $request->file('product_file')->getSize();
            $data['file_type'] = $request->file('product_file')->getClientOriginalExtension();
        }

        // Process tags
        if (!empty($data['tags'])) {
            $tags = array_map('trim', explode(',', $data['tags']));
            $data['tags'] = array_filter($tags);
        }

        // Set release date if scheduled
        if ($data['status'] === 'scheduled' && !empty($data['release_date'])) {
            $data['release_date'] = $data['release_date'];
        }

        $product->update($data);

        return redirect()->route('products.show', $product)
            ->with('success', __('Product updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if user owns this product
        if ($product->user_id !== Auth::id()) {
            abort(403, __('Unauthorized action.'));
        }

        // Delete associated files
        if ($product->image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
        }
        
        if ($product->file_path) {
            Storage::disk('public')->delete($product->file_path);
        }

        $product->delete();

        return redirect()->route('products.dashboard')
            ->with('success', __('Product deleted successfully!'));
    }

    /**
     * Download the product file
     */
    public function download(Product $product)
    {
        // Check if user has purchased the product or owns it
        if (!Auth::user()->hasPurchased($product->id) && $product->user_id !== Auth::id()) {
            abort(403, __('You must purchase this product to download it.'));
        }

        // Check if file exists
        if (!$product->file_path || !Storage::disk('public')->exists($product->file_path)) {
            abort(404, __('Product file not found.'));
        }

        // Increment download count
        $product->incrementDownloadCount();

        // If user has purchased, increment their download count
        if (Auth::user()->hasPurchased($product->id)) {
            $purchase = Auth::user()->purchases()->where('product_id', $product->id)->first();
            $purchase->incrementDownloadCount();
        }

        return Storage::disk('public')->download($product->file_path, $product->title . '.' . $product->file_type);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Product $product)
    {
        // Check if user owns this product
        if ($product->user_id !== Auth::id()) {
            abort(403, __('Unauthorized action.'));
        }

        $product->update([
            'is_featured' => !$product->is_featured,
            'featured_at' => $product->is_featured ? now() : null,
        ]);

        return back()->with('success', 
            $product->is_featured ? __('Product featured successfully!') : __('Product unfeatured successfully!')
        );
    }

    /**
     * Duplicate a product
     */
    public function duplicate(Product $product)
    {
        // Check if user owns this product
        if ($product->user_id !== Auth::id()) {
            abort(403, __('Unauthorized action.'));
        }

        $newProduct = $product->replicate();
        $newProduct->title = $product->title . ' (Copy)';
        $newProduct->status = 'draft';
        $newProduct->is_featured = false;
        $newProduct->featured_at = null;
        $newProduct->download_count = 0;
        $newProduct->view_count = 0;
        $newProduct->purchase_count = 0;
        $newProduct->rating = 0;
        $newProduct->rating_count = 0;
        $newProduct->save();

        return redirect()->route('products.edit', $newProduct)
            ->with('success', __('Product duplicated successfully!'));
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:delete,publish,unpublish,feature,unfeature',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $products = Product::whereIn('id', $request->products)
            ->where('user_id', Auth::id())
            ->get();

        $count = 0;

        foreach ($products as $product) {
            switch ($request->action) {
                case 'delete':
                    $product->delete();
                    $count++;
                    break;
                case 'publish':
                    $product->update(['status' => 'published']);
                    $count++;
                    break;
                case 'unpublish':
                    $product->update(['status' => 'draft']);
                    $count++;
                    break;
                case 'feature':
                    $product->update(['is_featured' => true, 'featured_at' => now()]);
                    $count++;
                    break;
                case 'unfeature':
                    $product->update(['is_featured' => false, 'featured_at' => null]);
                    $count++;
                    break;
            }
        }

        $action = ucfirst($request->action);
        return back()->with('success', __(":count products {$request->action} successfully!", ['count' => $count]));
    }
}