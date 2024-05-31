@if (isset($alert))
    <div class="alert alert-warning alert-dismissible fade show fixed-alert" role="alert">
        <h4 class="alert-heading">{{ $status }}</h4>
        <hr>
        <p><i class="bi bi-exclamation-triangle me-1"></i> {{ $message }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif