@extends('dashboard')

@section('konten')
    <div class="content-wrapper">
        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <h1 class="m-0"><i class="fas fa-paper-plane"></i> Buat Pengajuan</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Flash message -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data"
                            id="pengajuanForm">
                            @csrf

                            <div class="row">
                                <!-- Basic information fields -->
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Nama Pengadaan</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}" placeholder="Contoh: Permintaan Dana Buku" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="desc" class="form-label">Deskripsi pengadaan</label>
                                    <input type="text" name="desc"
                                        class="form-control @error('desc') is-invalid @enderror" value="{{ old('desc') }}"
                                        placeholder="Contoh: Dana pembelian buku pelajaran" required>
                                    @error('desc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nilai_kontrak" class="form-label">Nilai Kontrak/Pengadaan</label>
                                    <input type="text" name="nilai_kontrak"
                                        class="form-control @error('nilai_kontrak') is-invalid @enderror"
                                        value="{{ old('nilai_kontrak') }}" required>
                                    @error('nilai_kontrak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nilai_ajuan" class="form-label">Nilai Pengajuan Pembayaran</label>
                                    <input type="text" name="nilai_ajuan"
                                        class="form-control @error('nilai_ajuan') is-invalid @enderror"
                                        value="{{ old('nilai_ajuan') }}" required>
                                    @error('nilai_ajuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="Tp" class="form-label">Type Pengadaan</label>
                                    <select name="type_pengadaan" id="Tp" class="form-control">
                                        <option value="SWAKELOLA">SWAKELOLA</option>
                                        <option value="SUPLIER">SUPLIER</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="T" class="form-label">Type</label>
                                    <select name="type" id="T" class="form-control">
                                        <option value="SUPLIER">SUPLIER</option>
                                        <option value="VENDOR">VENDOR</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="rab" class="form-label">Tanggal Pengadaan</label>
                                    <input type="date" name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
                                        value="{{ old('tanggal') }}" required>
                                    @error('rab')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- File Upload Section -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>Upload Dokumen</h5>
                                    <small class="text-muted">Silakan upload dokumen satu per satu</small>
                                    <div class="progress mt-2" style="height: 10px;">
                                        <div class="progress-bar" id="uploadProgress" role="progressbar" style="width: 0%">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="fileUploadSteps">
                                        <!-- Steps will be added dynamically by JavaScript -->
                                    </div>

                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-secondary" id="prevStepBtn" disabled>
                                            <i class="fas fa-arrow-left"></i> Sebelumnya
                                        </button>
                                        <button type="button" class="btn btn-primary" id="nextStepBtn">
                                            Selanjutnya <i class="fas fa-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden file inputs -->
                            {{-- Hidden file inputs --}}
                            @php
                                $fileFields = [
                                    'nota',
                                    'rab',
                                    'kwitansi',
                                    'bukti_nota',
                                    'berita_acara',
                                    'serah_terima',
                                    'pembayaran',
                                    'jaminan_garansi',
                                    'jaminan_pelaksanaan',
                                    'keputusan',
                                    'surat_kontrak',
                                    'surat_perintah',
                                    'dokumentasi',
                                    'faktur_pajak',
                                    'spp',
                                    'spm',
                                    'ssp',
                                    'sp2d',
                                    'lain-lain',
                                ];
                            @endphp

                            @foreach ($fileFields as $field)
                                <input type="file" id="{{ $field }}Input" name="{{ $field }}"
                                    class="d-none">
                            @endforeach


                            <!-- Form submission buttons -->
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    <i class="fas fa-save"></i> Simpan Pengajuan
                                </button>
                            </div>
                        </form>



                        <style>
                            .upload-step {
                                transition: all 0.3s ease;
                            }

                            .progress {
                                background-color: #e9ecef;
                            }

                            .file-preview .alert {
                                margin-bottom: 0;
                            }

                            .step-file-display {
                                background-color: #fff;
                            }
                        </style>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <!-- JS Preview -->
    @push('scripts')
        <script>
            const uploadInput = document.getElementById('upload_1');
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');
            const previewText = document.getElementById('previewText');

            uploadInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;

                const fileType = file.type;
                if (fileType.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                        previewText.innerText = '';
                        previewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImage.style.display = 'none';
                    previewText.innerText = file.name;
                    previewContainer.style.display = 'block';
                }
            });
        </script>
    @endpush
    <script>
        (function() {
            const fileSteps = [{
                    id: 'nota',
                    label: 'Nota Dinas dan Disposisi KPA',
                    required: true,
                    description: 'Upload Nota Dinas dan Disposisi KPA dalam format PDF'
                },
                {
                    id: 'rab',
                    label: 'RAB',
                    required: true,
                    description: 'Upload Rencana Anggaran Biaya (RAB)'
                },
                {
                    id: 'kwitansi',
                    label: 'Kwitansi Bukti Pembayaran',
                    required: true,
                    description: 'Upload kwitansi sebagai bukti pembayaran'
                },
                {
                    id: 'bukti_nota',
                    label: 'Faktur atau Nota Bukti Pembelian',
                    required: true,
                    description: 'Upload faktur atau nota pembelian'
                },
                {
                    id: 'berita_acara',
                    label: 'Berita Acara Penyelesaian Pekerjaan',
                    required: true,
                    description: 'Upload berita acara penyelesaian pekerjaan'
                },
                {
                    id: 'serah_terima',
                    label: 'Berita Acara Serah Terima Pekerjaan',
                    required: true,
                    description: 'Upload berita acara serah terima pekerjaan'
                },
                {
                    id: 'pembayaran',
                    label: 'Berita Acara Pembayaran',
                    required: true,
                    description: 'Upload berita acara pembayaran (jika ada)'
                },
                {
                    id: 'jaminan_garansi',
                    label: 'Jaminan Bank Garansi Uang Muka',
                    required: true,
                    description: 'Upload jaminan bank garansi uang muka (jika ada)'
                },
                {
                    id: 'jaminan_pelaksanaan',
                    label: 'Jaminan Bank Garansi Pelaksanaan',
                    required: true,
                    description: 'Upload jaminan bank garansi pelaksanaan (jika ada)'
                },
                {
                    id: 'keputusan',
                    label: 'Keputusan Penetapan Pemenang',
                    required: true,
                    description: 'Upload keputusan penetapan pemenang (jika ada)'
                },
                {
                    id: 'surat_kontrak',
                    label: 'Surat Kontrak atau Surat Pesanan',
                    required: true,
                    description: 'Upload surat kontrak atau surat pesanan'
                },
                {
                    id: 'surat_perintah',
                    label: 'Surat Perintah Kerja',
                    required: true,
                    description: 'Upload surat perintah kerja (jika ada)'
                },
                {
                    id: 'dokumentasi',
                    label: 'Dokumentasi Kegiatan/Pembelian',
                    required: true,
                    description: 'Upload dokumentasi kegiatan atau pembelian (jika ada)'
                },
                {
                    id: 'faktur_pajak',
                    label: 'Faktur Pajak',
                    required: true,
                    description: 'Upload faktur pajak (jika ada)'
                },
                {
                    id: 'spp',
                    label: 'SPP',
                    required: true,
                    description: 'Upload SPP (jika ada)'
                },
                {
                    id: 'spm',
                    label: 'SPM',
                    required: true,
                    description: 'Upload SPM (jika ada)'
                },
                {
                    id: 'ssp',
                    label: 'SSP',
                    required: true,
                    description: 'Upload SSP (jika ada)'
                },
                {
                    id: 'sp2d',
                    label: 'SP2D',
                    required: true,
                    description: 'Upload SP2D (jika ada)'
                },
                {
                    id: 'lain-lain',
                    label: 'Dokumen lainnya',
                    required: true,
                    description: 'Upload dokumen lain yang diperlukan'
                },
            ];

            const fileUploadSteps = document.getElementById('fileUploadSteps');
            const prevStepBtn = document.getElementById('prevStepBtn');
            const nextStepBtn = document.getElementById('nextStepBtn');
            const submitBtn = document.getElementById('submitBtn');
            const uploadProgress = document.getElementById('uploadProgress');

            let currentStep = 0;
            const uploadedFiles = {};

            function initSteps() {
                fileUploadSteps.innerHTML = '';

                fileSteps.forEach((step, index) => {
                    const stepElement = document.createElement('div');
                    stepElement.className = 'upload-step ' + (index === 0 ? 'active' : 'd-none');
                    stepElement.dataset.stepIndex = index;

                    stepElement.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">${step.label} ${step.required ? '<span class="text-primary">*</span>' : ''}</label>
                    <p class="text-muted small">${step.description}</p>
                    <div class="input-group">
                        <input type="text" class="form-control step-file-display" id="${step.id}Display" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="${step.id}Btn">Pilih File</button>
                    </div>
                    <div class="mt-2 file-preview" id="${step.id}Preview"></div>
                </div>
            `;
                    fileUploadSteps.appendChild(stepElement);

                    const inputEl = document.getElementById(step.id + 'Input');
                    const btn = document.getElementById(step.id + 'Btn');

                    if (inputEl && btn) {
                        btn.addEventListener('click', () => inputEl.click());
                        inputEl.addEventListener('change', (e) => handleFileSelect(e, step.id));
                    }
                });

                updateProgress();
                updateButtons();
            }

            function handleFileSelect(event, stepId) {
                const file = event.target.files[0];
                if (!file) return;

                uploadedFiles[stepId] = file;

                document.getElementById(stepId + 'Display').value = file.name;

                const previewDiv = document.getElementById(stepId + 'Preview');
                previewDiv.innerHTML = `
            <div class="alert alert-success p-2">
                <i class="fas fa-check-circle me-2"></i>
                ${file.name} (${formatFileSize(file.size)})
                <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="window.removeUploadedFile('${stepId}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

                updateProgress();
                updateButtons();
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function updateProgress() {
                const requiredSteps = fileSteps.filter(step => step.required);
                const completed = requiredSteps.filter(step => uploadedFiles[step.id]);

                const progress = (completed.length / requiredSteps.length) * 100;
                uploadProgress.style.width = progress + '%';
                submitBtn.disabled = completed.length !== requiredSteps.length;
            }

            function updateButtons() {
                prevStepBtn.disabled = currentStep === 0;

                if (currentStep === fileSteps.length - 1) {
                    nextStepBtn.innerHTML = 'Selesai <i class="fas fa-check"></i>';
                    nextStepBtn.classList.replace('btn-primary', 'btn-success');
                } else {
                    nextStepBtn.innerHTML = 'Selanjutnya <i class="fas fa-arrow-right"></i>';
                    nextStepBtn.classList.replace('btn-success', 'btn-primary');
                }
            }

            function showStep(index) {
                document.querySelectorAll('.upload-step').forEach(el => {
                    el.classList.add('d-none');
                    el.classList.remove('active');
                });

                const current = document.querySelector(`.upload-step[data-step-index="${index}"]`);
                if (current) {
                    current.classList.remove('d-none');
                    current.classList.add('active');
                }

                currentStep = index;
                updateButtons();
            }

            window.removeUploadedFile = function(stepId) {
                delete uploadedFiles[stepId];
                document.getElementById(stepId + 'Input').value = '';
                document.getElementById(stepId + 'Display').value = '';
                document.getElementById(stepId + 'Preview').innerHTML = '';

                updateProgress();
                updateButtons();
            };

            prevStepBtn.addEventListener('click', () => {
                if (currentStep > 0) showStep(currentStep - 1);
            });

            nextStepBtn.addEventListener('click', () => {
                if (currentStep < fileSteps.length - 1) {
                    showStep(currentStep + 1);
                }
            });

            initSteps();
        })();
    </script>
@endsection
