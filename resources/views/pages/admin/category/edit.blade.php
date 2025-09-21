@extends('layouts.admin')

@section('title', 'Edit Data Pelapor')

@section('content')
 <!-- Page Heading -->
 <a href="{{ route('admin.report-category.index') }}" class="btn btn-danger mb-3">Kembali</a>


 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="card-header py-3">
         <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
     </div>
     <div class="card-body">
         <form action="{{ route('admin.report-category.update', $category-> id) }}" 
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                id="name" name="name"
                value="{{ old('name',$category->name) }}">
            
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
             <div class="form-group">
                <p>Icon / Gambar lama</p>
                @if($category->image)
                <img src="{{ route('media', ['path' => $category->image]) }}" alt="image" width="50">
                <br>
                @endif
                 <label for="image"></label>
                 <input type="file" class="form-control @error('image') is-invalid @enderror" 
                 id="image" name="image">
              @error('image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                </div>
             <button type="submit" class="btn btn-primary">Submit</button>
         </form>
     </div>
 </div>

@endsection