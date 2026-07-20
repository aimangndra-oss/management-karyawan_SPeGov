@extends('layouts.app')

@section('title', $task->task_number)

@section('content')
<div class="p-6">
    <div class="bg-white p-6 rounded-lg border">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-sm text-slate-500">#{{ $task->task_number }}</div>
                <h2 class="text-2xl font-bold mt-1">{{ $task->title }}</h2>
                <div class="text-xs text-slate-500 mt-1">Penanggung: {{ $task->assignee?->name ?? '-' }}</div>
                <div class="text-xs text-slate-500">Batas Waktu: {{ $task->deadline?->format('Y-m-d') ?? '-' }}</div>
                <div class="mt-3 text-sm text-slate-700">{!! nl2br(e($task->description)) !!}</div>
            </div>
            <div class="text-right">
                <div class="mb-2">Status: <span class="font-semibold">{{ $task->status }}</span></div>
                <div>Progress: <strong>{{ $task->progress_percentage }}%</strong></div>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="inline-block px-3 py-2 bg-slate-200 rounded-lg">Kembali</a>
            @if(Auth::user()->role === \App\Enums\UserRole::KABID)
            <a href="#" class="inline-block px-3 py-2 bg-blue-600 text-white rounded-lg">Edit</a>
            @endif
        </div>
    </div>
</div>
@endsection
