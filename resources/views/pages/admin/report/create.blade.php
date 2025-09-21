@extends('layouts.admin')

@section('title', 'tambah Data Laporan')

@section('content')
 <!-- Page Heading -->  
 <a href="{{ route('admin.report.index') }}" class="btn btn-danger mb-3">Kembali</a>


 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="card-header py-3">
         <h6 class="m-0 font-weight-bold text-primary">Tambah Data Laporan</h6>
     </div>
     <div class="card-body">
         <form action="{{ route('admin.report.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="code">Kode</label>
                <input type="text" class="form-control @error('code') 
                is-invalid @enderror" id="code" name="code"
                value="AUTO" disabled>
            
                @error('code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="resident">Pelapor</label>
                
                <select name="resident_id" class="form-control @error('resident_id') 
                is-invalid @enderror">
                    @foreach ($residents as $resident)
                        <option value="{{ $resident->id }}" @if(old('resident_id')== $resident->id) selected @endif>
                            {{ $resident->user->email }} - {{ $resident->user->name }}
                        </option>
                    @endforeach
                </select>
                @error('resident')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category">Kategori Pelapor</label>
                <select name="report_category_id" class="form-control @error('report_category_id') 
                is-invalid @enderror">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @if(old('report_category_id')== $category->id) selected @endif>
                             {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('report-category_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="title">Judul Laporan</label>
                <input type="text" class="form-control @error('title') 
                is-invalid @enderror" id="title" name="title"
                value="{{ old ('title') }}">
            
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description Laporan</label>
                <textarea type="text" class="form-control @error('description') 
                is-invalid @enderror" id="description" name="description"
                value="{{ old ('description') }}" rows="5"></textarea>
            
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
         
            <div class="form-group">
                <label for="image">Bukti Laporan</label>
                <input type="file" class="form-control @error('image') 
                is-invalid @enderror" id="image" name="image"
                value="{{ old ('image') }}">
            
                @error('image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Alamat laporan</label>
                <textarea type="text" class="form-control @error('address') 
                is-invalid @enderror"  id="address" name="address"
                value="{{ old ('address') }}" rows="5"></textarea>
            
                @error('address')
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