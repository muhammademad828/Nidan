@extends('layouts.admin')
@section('title', 'Reviews')
@section('page-title', 'Reviews')
@section('breadcrumb', 'Home / Reviews')

@section('page-content')

<div class="mb-6 flex flex-wrap gap-4 items-center justify-between">
    <div class="flex gap-2">
        <a href="{{ route('admin.reviews.index') }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-nidan-gold text-white' : 'bg-white text-gray-600 border border-gray-100 hover:bg-gray-50' }}">
            All
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-600 border border-gray-100 hover:bg-gray-50' }}">
            Pending
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'approved' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 border border-gray-100 hover:bg-gray-50' }}">
            Approved
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'rejected' ? 'bg-red-600 text-white' : 'bg-white text-gray-600 border border-gray-100 hover:bg-gray-50' }}">
            Rejected
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-gray-400 uppercase tracking-widest border-b">
                <th class="p-4">Product</th>
                <th class="p-4">Reviewer</th>
                <th class="p-4">Rating</th>
                <th class="p-4">Comment</th>
                <th class="p-4">Status</th>
                <th class="p-4">Date</th>
                <th class="p-4">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($reviews as $review)
            <tr class="hover:bg-gray-50/50">
                <td class="p-4">
                    @if($review->product)
                    <a href="{{ route('product.show', ['product' => $review->product->slug]) }}" target="_blank"
                        class="text-nidan-gold hover:underline text-xs font-medium">{{ $review->product->name }}</a>
                    @else <span class="text-gray-400">—</span> @endif
                </td>
                <td class="p-4">
                    <div class="flex flex-col">
                        <span class="font-medium text-gray-900">{{ $review->name }}</span>
                        @if($review->user)
                            <span class="text-[10px] text-blue-500 uppercase font-bold tracking-tighter">Registered User</span>
                        @else
                            <span class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">Guest</span>
                        @endif
                    </div>
                </td>
                <td class="p-4">
                    <div class="flex gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-nidan-gold' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                </td>
                <td class="p-4 max-w-xs text-gray-500">
                    <p class="truncate" title="{{ $review->comment }}">{{ $review->comment ?? '—' }}</p>
                </td>
                <td class="p-4">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                        @if($review->status === 'approved') bg-green-100 text-green-700
                        @elseif($review->status === 'rejected') bg-red-100 text-red-700
                        @else bg-yellow-100 text-yellow-700 @endif">
                        {{ $review->status }}
                    </span>
                </td>
                <td class="p-4 text-xs text-gray-400 whitespace-nowrap">{{ $review->created_at->format('d M Y') }}</td>
                <td class="p-4">
                    <div class="flex gap-2 items-center">
                        @if($review->status !== 'approved')
                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline">
                            @csrf @method('PATCH')
                            <button title="Approve & Show" class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif

                        @if($review->status === 'approved')
                        <form method="POST" action="{{ route('admin.reviews.hide', $review) }}" class="inline">
                            @csrf @method('PATCH')
                            <button title="Hide (Set Pending)" class="p-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </form>
                        @endif

                        @if($review->status !== 'rejected')
                        <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" class="inline">
                            @csrf @method('PATCH')
                            <button title="Reject" class="p-2 bg-red-50 text-red-400 rounded-lg hover:bg-red-100 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endif

                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this review permanently?')">
                            @csrf @method('DELETE')
                            <button title="Delete Permanently" class="p-2 text-red-600 hover:text-red-800 transition-colors">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="p-12 text-center text-gray-400 italic">No reviews found in this category.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($reviews->hasPages())
    <div class="p-4 border-t bg-gray-50/50">{{ $reviews->links() }}</div>
    @endif
</div>

@endsection
