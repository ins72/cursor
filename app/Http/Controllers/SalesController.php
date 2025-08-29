<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Display the sales page with products, filters, and seller information.
     *
     * @param Request $request
     * @param int|null $sellerId
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $sellerId = null)
    {
        // Get seller information
        $seller = null;
        if ($sellerId) {
            $seller = User::findOrFail($sellerId);
        }

        // Build product query
        $query = Product::with(['user', 'ratings'])
            ->where('status', 'published');

        // Filter by seller if specified
        if ($seller) {
            $query->where('user_id', $seller->id);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->filled('category') && !in_array('all', $request->get('category', []))) {
            $categories = $request->get('category');
            $query->whereIn('category', $categories);
        }

        // Apply price filter
        if ($request->filled('price_min') || $request->filled('price_max')) {
            $minPrice = $request->get('price_min', 0);
            $maxPrice = $request->get('price_max', 1000);
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        // Apply rating filter
        if ($request->filled('rating')) {
            $minRating = $request->get('rating');
            $query->whereHas('ratings', function ($q) use ($minRating) {
                $q->havingRaw('AVG(rating) >= ?', [$minRating]);
            });
        }

        // Apply sorting
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'new':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->withCount('purchases')->orderBy('purchases_count', 'desc');
                break;
            case 'recent':
            default:
                $query->orderBy('updated_at', 'desc');
                break;
        }

        // Apply sort by filter
        $sortBy = $request->get('sort_by', 'featured');
        switch ($sortBy) {
            case 'last':
                $query->orderBy('updated_at', 'desc');
                break;
            case 'new':
                $query->orderBy('created_at', 'desc');
                break;
            case 'featured':
            default:
                $query->where('is_featured', true)->orderBy('featured_at', 'desc');
                break;
        }

        // Get paginated products
        $products = $query->paginate(12)->withQueryString();

        // Get followers and following if seller is specified
        $followers = collect();
        $following = collect();
        
        if ($seller) {
            $followers = $seller->followers()->with('follower')->get()->pluck('follower');
            $following = $seller->following()->with('following')->get()->pluck('following');
        }

        return view('sales.index', compact(
            'products',
            'seller',
            'followers',
            'following'
        ));
    }

    /**
     * Handle follow/unfollow functionality.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function follow(Request $request)
    {
        $request->validate([
            'seller_id' => 'required_without:user_id|exists:users,id',
            'user_id' => 'required_without:seller_id|exists:users,id',
        ]);

        $followerId = Auth::id();
        $followingId = $request->get('seller_id') ?? $request->get('user_id');

        // Prevent self-following
        if ($followerId == $followingId) {
            return back()->with('error', __('You cannot follow yourself.'));
        }

        $follower = User::find($followerId);
        $following = User::find($followingId);

        if ($follower->isFollowing($followingId)) {
            // Unfollow
            $follower->following()->where('following_id', $followingId)->delete();
            $message = __('Successfully unfollowed :name.', ['name' => $following->name]);
        } else {
            // Follow
            $follower->following()->create(['following_id' => $followingId]);
            $message = __('Successfully followed :name.', ['name' => $following->name]);
        }

        return back()->with('success', $message);
    }

    /**
     * Purchase a product.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function purchase(Request $request, Product $product)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', __('Please log in to purchase products.'));
        }

        // Check if product is available for purchase
        if ($product->status !== 'published') {
            return back()->with('error', __('This product is not available for purchase.'));
        }

        // Check if user already purchased this product
        if (Auth::user()->hasPurchased($product->id)) {
            return back()->with('info', __('You have already purchased this product.'));
        }

        // Process payment (this would integrate with your payment gateway)
        try {
            // Payment processing logic here
            // $payment = PaymentGateway::process($request->payment_method, $product->price);
            
            // Create purchase record
            Auth::user()->purchases()->create([
                'product_id' => $product->id,
                'amount' => $product->price,
                'status' => 'completed',
                // 'payment_id' => $payment->id,
            ]);

            // Increment product purchase count
            $product->increment('purchase_count');

            return back()->with('success', __('Product purchased successfully!'));
        } catch (\Exception $e) {
            return back()->with('error', __('Payment failed. Please try again.'));
        }
    }

    /**
     * Rate a product.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rate(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:1000',
        ]);

        // Check if user has purchased the product
        if (!Auth::user()->hasPurchased($product->id)) {
            return back()->with('error', __('You can only rate products you have purchased.'));
        }

        // Check if user already rated this product
        $existingRating = $product->ratings()->where('user_id', Auth::id())->first();
        
        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);
            $message = __('Rating updated successfully!');
        } else {
            // Create new rating
            $product->ratings()->create([
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'review' => $request->review,
            ]);
            $message = __('Rating submitted successfully!');
        }

        return back()->with('success', $message);
    }

    /**
     * Add product to wishlist.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function wishlist(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', __('Please log in to add items to wishlist.'));
        }

        $user = Auth::user();
        
        if ($user->wishlist()->where('product_id', $product->id)->exists()) {
            // Remove from wishlist
            $user->wishlist()->where('product_id', $product->id)->delete();
            $message = __('Product removed from wishlist.');
        } else {
            // Add to wishlist
            $user->wishlist()->create(['product_id' => $product->id]);
            $message = __('Product added to wishlist.');
        }

        return back()->with('success', $message);
    }
}