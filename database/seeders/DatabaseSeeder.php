<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Purchase;
use App\Models\Follow;
use App\Models\Wishlist;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'bio' => 'Platform administrator and creator',
            'social_links' => [
                'twitter' => 'https://twitter.com/admin',
                'instagram' => 'https://instagram.com/admin',
                'pinterest' => 'https://pinterest.com/admin',
            ],
            'is_verified' => true,
            'is_featured' => true,
            'subscription_status' => 'pro',
        ]);

        // Create sample users
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $users[] = User::create([
                'name' => "Creator {$i}",
                'email' => "creator{$i}@example.com",
                'username' => "creator{$i}",
                'password' => Hash::make('password'),
                'bio' => "Professional designer and creator with {$i} years of experience",
                'social_links' => [
                    'twitter' => "https://twitter.com/creator{$i}",
                    'instagram' => "https://instagram.com/creator{$i}",
                    'pinterest' => "https://pinterest.com/creator{$i}",
                ],
                'is_verified' => $i <= 5,
                'is_featured' => $i <= 3,
                'subscription_status' => $i <= 3 ? 'pro' : 'free',
            ]);
        }

        // Create sample products
        $categories = ['ui-kit', 'illustration', 'wireframe', 'icons', 'template', 'plugin'];
        $statuses = ['published', 'draft', 'scheduled'];
        
        foreach ($users as $user) {
            for ($i = 1; $i <= rand(2, 5); $i++) {
                $product = Product::create([
                    'user_id' => $user->id,
                    'title' => "Amazing Product {$i} by {$user->name}",
                    'description' => "This is a fantastic product that will help you create amazing designs. It includes everything you need to get started and more.",
                    'price' => rand(0, 200),
                    'category' => $categories[array_rand($categories)],
                    'tags' => ['design', 'creative', 'professional', 'modern'],
                    'status' => $statuses[array_rand($statuses)],
                    'is_featured' => rand(0, 1),
                    'download_count' => rand(0, 1000),
                    'view_count' => rand(0, 5000),
                    'purchase_count' => rand(0, 500),
                    'rating' => rand(35, 50) / 10,
                    'rating_count' => rand(0, 100),
                    'license_type' => ['personal', 'commercial', 'extended'][array_rand(['personal', 'commercial', 'extended'])],
                    'version' => '1.0.0',
                    'changelog' => 'Initial release with all features',
                ]);

                // Create ratings for published products
                if ($product->status === 'published') {
                    for ($r = 1; $r <= rand(0, 10); $r++) {
                        $ratingUser = $users[array_rand($users)];
                        if ($ratingUser->id !== $user->id) {
                            Rating::create([
                                'user_id' => $ratingUser->id,
                                'product_id' => $product->id,
                                'rating' => rand(3, 5),
                                'review' => "Great product! Really helped me with my project.",
                                'is_verified' => rand(0, 1),
                            ]);
                        }
                    }

                    // Create purchases
                    for ($p = 1; $p <= rand(0, 20); $p++) {
                        $purchaseUser = $users[array_rand($users)];
                        if ($purchaseUser->id !== $user->id) {
                            Purchase::create([
                                'user_id' => $purchaseUser->id,
                                'product_id' => $product->id,
                                'amount' => $product->price,
                                'status' => 'completed',
                                'payment_method' => 'stripe',
                                'purchased_at' => now()->subDays(rand(1, 30)),
                                'download_count' => rand(0, 5),
                            ]);
                        }
                    }

                    // Create comments
                    for ($c = 1; $c <= rand(0, 5); $c++) {
                        $commentUser = $users[array_rand($users)];
                        if ($commentUser->id !== $user->id) {
                            Comment::create([
                                'user_id' => $commentUser->id,
                                'product_id' => $product->id,
                                'content' => "This is a great product! I love using it in my projects.",
                                'is_approved' => true,
                                'is_featured' => rand(0, 1),
                            ]);
                        }
                    }
                }
            }
        }

        // Create follow relationships
        foreach ($users as $user) {
            $followCount = rand(0, 5);
            $followedUsers = array_rand($users, min($followCount, count($users)));
            if (!is_array($followedUsers)) {
                $followedUsers = [$followedUsers];
            }
            
            foreach ($followedUsers as $followedIndex) {
                if ($users[$followedIndex]->id !== $user->id) {
                    Follow::create([
                        'follower_id' => $user->id,
                        'following_id' => $users[$followedIndex]->id,
                        'followed_at' => now()->subDays(rand(1, 60)),
                    ]);
                }
            }
        }

        // Create wishlist items
        foreach ($users as $user) {
            $wishlistCount = rand(0, 8);
            $publishedProducts = Product::where('status', 'published')->get();
            
            for ($w = 1; $w <= min($wishlistCount, $publishedProducts->count()); $w++) {
                $product = $publishedProducts->random();
                if ($product->user_id !== $user->id) {
                    Wishlist::create([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'added_at' => now()->subDays(rand(1, 30)),
                    ]);
                }
            }
        }

        // Create notifications
        foreach ($users as $user) {
            $notificationCount = rand(0, 10);
            for ($n = 1; $n <= $notificationCount; $n++) {
                $types = ['purchase', 'rating', 'comment', 'follow', 'like', 'download'];
                $type = $types[array_rand($types)];
                
                $titles = [
                    'purchase' => 'New purchase',
                    'rating' => 'New rating received',
                    'comment' => 'New comment on your product',
                    'follow' => 'New follower',
                    'like' => 'Product liked',
                    'download' => 'Product downloaded',
                ];
                
                $messages = [
                    'purchase' => 'Someone purchased your product',
                    'rating' => 'You received a new rating',
                    'comment' => 'Someone commented on your product',
                    'follow' => 'You have a new follower',
                    'like' => 'Someone liked your product',
                    'download' => 'Your product was downloaded',
                ];
                
                Notification::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'title' => $titles[$type],
                    'message' => $messages[$type],
                    'is_read' => rand(0, 1),
                    'read_at' => rand(0, 1) ? now()->subDays(rand(1, 7)) : null,
                ]);
            }
        }

        // Create messages
        foreach ($users as $user) {
            $messageCount = rand(0, 5);
            for ($m = 1; $m <= $messageCount; $m++) {
                $recipient = $users[array_rand($users)];
                if ($recipient->id !== $user->id) {
                    Message::create([
                        'sender_id' => $user->id,
                        'recipient_id' => $recipient->id,
                        'subject' => 'Hello from ' . $user->name,
                        'content' => 'Hi! I really like your work. Would you be interested in collaborating on a project?',
                        'is_read' => rand(0, 1),
                        'read_at' => rand(0, 1) ? now()->subDays(rand(1, 7)) : null,
                    ]);
                }
            }
        }

        // Update product statistics
        Product::chunk(100, function ($products) {
            foreach ($products as $product) {
                $product->updateRatingStats();
            }
        });

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin user: admin@example.com / password');
        $this->command->info('Sample users: creator1@example.com through creator10@example.com / password');
    }
}