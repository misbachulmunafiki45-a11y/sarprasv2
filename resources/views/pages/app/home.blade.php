@extends('layouts.app')

@section('title', 'Home')

@section('content')
        @auth
            <h6 class="greeting"> {{ Auth::user()->name }} 👋</h6>
        @else
        <h6 class="greeting">Selamat datang</h6>
        @endauth
        <h4 class="home-headline">SIAP LAPOR SARANA DAN PRASARANA SEKOLAH</h4>

        <div class="d-flex align-items-center justify-content-between gap-4 py-3 overflow-auto" 
        id="category" style="white-space: nowrap;">

            @foreach($categories as $category)
            <a href="{{ route('report.index', ['category' => $category->name]) }}" class="category d-inline-block">
                <div class="icon">
                    @if ($category->image && \Illuminate\Support\Facades\Storage::disk('public')->fileExists($category->image))
                        <img src="{{ route('media', ['path' => $category->image]) }}" alt="icon">
                    @else
                        <img src="{{ asset('assets/app/images/icons/Checks.svg') }}" alt="icon">
                    @endif
                </div>
                <p>{{ $category->name }}</p>
            </a>
            @endforeach
        </div>

        <div class="py-3" id="reports">
            <div class="d-flex justify-content-between align-items-center">
                <h6>Pengaduan terbaru</h6>
    
                <a href="{{ route('report.index') }}" class="text-primary text-decoration-none show-more">
                    Lihat semua
                </a>
            </div>

            <div class="d-flex flex-column gap-3 mt-3">
                @foreach($reports as $report)
                <div class="card card-report border-0 shadow-none">
                    <a href="{{ route('report.show', $report->code) }}" class="text-decoration-none text-dark">
                        <div class="card-body p-0">
                            <div class="card-report-image position-relative mb-2">
                                @if ($report->image && \Illuminate\Support\Facades\Storage::disk('public')->fileExists($report->image))
                                    <img src="{{ route('media', ['path' => $report->image]) }}" alt="">
                                @else
                                    <img src="{{ asset('assets/app/images/report-1.png') }}" alt="">
                                @endif

                                @if ($report->reportStatuses->last()->status === 'delivered')
                                    <div class="badge-status on_process">
                                    Terkirim
                                    </div>
                                @endif

                                @if($report->reportStatuses->last()->status === 'in_proses')
                                    <div class="badge-status on_process">
                                        Di Proses
                                    </div>
                                @endif

                                @if($report->reportStatuses->last()->status === 'completed')
                                    <div class="badge-status done">
                                        Selesai
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <div class="d-flex align-items-center ">
                                    <img src="{{ asset('assets/app/images/icons/MapPin.png') }}" alt="map pin" class="icon me-2">
                                    <p class="text-primary city">
                                        {{ $report->address }}
                                    </p>
                                </div>

                                <p class="text-secondary date">
                                    {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y H:i') }}
                                </p>
                            </div>

                            <h1 class="card-title">
                                {{ $report->title }}
                            </h1>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

@endsection