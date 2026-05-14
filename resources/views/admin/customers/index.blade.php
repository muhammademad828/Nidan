@extends('layouts.admin')

@section('title', 'Customer Intelligence')
@section('page-title', 'Customer Database & Insights')

@section('page-content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <p class="text-sm text-gray-500">Comprehensive database of everyone who has ever purchased from Nidan Atelier.</p>
    </div>
    <div class="flex gap-2">
        <button onclick="exportToCSV()" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
            <i class="fas fa-file-export mr-2"></i> Export CSV
        </button>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="customers-table">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Customer</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Contact Info</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Orders</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Total Spent</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Last Purchase</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Fav. Product</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($customers as $customer)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-nidan-gold/10 flex items-center justify-center text-nidan-gold font-bold">
                                {{ substr($customer['name'], 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">{{ $customer['name'] }}</div>
                                <div class="text-xs text-gray-400">ID: {{ $customer['id'] ?? 'Guest' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-envelope text-gray-300 w-4"></i>
                                {{ $customer['email'] }}
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-phone text-gray-300 w-4"></i>
                                {{ $customer['phone'] }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 font-bold text-xs">
                            {{ $customer['orders_count'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-900">EGP {{ number_format($customer['total_spent'], 2) }}</div>
                        <div class="text-[10px] text-gray-400 uppercase">Gross Revenue</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600">{{ $customer['last_purchase'] ? $customer['last_purchase']->format('d M Y') : 'N/A' }}</div>
                        <div class="text-[10px] text-gray-400">{{ $customer['last_purchase'] ? $customer['last_purchase']->diffForHumans() : '' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs text-gray-600 bg-gray-100 px-3 py-1 rounded-full inline-block max-w-[150px] truncate">
                            {{ $customer['favorite_product'] }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($customer['type'] === 'Registered')
                            <span class="px-3 py-1 rounded-full bg-green-50 text-green-600 text-[10px] font-bold uppercase tracking-widest">
                                Member
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-gray-50 text-gray-500 text-[10px] font-bold uppercase tracking-widest">
                                Guest
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function exportToCSV() {
    let csv = [];
    let rows = document.querySelectorAll("#customers-table tr");
    
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (let j = 0; j < cols.length; j++) {
            let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
            row.push('"' + text + '"');
        }
        
        csv.push(row.join(","));
    }

    let csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
    let downloadLink = document.createElement("a");
    downloadLink.download = "nidan_customers_export_" + new Date().toISOString().slice(0,10) + ".csv";
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
}
</script>
@endpush
@endsection
