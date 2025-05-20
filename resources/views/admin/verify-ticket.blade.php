@extends('layouts.landing')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div id="result" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p>Memverifikasi tiket...</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get ticket code from URL
    const code = window.location.pathname.split('/').pop();

    // Verify ticket
    fetch(`/verify/ticket/${code}`)
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('result');

            if (data.status === 'success') {
                resultDiv.innerHTML = `
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill fs-1"></i>
                        <h4 class="mt-3">Tiket Valid</h4>
                        <p class="mb-0">${data.data.package_name}</p>
                        <p class="mb-0">Atas nama: ${data.data.user_name}</p>
                        <p class="text-muted">Diverifikasi pada ${data.data.verified_at}</p>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="text-danger">
                        <i class="bi bi-x-circle-fill fs-1"></i>
                        <h4 class="mt-3">Tiket Tidak Valid</h4>
                        <p>${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('result').innerHTML = `
                <div class="text-danger">
                    <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                    <h4 class="mt-3">Terjadi Kesalahan</h4>
                    <p>Gagal memverifikasi tiket</p>
                </div>
            `;
        });
});
</script>
@endpush
@endsection
