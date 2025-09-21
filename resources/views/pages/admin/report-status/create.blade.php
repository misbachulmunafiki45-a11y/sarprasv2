@extends('layouts.admin')

@section('title', 'tambah Data Progress Laporan')

@section('content')
 <!-- Page Heading -->  
 <a href="{{ route('admin.report-status.index') }}" class="btn btn-danger mb-3">Kembali</a>

 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="card-header py-3">
         <h6 class="m-0 font-weight-bold text-primary">Tambah Data Progress Laporan {{ $report->code }}</h6>
     </div>
     <div class="card-body">
         <form action="{{ route('admin.report-status.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="report_id" value="{{ $report->id }}">
             <!-- bukti gambar -->
            <div class="form-group">
                <label for="image">Bukti</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                id="image" name="image">
             @error('image')
                   <div class="invalid-feedback">
                       {{ $message }}
                   </div>
               @enderror
               </div>  

               <!-- kolom  status -->
               <div class="form-group">
                   <label for="status">Status</label>
                   
                   <select name="status" class="form-control @error('status')is-invalid @enderror">
                        <option value="delivered" @if(old('status')== 'delivered') selected @endif>
                               Delivered
                        </option>
                        <option value="in_proses" @if(old('status')== 'in_proses') selected @endif>
                            In Proses
                        </option>
                        <option value="completed" @if(old('status')== 'completed') selected @endif>
                            Completed
                        </option>
                        <option value="rejected" @if(old('status')== 'rejected') selected @endif>
                            Rejected
                        </option>
                   </select>
                @error('status')
                      <div class="invalid-feedback">
                          {{ $message }}
                      </div>
                  @enderror
                  </div>  

            <!-- kolom pendanaan -->
            <div class="form-group">
                <label for="funding">Pendanaan</label>
                <input type="text" class="form-control @error('funding') is-invalid @enderror" id="funding"
                    name="funding" value="{{ old('funding') }}" placeholder="Contoh: APBD, BOS, CSR, dll">
                @error('funding')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!--kolom deskripsi -->
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="description"
                 name="description" value="{{ old('description') }}" rows="5">{{ old('description') }}</textarea>
                @error('description')
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