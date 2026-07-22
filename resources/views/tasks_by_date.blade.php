@extends('layouts.app')

@section('title', 'Tugas pada ' . $date)

@section('content')
<div class="flex flex-col min-h-screen justify-between p-6">
    {{-- Bagian Utama Konten --}}
    <div class="flex-grow">
        <h2 class="text-xl font-bold mb-4">Tugas pada {{ $date }}</h2>

        @if(count($tasks) === 0)
            <div class="py-6 text-center text-slate-600">Tidak ada tugas pada tanggal ini.</div>
        @else
            <div class="space-y-4">
                @foreach($tasks as $t)
                <div class="bg-white p-4 rounded-lg border">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-sm text-slate-500">#{{ $t['task_number'] }}</div>
                            <h3 class="font-semibold">{{ $t['title'] }}</h3>
                            <div class="text-xs text-slate-500">Penanggung: {{ $t['assignee'] ?? '-' }}</div>
                        </div>
                        <div class="text-right">
                            <a href="/tasks/{{ $t['id'] }}" class="inline-block px-3 py-1 bg-blue-600 text-white rounded-lg">Lihat</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection