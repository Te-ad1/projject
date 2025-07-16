<!-- Left Sidebar -->
<div id="sidebar" class="fixed top-0 left-0 h-screen bg-primary flex flex-col justify-between z-50 transition-all duration-300">
    <div>
        <div class="flex justify-between items-center py-4 px-3">
            <h1 class="text-white text-xl font-bold text-center">FoodTime</h1>
            <button id="toggleSidebar" class="text-white p-1 rounded-md">
                <iconify-icon icon="mdi:chevron-left" width="20" height="20"></iconify-icon>
            </button>
        </div>
        
        <div class="mt-4 flex flex-col space-y-1 px-3">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 text-white {{ request()->routeIs('seller.dashboard') ? 'bg-white text-primary font-medium' : '' }} py-3 px-4 rounded-md" style="transition: none !important;">
                <iconify-icon icon="mdi:home-outline" width="20" height="20"></iconify-icon>
                <span class="sidebar-text">Dashboard</span>
            </a>
            
            <a href="{{ route('seller.menuItems.index') }}" class="flex items-center gap-3 text-white {{ request()->routeIs('seller.menuItems.*') ? 'bg-white text-primary font-medium' : '' }} py-3 px-4 rounded-md" style="transition: none !important;">
                <iconify-icon icon="mdi:silverware-fork-knife" width="20" height="20"></iconify-icon>
                <span class="sidebar-text">Menu Items</span>
            </a>
            
         
            
            <a href="{{ route('seller.categories') }}" class="flex items-center gap-3 text-white {{ request()->routeIs('seller.categories*') ? 'bg-white text-primary font-medium' : '' }} py-3 px-4 rounded-md" style="transition: none !important;">
                <iconify-icon icon="mdi:tag-outline" width="20" height="20"></iconify-icon>
                <span class="sidebar-text">Categories</span>
            </a>
            
            <a href="{{ route('seller.orders.index') }}" class="flex items-center gap-3 text-white relative {{ request()->routeIs('seller.orders*') ? 'bg-white text-primary font-medium' : '' }} py-3 px-4 rounded-md" style="transition: none !important;">
                <iconify-icon icon="mdi:file-document-outline" width="20" height="20"></iconify-icon>
                <span class="sidebar-text">Orders</span>
                
                @php
                    // Get count of new pending orders
                    $newOrdersCount = 0;
                    if(auth()->user()->role === 'seller') {
                        $seller = \App\Models\Seller::where('user_id', auth()->id())->first();
                        if($seller) {
                            $newOrdersCount = \App\Models\Order::where('seller_id', $seller->seller_id)
                                ->where('status', 'pending')
                                ->count();
                        }
                    }
                @endphp
                
                @if($newOrdersCount > 0)
                    <span class="absolute right-2 top-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold notification-badge">
                        {{ $newOrdersCount }}
                    </span>
                @endif
            </a>
            
            <a href="{{ route('seller.reports.sales') }}" class="flex items-center gap-3 text-white {{ request()->routeIs('seller.reports*') ? 'bg-white text-primary font-medium' : '' }} py-3 px-4 rounded-md" style="transition: none !important;">
                <iconify-icon icon="mdi:chart-bar" width="20" height="20"></iconify-icon>
                <span class="sidebar-text">Sales Report</span>
            </a>
            
            <a href="{{ route('seller.feedback.index') }}" class="flex items-center gap-3 text-white {{ request()->routeIs('seller.feedback*') ? 'bg-white text-primary font-medium' : '' }} py-3 px-4 rounded-md relative" style="transition: none !important;">
                <iconify-icon icon="mdi:message-text-outline" width="20" height="20"></iconify-icon>
                <span class="sidebar-text">Customer Feedback</span>
                
                @php
                $newFeedbackCount = 0;
                if (auth()->check() && auth()->user()->role === 'seller') {
                    $seller = \App\Models\Seller::where('user_id', auth()->id())->first();
                    if ($seller) {
                        $baseQuery = \App\Models\Order::where('seller_id', $seller->seller_id)
                            ->whereHas('orderItems', function($query) {
                                $query->whereRaw('EXISTS (SELECT 1 FROM item_ratings WHERE item_ratings.order_item_id = orderitems.order_item_id)');
                            });

                        // If the feedback_read table exists, filter out the read ones.
                        if (Illuminate\Support\Facades\Schema::hasTable('feedback_read')) {
                            $baseQuery->whereRaw('NOT EXISTS (SELECT 1 FROM feedback_read WHERE feedback_read.order_id = orders.order_id AND feedback_read.seller_id = ?)', [$seller->seller_id]);
                        }

                        $newFeedbackCount = $baseQuery->count();
                    }
                }
                @endphp
                
                @if($newFeedbackCount > 0)
                    <span class="absolute right-2 top-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold notification-badge">
                        {{ $newFeedbackCount }}
                    </span>
                @endif
            </a>
          
           
        </div>
    </div>
    
    <div class="mb-8 px-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full text-white py-3 px-4 rounded-md text-left" style="transition: none !important;">
                <iconify-icon icon="mdi:logout" width="20" height="20"></iconify-icon>
                <span class="sidebar-text">Logout</span>
            </button>
        </form>
    </div>
