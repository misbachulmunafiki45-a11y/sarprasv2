@extends('layouts.admin')

@section('title', 'Detail Laporan')

@section('content')

<div class="d-flex gap-2 mb-3">
    <a href="{{ route('admin.report.index') }}" class="btn btn-danger">Kembali</a>
    @php($last = $report->reportStatuses->last())
    @if($last && $last->status === 'completed')
        <a href="{{ route('admin.report.print', $report->id) }}" class="btn btn-primary" target="_blank">
            <i class="fas fa-print"></i> Cetak PDF
        </a>
    @endif
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Detail Laporan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <td class="no-wrap">Kode Laporan</td>
                    <td class="text-break">{{ $report->code }}</td>
                </tr>

                <tr>
                    <td class="no-wrap">Pelapor</td>
                    <td class="text-break">{{ $report->resident->user->email }} - {{ $report->resident->user->name }}</td>
                </tr>

                <tr>
                    <td class="no-wrap">Kategori Laporan</td>
                    <td class="text-break">{{ $report->reportCategory->name }} </td>
                </tr>

                <tr>
                    <td class="no-wrap">judul Laporan</td>
                    <td class="text-break">{{ $report->title}} </td>
                </tr>
                <tr>
                    <td class="no-wrap">Tanggal</td>
                    <td class="text-break">
                        {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="no-wrap">Posisi</td>
                    <td class="text-break">{{ $report->address }}</td>
                </tr>

                <tr>
                    <td class="no-wrap">Deskripsi Laporan</td>
                    <td class="text-break">{{ $report->description }} </td>
                </tr>

                <tr>
                    <td class="no-wrap">Bukti Laporan</td>
                    <td>
                        @if ($report->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($report->image))
                            <img src="{{ route('media', ['path' => $report->image]) }}" alt="image" class="img-fluid" style="max-width: 280px; height: auto;">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="card shadow mb-5">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Progress Laporan</h6>
    </div>
    <div class="card-body">
        <a href="{{ route('admin.report-status.create', $report->id) }}" 
            class="btn btn-primary mb-3">Tambah Progress</a>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="no-wrap">No</th>
                        <th class="no-wrap">Bukti</th>
                        <th class="no-wrap">Status</th>
                        <th>Deskripsi</th>
                        <th class="no-wrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report->reportStatuses as $status)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
       
                        <td>
                            @if($status->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($status->image))
                                <img src="{{ route('media', ['path' => $status->image]) }}" alt="image" width="100" class="img-fluid">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ $status->status }}
                        </td>
                        <td>
                            {{ $status->description }}
                        </td>
                        <td>
                            <a href="{{ route('admin.report-status.edit', $status->id) }}" 
                                class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.report-status.destroy', $status->id) }}" 
                                method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

{{-- scripts section with Leaflet map has been removed --}}