@php
    $map = [
        'Total Tugas' => ['color' => 'blue', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h6M9 8h6M5 6h14v12H5z"/></svg>'],
        'Selesai' => ['color' => 'green', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'],
        'Sedang Jalan' => ['color' => 'indigo', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>'],
        'Terlambat' => ['color' => 'red', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>'],
        'Deadline Mendekat' => ['color' => 'amber', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>'],
    ];
    $cfg = $map[$title] ?? ['color' => 'blue', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'];
    $bgClass = $cfg['color'] === 'green' ? 'bg-emerald-50 text-emerald-600' : ($cfg['color'] === 'red' ? 'bg-rose-50 text-rose-600' : ($cfg['color'] === 'amber' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600'));
@endphp
<div class="bg-white rounded-xl shadow-sm p-5 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <div>
            <div class="text-xs text-gray-400">{{ $title }}</div>
            <div class="text-2xl font-extrabold text-gray-900">{{ $value }}</div>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <div class="h-10 w-10 {{ $bgClass }} rounded-md flex items-center justify-center">{!! $cfg['icon'] !!}</div>
    </div>
</div>
