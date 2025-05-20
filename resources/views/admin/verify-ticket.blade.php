@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <div id="verificationResult" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 mb-0">Memverifikasi tiket...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const code = '{{ $code }}';
    const resultDiv = document.getElementById('verificationResult');

    fetch(`/api/verify-ticket/${code}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                resultDiv.innerHTML = `
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill fs-1"></i>
                        <h4 class="mt-3 mb-2">Tiket Valid</h4>
                        <p class="mb-0">${data.message}</p>
                        <p class="mt-2 text-muted">Diverifikasi pada ${data.data.verified_at}</p>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="text-danger">
                        <i class="bi bi-x-circle-fill fs-1"></i>
                        <h4 class="mt-3 mb-2">Tiket Tidak Valid</h4>
                        <p class="mb-0">${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            resultDiv.innerHTML = `
                <div class="text-danger">
                    <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                    <h4 class="mt-3 mb-2">Terjadi Kesalahan</h4>
                    <p class="mb-0">Gagal memverifikasi tiket</p>
                </div>
            `;
        });
});
</script>
@endpush
@endsection
