<div class="mb-6 flex flex-wrap gap-2">
    @foreach([
        ['label' => 'Dashboard Sales', 'route' => 'admin.sales.dashboard'],
        ['label' => 'Data Penjualan', 'route' => 'admin.sales.list'],
        ['label' => 'Marketing', 'route' => 'admin.sales.marketing'],
        ['label' => 'Master Komisi', 'route' => 'admin.sales.commissions'],
        ['label' => 'Target', 'route' => 'admin.sales.targets'],
    ] as $item)
        <a href="{{ route($item['route']) }}"
            class="rounded-lg px-4 py-2 text-sm font-bold transition {{ request()->routeIs($item['route']) ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-gray-700 ring-1 ring-gray-200 hover:bg-blue-50 hover:text-blue-700' }}">
            {{ $item['label'] }}
        </a>
    @endforeach
</div>
