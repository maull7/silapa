@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Pengajuan</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('pengajuan.update', $request->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Basic Information Section -->
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Nama Pengadaan</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $request->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="desc" class="form-label">Deskripsi pengadaan</label>
                                    <input type="text" name="desc"
                                        class="form-control @error('desc') is-invalid @enderror"
                                        value="{{ old('desc', $request->desc) }}" required>
                                    @error('desc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nilai_kontrak" class="form-label">Nilai Kontrak/Pengadaan</label>
                                    <input type="text" name="nilai_kontrak"
                                        class="form-control @error('nilai_kontrak') is-invalid @enderror"
                                        value="{{ old('nilai_kontrak', $request->nilai_kontrak) }}" required>
                                    @error('nilai_kontrak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nilai_ajuan" class="form-label">Nilai Pengajuan Pembayaran</label>
                                    <input type="text" name="nilai_ajuan"
                                        class="form-control @error('nilai_ajuan') is-invalid @enderror"
                                        value="{{ old('nilai_ajuan', $request->nilai_ajuan) }}" required>
                                    @error('nilai_ajuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="Tp" class="form-label">Type Pengadaan</label>
                                    <select name="type_pengadaan" id="Tp" class="form-control">
                                        <option value="SWAKELOLA"
                                            {{ $request->type_pengadaan == 'SWAKELOLA' ? 'selected' : '' }}>SWAKELOLA
                                        </option>
                                        <option value="SUPLIER"
                                            {{ $request->type_pengadaan == 'SUPLIER' ? 'selected' : '' }}>SUPLIER</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="T" class="form-label">Type</label>
                                    <select name="type" id="T" class="form-control">
                                        <option value="SUPLIER" {{ $request->type == 'SUPLIER' ? 'selected' : '' }}>SUPLIER
                                        </option>
                                        <option value="VENDOR" {{ $request->type == 'VENDOR' ? 'selected' : '' }}>VENDOR
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="rab" class="form-label">Tanggal Pengadaan</label>
                                    <input type="date" name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
                                        value="{{ old('tanggal', $request->tanggal) }}" required>
                                    @error('rab')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- File Upload Section -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Upload Dokumen</h5>
                                    <small class="text-muted">Silakan upload dokumen satu per satu</small>
                                </div>
                                <div class="card-body">
                                    <div class="progress mb-3" style="height: 10px;">
                                        <div id="uploadProgress" class="progress-bar" role="progressbar" style="width: 0%">
                                        </div>
                                    </div>

                                    <div id="editFileSteps" class="mb-4"></div>
                                    <div id="hiddenFileInputs" class="d-none"></div>

                                    <div class="d-flex justify-content-between">
                                        <button type="button" id="prevStepBtn" class="btn btn-secondary" disabled>
                                            <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                                        </button>
                                        <button type="button" id="nextStepBtn" class="btn btn-primary">
                                            Selanjutnya <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Buttons -->
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i> Perbarui Data
                                </button>
                            </div>
                        </form>



                        <style>
                            .upload-step {
                                transition: all 0.3s ease;
                            }

                            .progress-bar {
                                transition: width 0.3s ease;
                            }

                            .file-preview .alert {
                                padding: 0.5rem 1rem;
                                margin-bottom: 0;
                            }

                            .badge {
                                font-size: 0.85rem;
                                padding: 0.35rem 0.65rem;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        (function() {
            const fileSteps = [{
                    id: 'nota',
                    label: 'Nota Dinas dan Disposisi KPA',
                    required: true
                },
                {
                    id: 'rab',
                    label: 'RAB',
                    required: true
                },
                {
                    id: 'kwitansi',
                    label: 'Kwitansi Bukti Pembayaran',
                    required: true
                },
                {
                    id: 'bukti_nota',
                    label: 'Faktur / Nota Bukti Pembelian',
                    required: true
                },
                {
                    id: 'berita_acara',
                    label: 'Berita Acara Penyelesaian Pekerjaan',
                    required: true
                },
                {
                    id: 'serah_terima',
                    label: 'Berita Acara Serah Terima Pekerjaan',
                    required: true
                },
                {
                    id: 'pembayaran',
                    label: 'Berita Acara Pembayaran',
                    required: false
                },
                {
                    id: 'jaminan_garansi',
                    label: 'Jaminan Bank Garansi Uang Muka',
                    required: false
                },
                {
                    id: 'jaminan_pelaksanaan',
                    label: 'Jaminan Bank Garansi Pelaksanaan',
                    required: false
                },
                {
                    id: 'keputusan',
                    label: 'Keputusan Penetapan Pemenang',
                    required: false
                },
                {
                    id: 'surat_kontrak',
                    label: 'Surat Kontrak / Pesanan',
                    required: true
                },
                {
                    id: 'surat_perintah',
                    label: 'Surat Perintah Kerja',
                    required: false
                },
                {
                    id: 'dokumentasi',
                    label: 'Dokumentasi Kegiatan',
                    required: false
                },
                {
                    id: 'faktur_pajak',
                    label: 'Faktur Pajak',
                    required: false
                },
                {
                    id: 'spp',
                    label: 'SPP',
                    required: false
                },
                {
                    id: 'spm',
                    label: 'SPM',
                    required: false
                },
                {
                    id: 'ssp',
                    label: 'SSP',
                    required: false
                },
                {
                    id: 'sp2d',
                    label: 'SP2D',
                    required: false
                },
                {
                    id: 'lain-lain',
                    label: 'Dokumen lainnya',
                    required: false
                }
            ];

            const oldFiles = @json($request);
            const fileUploadSteps = document.getElementById('editFileSteps');
            const hiddenInputs = document.getElementById('hiddenFileInputs');
            const prevBtn = document.getElementById('prevStepBtn');
            const nextBtn = document.getElementById('nextStepBtn');
            const submitBtn = document.getElementById('submitBtn');
            const progressBar = document.getElementById('uploadProgress');

            let currentStep = 0;
            const uploadedFiles = {};

            function formatFileSize(bytes) {
                if (!bytes) return '';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function updateProgress() {
                const totalRequired = fileSteps.filter(step => step.required).length;
                const completed = fileSteps.filter(step => {
                    return step.required && (uploadedFiles[step.id] || oldFiles[step.id]);
                }).length;

                const percent = Math.round((completed / totalRequired) * 100);
                progressBar.style.width = percent + '%';
                progressBar.setAttribute('aria-valuenow', percent);
                progressBar.textContent = percent + '%';

                submitBtn.disabled = completed < totalRequired;
            }

            function showStep(stepIndex) {
                document.querySelectorAll('.upload-step').forEach((el, i) => {
                    el.classList.toggle('d-none', i !== stepIndex);
                });

                currentStep = stepIndex;
                prevBtn.disabled = currentStep === 0;

                if (currentStep === fileSteps.length - 1) {
                    nextBtn.innerHTML = '<i class="fas fa-check me-1"></i> Selesai';
                    nextBtn.classList.remove('btn-primary');
                    nextBtn.classList.add('btn-success');
                } else {
                    nextBtn.innerHTML = 'Selanjutnya <i class="fas fa-arrow-right ms-1"></i>';
                    nextBtn.classList.remove('btn-success');
                    nextBtn.classList.add('btn-primary');
                }
            }

            window.removeUploadedFile = function(id) {
                delete uploadedFiles[id];
                document.getElementById(id + 'Display').value = '';
                document.getElementById(id + 'Preview').innerHTML = oldFiles[id] ?
                    `<a href="/storage/${oldFiles[id]}" target="_blank" class="badge bg-primary me-2">Lihat File</a>` :
                    '';
                document.getElementById(id + 'Input').value = '';
                updateProgress();
            };

            function createSteps() {
                fileSteps.forEach((step, index) => {
                    // Create step container
                    const stepDiv = document.createElement('div');
                    stepDiv.className = `upload-step ${index === 0 ? '' : 'd-none'}`;
                    stepDiv.dataset.step = step.id;

                    // Create file preview if exists
                    const existingFile = oldFiles[step.id] ?
                        `<a href="/storage/${oldFiles[step.id]}" target="_blank" class="badge bg-primary me-2">
                    <i class="fas fa-file me-1"></i> Lihat File
                </a>` : '';

                    // Step HTML
                    stepDiv.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">
                        ${step.label} ${step.required ? '<span class="text-danger">*</span>' : ''}
                    </label>
                    <div class="input-group">
                        <input type="text" id="${step.id}Display" class="form-control" 
                               value="${oldFiles[step.id] ? oldFiles[step.id].split('/').pop() : ''}" readonly>
                        <button type="button" class="btn btn-outline-primary" id="${step.id}Btn">
                            <i class="fas fa-upload me-1"></i> ${oldFiles[step.id] ? 'Ganti' : 'Pilih'} File
                        </button>
                    </div>
                    <div class="mt-2" id="${step.id}Preview">${existingFile}</div>
                </div>
            `;

                    fileUploadSteps.appendChild(stepDiv);

                    // Create hidden file input
                    const fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.name = step.id;
                    fileInput.id = step.id + 'Input';
                    fileInput.classList.add('d-none');
                    hiddenInputs.appendChild(fileInput);

                    // Handle file selection
                    fileInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (!file) return;

                        uploadedFiles[step.id] = file;
                        document.getElementById(step.id + 'Display').value = file.name;

                        document.getElementById(step.id + 'Preview').innerHTML = `
                    <div class="alert alert-success p-2 mt-2 d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-check-circle me-2"></i>
                            ${file.name} (${formatFileSize(file.size)})
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                onclick="removeUploadedFile('${step.id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;

                        updateProgress();
                    });

                    // Trigger file input when button clicked
                    document.getElementById(step.id + 'Btn').addEventListener('click', () => {
                        fileInput.click();
                    });
                });
            }

            // Navigation handlers
            prevBtn.addEventListener('click', () => showStep(currentStep - 1));
            nextBtn.addEventListener('click', () => {
                if (currentStep < fileSteps.length - 1) {
                    showStep(currentStep + 1);
                }
            });

            // Initialize
            createSteps();
            updateProgress();
        })();
    </script>
@endsection
