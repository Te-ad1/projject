<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Feedback - Canteen Online Ordering System</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E90FF',
                        secondary: '#484648',
                        lightsky: '#87CEFA',
                        darkgrey: '#a7a7a7',
                    },
                    fontFamily: {
                        sans: ['Open Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-lightsky flex w-full h-screen overflow-x-hidden">
    @include('includes.sidebar')

    <!-- Main Content -->
    <div class="ml-[175px] w-[calc(100%-175px)] p-3">
        <div class="bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Customer Feedback</h1>
                    <p class="mt-1 text-sm text-gray-600">View and manage feedback from your customers.</p>
                </div>
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Total Feedback Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900">Total Feedback</h3>
                                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_feedback'] }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Average Rating Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h3 class="text-lg font-medium text-gray-900">Average Rating</h3>
                                <div class="flex items-center mt-1">
                                    <p class="text-3xl font-semibold text-gray-900">{{ number_format($stats['average_rating'], 1) }}</p>
                                    <div class="ml-2 flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= round($stats['average_rating']))
                                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rating Distribution Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-medium text-gray-900">Rating Distribution</h3>
                        <div class="mt-2">
                            @foreach(range(5, 1) as $rating)
                                <div class="flex items-center mt-1">
                                    <span class="text-sm font-medium text-gray-600 w-3">{{ $rating }}</span>
                                    <div class="flex items-center ml-2">
                                        <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 ml-2">
                                        @php
                                            $percentage = $stats['total_feedback'] > 0 
                                                ? ($stats['rating_distribution'][$rating] / $stats['total_feedback']) * 100 
                                                : 0;
                                        @endphp
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">{{ $stats['rating_distribution'][$rating] }}</span>
                                    <span class="ml-1 text-sm text-gray-500">({{ number_format($percentage, 1) }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Feedback List -->
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul class="divide-y divide-gray-200">
                        @forelse ($ordersWithFeedback as $order)
                            <li>
                                <a href="{{ route('seller.feedback.show', $order->order_id) }}" class="block hover:bg-gray-50">
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-blue-600 truncate">
                                                    Order #{{ $order->order_number }}
                                                </p>
                                                <p class="mt-1 flex items-center text-sm text-gray-500">
                                                    <span class="truncate">{{ $order->student->full_name }}</span>
                                                </p>
                                            </div>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ ucfirst($order->status) }}
                                                </p>
                                                <p class="ml-2 text-sm text-gray-500">
                                                    {{ $order->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <div class="flex items-center text-sm">
                                                <p class="text-sm text-gray-500">
                                                    {{ count($order->orderItems) }} item(s) rated
                                                </p>
                                                <div class="ml-4 flex">
                                                    @php
                                                        $avgRating = $order->orderItems->avg('rating');
                                                    @endphp
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= round($avgRating))
                                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                    <span class="ml-1 text-sm text-gray-500">
                                                        {{ number_format($avgRating, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            @if ($order->orderItems->first()->ratingDetails->review)
                                                <p class="mt-2 text-sm text-gray-500 line-clamp-2">
                                                    "{{ $order->orderItems->first()->ratingDetails->review }}"
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="px-4 py-6 sm:px-6">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No feedback yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        You haven't received any ratings or reviews yet.
                                    </p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
                
                <!-- Pagination -->
                @if ($ordersWithFeedback->hasPages())
                    <div class="mt-4">
                        {{ $ordersWithFeedback->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const menuToggle = document.querySelector('.lg\\:hidden');
        const sidebar = document.querySelector('.fixed');
        
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                sidebar.classList.toggle('translate-x-0');
            });
        }
        
        // Remove notification badge when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Send a request to mark all feedback as read.
            // This is "fire and forget", we don't need to wait for the response.
            fetch('{{ route("seller.feedback.markRead") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            }).catch(error => console.error('Error marking feedback as read:', error));

            // For immediate user feedback, also remove the badge from the sidebar on this page.
            const feedbackLinkInSidebar = document.querySelector('a[href*="seller/feedback"]');
            if (feedbackLinkInSidebar) {
                const badge = feedbackLinkInSidebar.querySelector('.notification-badge');
                if (badge) {
                    badge.remove();
                }
            }
        });
    </script>
</body>
</html> 
