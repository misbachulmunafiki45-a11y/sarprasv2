@extends('layouts.admin')

@section('title', 'Data Pelapor')

@section('content')

<a href="{{ route('admin.report-category.create') }}" class="btn btn-primary mb-3">Tambah Data</a>


<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Data Pelapor</h6>
        <div class="d-md-none">
            <small class="text-muted">Geser tabel ke samping</small>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="no-wrap">No</th>
                        <th>Nama</th>
                        <th class="no-wrap">Icon</th>
                        <th class="no-wrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <img src="{{ route('media', ['path' => $category->image]) }}" alt="image" width="100">
                        </td>
                        <td>
                            <a href="{{ route('admin.report-category.edit', $category->id) }}" 
                                class="btn btn-warning btn-sm">Edit</a>

                            <a href="{{ route('admin.report-category.show', $category->id) }}" 
                                class="btn btn-info btn-sm">Show</a>

                            <form action="{{ route('admin.report-category.destroy', $category->id) }}" 
                                method="POST" class="d-inline">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                @csrf
                                @method('DELETE')
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
