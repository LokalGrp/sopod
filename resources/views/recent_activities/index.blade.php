@extends('layouts.app')
@section('title', $pageTitle ?? 'All Recent Activities')
@section('content')
{{-- Layout only. The page was wrapped in a single card holding both the
     header and the table, which produced a page-inside-a-box. The header now
     sits on the page itself and only the table gets a card, matching the
     other modules. Data, routes and pagination are unchanged. --}}
<div class="page-wide">
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $pageTitle ?? 'All Recent Activities' }}</h1>
            <p class="page-subtitle">Activity recorded across this module.</p>
        </div>
        <div class="page-actions">
            <a href="{{ $backRoute ?? route('dashboard') }}" class="btn-secondary">
                Back to Dashboard
            </a>
        </div>
    </div>

    @if($recentActivities->isEmpty())
        <div class="card">
            <div class="empty-state">
                <svg class="ico" style="width:22px;height:22px;color:#D1D5DB;margin-bottom:6px" aria-hidden="true"><use href="#i-repeat"/></svg>
                <div class="empty-state-title">No recent activity</div>
                <div class="empty-note">Activity for this module will appear here.</div>
            </div>
        </div>
    @else
        <div class="card card-table">
        <div class="table-wrap">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-700 text-xs uppercase text-gray-200">
                    <tr>
                        <th class="px-4 py-3 border-b border-gray-600">Date</th>
                        <th class="px-4 py-3 border-b border-gray-600">Activity</th>
                        <th class="px-4 py-3 border-b border-gray-600">Type</th>
                        <th class="px-4 py-3 border-b border-gray-600">Item</th>
                        <th class="px-4 py-3 border-b border-gray-600">User</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivities as $activity)
                        <tr class="hover:bg-gray-700 border-b border-gray-700">
                            <td class="px-4 py-2 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($activity->created_at)->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-2">{{ $activity->message ?? '—' }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap
                                    {{ $activity->type === 'Customer' ? 'bg-blue-100 text-blue-700' :
                                       ($activity->type === 'Item' ? 'bg-green-100 text-green-700' :
                                       ($activity->type === 'Sales Order' ? 'bg-yellow-100 text-yellow-700' :
                                       ($activity->type === 'Delivery' ? 'bg-purple-100 text-purple-700' :
                                       ($activity->type === 'Purchase Order' ? 'bg-orange-100 text-orange-700' :
                                       ($activity->type === 'Supplier' ? 'bg-teal-100 text-teal-700' :
                                       ($activity->type === 'Purchase Request' ? 'bg-blue-100 text-blue-700' :
                                       ($activity->type === 'Request For Payment' ? 'bg-purple-100 text-purple-700' :
                                       ($activity->type === 'Accounts Payable Invoice' ? 'bg-orange-100 text-orange-700' :
                                       ($activity->type === 'Check Voucher' ? 'bg-green-100 text-green-700' :
                                       'bg-red-100 text-red-700'))))))))) }}">
                                    {{ $activity->type ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $activity->item ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $activity->user_name ?? 'System' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
        <div class="mt-4 flex justify-center pr-2">
            {{ $recentActivities->onEachSide(1)->links('vendor.pagination.elegant') }}
        </div>
    @endif
</div>
@endsection