</div>

<!-- Toggle button to show sidebar when it's hidden -->
<button id="showSidebar" class="fixed top-4 left-4 z-50 bg-primary text-white p-2 rounded-md hidden">
    <iconify-icon icon="mdi:menu" width="24" height="24"></iconify-icon>
</button>

<style>
    /* Override any hover effects */
    .fixed.top-0.left-0.h-screen a:hover,
    .fixed.top-0.left-0.h-screen a:active,
    .fixed.top-0.left-0.h-screen a:focus,
    .fixed.top-0.left-0.h-screen button:hover,
    .fixed.top-0.left-0.h-screen button:active,
    .fixed.top-0.left-0.h-screen button:focus {
        background-color: transparent !important;
        color: white !important;
        outline: none !important;
        box-shadow: none !important;
    }
    
    /* Only apply background to active items */
    .fixed.top-0.left-0.h-screen a.bg-white {
        background-color: white !important;
        color: #1E90FF !important;
    }
    
    /* Ensure notification badge is always visible */
    .fixed.top-0.left-0.h-screen a .bg-red-500 {
        color: white !important;
    }
    
    /* Pulse animation for notification badge */
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }
    
    .notification-badge {
        animation: pulse 1.5s infinite;
    }
    
    /* Hide sidebar completely */
    #sidebar.hidden {
        transform: translateX(-100%);
    }
    
    /* Base sidebar styles */
    #sidebar {
        width: 175px;
    }
</style>

<script>
    // Update the notification badge count every 30 seconds
    // Only if we're on a seller page (not applicable for students)
    document.addEventListener('DOMContentLoaded', function() {
        // Check if user is a seller
        @if(auth()->check() && auth()->user()->role === 'seller')
        
        // Set up the interval to check for new orders
        setInterval(function() {
            // Use AJAX to get the current count of pending orders
            fetch('{{ route("seller.orders.index") }}?count_only=true')
                .then(response => response.json())
                .then(data => {
                    if (data.pending_count !== undefined) {
                        // Get the notification badge
                        const badgeContainer = document.querySelector('a[href="{{ route("seller.orders.index") }}"]');
                        
                        // If there are pending orders
                        if (data.pending_count > 0) {
                            // Check if badge already exists
                            let badge = badgeContainer.querySelector('.notification-badge');
                            
                            if (!badge) {
                                // Create a new badge if it doesn't exist
                                badge = document.createElement('span');
                                badge.className = 'absolute right-2 top-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold notification-badge';
                                badgeContainer.appendChild(badge);
                            }
                            
                            // Update the count
                            badge.textContent = data.pending_count;
                        } else {
                            // If no pending orders, remove the badge if it exists
                            const badge = badgeContainer.querySelector('.notification-badge');
                            if (badge) {
                                badge.remove();
                            }
                        }
                    }
                })
                .catch(error => console.error('Error fetching order count:', error));
        }, 30000); // Check every 30 seconds
        
        @endif
        
        // Simple sidebar toggle functionality
        const toggleButton = document.getElementById('toggleSidebar');
        const showButton = document.getElementById('showSidebar');
        const sidebar = document.getElementById('sidebar');
        
        // Check if sidebar was hidden in previous session
        if (localStorage.getItem('sidebarHidden') === 'true') {
            sidebar.classList.add('hidden');
            showButton.classList.remove('hidden');
        }
        
        // Hide sidebar when toggle is clicked
        toggleButton.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            sidebar.classList.add('hidden');
            showButton.classList.remove('hidden');
            localStorage.setItem('sidebarHidden', 'true');
        });
        
        // Show sidebar when menu button is clicked
        showButton.addEventListener('click', function(event) {
            event.preventDefault();
            sidebar.classList.remove('hidden');
            showButton.classList.add('hidden');
            localStorage.setItem('sidebarHidden', 'false');
        });
    });

function markFeedbackAsRead(event) {
    // Prevent the default navigation
    event.preventDefault();
    
    // Find the notification badge
    const badge = event.currentTarget.querySelector('.notification-badge');
    if (badge) {
        // Remove the badge visually immediately for better UX
        badge.style.display = 'none';
        
        // Create a form element
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("seller.feedback.markRead") }}';
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add redirect parameter
        const redirect = document.createElement('input');
        redirect.type = 'hidden';
        redirect.name = 'redirect';
        redirect.value = '{{ route("seller.feedback.index") }}';
        form.appendChild(redirect);
        
        // Append form to body
        document.body.appendChild(form);
        
        // Submit the form
        form.submit();
    } else {
        // If no badge, just navigate to the page
        window.location.href = '{{ route("seller.feedback.index") }}';
    }
}
</script>
