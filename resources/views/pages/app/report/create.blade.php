@extends('layouts.no-nav')

@section('title', 'Buat Laporan')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Buat Laporan</h2>
    <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Judul</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="report_category_id">Kategori</label>
            <select name="report_category_id" id="report_category_id" class="form-control @error('report_category_id') is-invalid @enderror" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if(old('report_category_id') == $category->id) selected @endif>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('report_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Posisi (dipindah dari bawah foto, tetap gunakan name="address" untuk kompatibilitas backend) -->
        <div class="form-group">
            <label for="address">Posisi</label>
            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="image">Foto</label>
            <!-- Satu input file, default mengarah ke kamera (belakang) di perangkat mobile -->
            <input type="file" accept="image/*" capture="environment" class="form-control d-none @error('image') is-invalid @enderror" id="image" name="image">
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn btn-success" id="btnCamera">Buka Kamera (Belakang)</button>
                <button type="button" class="btn btn-secondary" id="btnGallery">Pilih dari Galeri</button>
            </div>
            <small class="text-muted d-block mt-1">Di perangkat mobile, tombol Kamera akan mencoba membuka kamera belakang. Tombol Galeri akan membuka penyimpanan foto.</small>
            <div id="preview" class="mt-2"></div>
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- removed hidden latitude/longitude inputs -->

        

        <div class="d-flex gap-2 mt-2">
            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Kirim</button>
            <a href="{{ route('home') }}" class="btn btn-outline-dark rounded-pill px-4 ms-auto">cancel</a>
        </div>
         </form>
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
     // capture sudah diset melalui atribut HTML (lebih kompatibel di mobile)
     if(fileInput) fileInput.click();
   }

   function openFromGallery(){
     // Nonaktifkan capture sementara untuk membuka galeri/file picker,
     // lalu kembalikan agar klik berikutnya tetap membuka kamera.
     if(!fileInput) return;
     const hadCapture = fileInput.hasAttribute('capture');
     if(hadCapture) fileInput.removeAttribute('capture');
     fileInput.click();
     setTimeout(() => {
       // Pulihkan agar default kembali kamera
       try { fileInput.setAttribute('capture','environment'); } catch(e) {}
     }, 500);
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