@extends('layouts.admin')

@section('title', 'Edit Data Prograss Laporan')

@section('content')
 <!-- Page Heading -->
 <a href="{{ route('admin.report.show', $status->report->id) }}" class="btn btn-danger mb-3">Kembali</a>

 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="card-header py-3">
         <h6 class="m-0 font-weight-bold text-primary">Edit Data Progress Laporan {{ $status->report->code }}</h6>
     </div>
     <div class="card-body">
         <form action="{{ route('admin.report-status.update', $status->id) }}" 
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="report_id" value="{{ $status->report_id }}">
            <!-- bukti gambar -->
            <div class="form-group">
                <label for="image">Bukti</label>
                <br>
                @if($status->image) 
                <img src="{{ route('media', ['path' => $status->image]) }}" alt="image" width="200">
                <br>
                @endif
                <br>
                <!-- Satu input file disembunyikan; dua tombol untuk kamera/galeri -->
                <input type="file" accept="image/*" capture="environment" class="form-control d-none @error('image') is-invalid @enderror" 
                id="image" name="image">
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

               <!-- kolom  status -->
               <div class="form-group">
                   <label for="status">Status</label>
                   
                   <select name="status" class="form-control @error('status')is-invalid @enderror">
                        <option value="delivered" @if(old('status', $status->status)== 'delivered') selected @endif>
                               Delivered
                        </option>
                        <option value="in_proses" @if(old('status', $status->status)== 'in_proses') selected @endif>
                            In Proses
                        </option>
                        <option value="completed" @if(old('status', $status->status)== 'completed') selected @endif>
                            Completed
                        </option>
                        <option value="rejected" @if(old('status', $status->status)== 'rejected') selected @endif>
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
                    name="funding" value="{{ old('funding', $status->funding) }}" placeholder="Contoh: APBD, BOS, CSR, dll">
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
                 name="description" value="{{ old('description', $status->description) }}" rows="5">{{ old('description', $status->description) }}</textarea>
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
    setTimeout(() => {
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