@extends('layouts.admin')

@section('title', 'Edit Data Pelapor')

@section('content')
 <!-- Page Heading -->
 <a href="{{ route('admin.report.index') }}" class="btn btn-danger mb-3">Kembali</a>


 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="card-header py-3">
         <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
     </div>
     <div class="card-body">
         <form action="{{ route('admin.report.update', $report-> id) }}" 
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="code">Kode</label>
                <input type="text" class="form-control @error('code') 
                is-invalid @enderror" id="code" name="code"
                value="{{ $report->code }}" disabled>
            
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
                        <option value="{{ $resident->id }}" @if(old('resident_id', $report->resident_id)== $resident->id) selected @endif>
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
                        <option value="{{ $category->id }}" 
                            @if(old('report_category_id', $report->report_category_id) == $category->id) selected @endif>
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
                value="{{ old ('title', $report->title) }}">
            
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description Laporan</label>
                <textarea type="text" class="form-control @error('description') is-invalid @enderror" 
                id="description" name="description"
                value="{{ old ('description', $report->description) }}" 
                rows="5">{{ old ('description', $report->description) }}</textarea>
            
                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
         
            <div class="form-group">
                <label for="image">Bukti Laporan</label>
                <br>
                <img src="{{ route('media', ['path' => $report->image]) }}" alt="image" width="100">
                <input type="file" accept="image/*" capture="environment" class="form-control d-none @error('image') 
                is-invalid @enderror" id="image" name="image">
                <div class="d-flex gap-2 mt-2">
                  <button type="button" class="btn btn-success" id="btnCamera">Buka Kamera (Belakang)</button>
                  <button type="button" class="btn btn-secondary" id="btnGallery">Pilih dari Galeri</button>
                </div>
                <div id="preview" class="mt-2"></div>
            
                @error('image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Latitude/Longitude fields removed by request -->

            <div class="form-group">
                <label for="address">Alamat laporan</label>
                <textarea type="text" class="form-control @error('address') 
                is-invalid @enderror"  id="address" name="address"
                value="{{ old ('address', $report->address) }}" rows="5">{{ old ('address', $report->address) }}</textarea>
            
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

@section('scripts')
<script>
(function(){
  const fileInput = document.getElementById('image');
  const btnCamera = document.getElementById('btnCamera');
  const btnGallery = document.getElementById('btnGallery');
  const preview = document.getElementById('preview');

  function openWithCamera(){
    if(fileInput) fileInput.click();
  }
  function openFromGallery(){
    if(!fileInput) return;
    const hadCapture = fileInput.hasAttribute('capture');
    if(hadCapture) fileInput.removeAttribute('capture');
    fileInput.click();
    setTimeout(() => { try { fileInput.setAttribute('capture','environment'); } catch(e) {} }, 500);
  }
  function renderPreview(){
    preview.innerHTML = '';
    const file = fileInput.files && fileInput.files[0];
    if(!file) return;
    const img = document.createElement('img');
    img.style.maxWidth = '220px';
    img.style.height = 'auto';
    img.alt = 'preview';
    img.src = URL.createObjectURL(file);
    img.onload = () => URL.revokeObjectURL(img.src);
    preview.appendChild(img);
  }
  if(btnCamera) btnCamera.addEventListener('click', openWithCamera);
  if(btnGallery) btnGallery.addEventListener('click', openFromGallery);
  if(fileInput) fileInput.addEventListener('change', renderPreview);
})();
</script>
@endsection