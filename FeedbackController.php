<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Seller;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class FeedbackController extends Controller
{
    /**
     * Display a listing of customer feedback for the seller.
     */
    public function index()
    {
        // Get the authenticated seller
        $user = Auth::user();
        $seller = Seller::where('user_id', $user->user_id)->first();
        
        if (!$seller) {
            return redirect()->route('seller.dashboard')->with('error', 'Seller profile not found');
        }
        
        // Get all orders with ratings for this seller
        $ordersWithFeedback = Order::where('seller_id', $seller->seller_id)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('orderitems')
                    ->join('item_ratings', 'orderitems.order_item_id', '=', 'item_ratings.order_item_id')
                    ->whereRaw('orderitems.order_id = orders.order_id');
            })
            ->with(['student', 'orderItems' => function($query) {
                $query->whereNotNull('rating');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // For each order item, get the actual rating details
        foreach ($ordersWithFeedback as $order) {
            foreach ($order->orderItems as $item) {
                $item->ratingDetails = DB::table('item_ratings')
                    ->where('order_item_id', $item->order_item_id)
                    ->first();
            }
        }
        
        // Calculate statistics
        $stats = [
            'total_feedback' => DB::table('item_ratings')
                ->join('orderitems', 'item_ratings.order_item_id', '=', 'orderitems.order_item_id')
                ->join('orders', 'orderitems.order_id', '=', 'orders.order_id')
                ->where('orders.seller_id', $seller->seller_id)
                ->count(),
            'average_rating' => DB::table('item_ratings')
                ->join('orderitems', 'item_ratings.order_item_id', '=', 'orderitems.order_item_id')
                ->join('orders', 'orderitems.order_id', '=', 'orders.order_id')
                ->where('orders.seller_id', $seller->seller_id)
                ->avg('item_ratings.rating') ?? 0,
            'rating_distribution' => [
                1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0
            ]
        ];
        
        // Get the distribution of ratings (1-5)
        $ratingDistribution = DB::table('item_ratings')
            ->join('orderitems', 'item_ratings.order_item_id', '=', 'orderitems.order_item_id')
            ->join('orders', 'orderitems.order_id', '=', 'orders.order_id')
            ->where('orders.seller_id', $seller->seller_id)
            ->select('item_ratings.rating', DB::raw('count(*) as total'))
            ->groupBy('item_ratings.rating')
            ->get();
            
        foreach ($ratingDistribution as $rating) {
            $stats['rating_distribution'][$rating->rating] = $rating->total;
        }
        
        return view('seller.feedback.index', compact('ordersWithFeedback', 'stats'));
    }

    /**
     * Display the details of a specific feedback.
     */
    public function show($id)
    {
        // Get the authenticated seller
        $user = Auth::user();
        $seller = Seller::where('user_id', $user->user_id)->first();
        
        if (!$seller) {
            return redirect()->route('seller.dashboard')->with('error', 'Seller profile not found');
        }
        
        // Get the order with feedback
        $order = Order::where('order_id', $id)
            ->where('seller_id', $seller->seller_id)
            ->with(['student', 'orderItems' => function($query) {
                $query->whereNotNull('rating');
            }])
            ->firstOrFail();
            
        // For each order item, get the actual rating details
        foreach ($order->orderItems as $item) {
            $item->ratingDetails = DB::table('item_ratings')
                ->where('order_item_id', $item->order_item_id)
                ->first();
                
            // Get the menu item details
            $item->menuItem = DB::table('menuitems')
                ->where('item_id', $item->item_id)
                ->first();
        }
        
        return view('seller.feedback.show', compact('order'));
    }
    
    /**
     * Get feedback statistics for the dashboard
     */
    public function getStats()
    {
        // Get the authenticated seller
        $user = Auth::user();
        $seller = Seller::where('user_id', $user->user_id)->first();
        
        if (!$seller) {
            return response()->json(['error' => 'Seller not found'], 404);
        }
        
        // Calculate statistics
        $stats = [
            'total_feedback' => DB::table('item_ratings')
                ->join('orderitems', 'item_ratings.order_item_id', '=', 'orderitems.order_item_id')
                ->join('orders', 'orderitems.order_id', '=', 'orders.order_id')
                ->where('orders.seller_id', $seller->seller_id)
                ->count(),
            'average_rating' => DB::table('item_ratings')
                ->join('orderitems', 'item_ratings.order_item_id', '=', 'orderitems.order_item_id')
                ->join('orders', 'orderitems.order_id', '=', 'orders.order_id')
                ->where('orders.seller_id', $seller->seller_id)
                ->avg('item_ratings.rating') ?? 0,
            'recent_feedback' => DB::table('item_ratings')
                ->join('orderitems', 'item_ratings.order_item_id', '=', 'orderitems.order_item_id')
                ->join('orders', 'orderitems.order_id', '=', 'orders.order_id')
                ->join('users', 'item_ratings.user_id', '=', 'users.user_id')
                ->join('menuitems', 'orderitems.item_id', '=', 'menuitems.item_id')
                ->where('orders.seller_id', $seller->seller_id)
                ->select('item_ratings.*', 'users.full_name', 'menuitems.item_name')
                ->orderBy('item_ratings.created_at', 'desc')
                ->limit(5)
                ->get()
        ];
        
        return response()->json($stats);
    }
    
    /**
     * Mark all feedback as read for the authenticated seller.
     */
    public function markAllAsRead(Request $request)
    {
        // Get the authenticated seller
        $user = Auth::user();
        $seller = Seller::where('user_id', $user->user_id)->first();
        
        if (!$seller) {
            // Return an error for AJAX requests
            if ($request->ajax()) {
                return response()->json(['error' => 'Seller not found'], 404);
            }
            return redirect()->route('seller.dashboard')->with('error', 'Seller profile not found');
        }
        
        // Ensure the feedback_read table exists before trying to insert into it
        if (!Schema::hasTable('feedback_read')) {
            Schema::create('feedback_read', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('seller_id');
                $table->unsignedBigInteger('order_id');
                $table->timestamps();
                
                $table->unique(['seller_id', 'order_id']);
                $table->foreign('seller_id')->references('seller_id')->on('sellers')->onDelete('cascade');
                $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            });
        }

        // Get all order IDs that have feedback for this seller
        $orderIds = Order::where('seller_id', $seller->seller_id)
            ->whereHas('orderItems', function($query) {
                $query->whereRaw('EXISTS (SELECT 1 FROM item_ratings WHERE item_ratings.order_item_id = orderitems.order_item_id)');
            })
            ->pluck('order_id');

        // Prepare data for batch insert
        $dataToInsert = $orderIds->map(function($orderId) use ($seller) {
            return [
                'seller_id' => $seller->seller_id, 
                'order_id' => $orderId,
                'created_at' => now(), 
                'updated_at' => now()
            ];
        })->all();
        
        // Use updateOrInsert for each to avoid duplicates
        if (!empty($dataToInsert)) {
            foreach ($dataToInsert as $data) {
                DB::table('feedback_read')->updateOrInsert(
                    ['seller_id' => $data['seller_id'], 'order_id' => $data['order_id']],
                    $data
                );
            }
        }
        
        // Always return a success JSON response for this background task.
        return response()->json(['success' => true]);
    }
} 
