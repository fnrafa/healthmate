<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>History | HealthMate</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
          rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
@include('partials.alert')
@include('partials.header')
@include('partials.sidebar-doctor')
<main id="main" class="main">
    <section class="section">
        <div class="row align-items-top">
            @if(isset($consults))
                @foreach($consults as $consult)
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body row">
                                <div class="col-11">
                                    <h5 class="card-title">{{$consult->status}} #{{$consult->id}} </h5>
                                    <p class="text-secondary">
                                        <span class="text-dark">Note : </span>{{$consult->notes}}
                                    </p>
                                    <p class="text-secondary">
                                        <span class="text-dark">Patient : </span>{{$consult->patient->name}}
                                    </p>
                                </div>
                                <div class="col-1">
                                    <h2 class="mt-4 pe-3"><i class="bi bi-chat-dots text-secondary"></i></h2>
                                    @if($consult->status == 'completed')
                                        <h2 class="mt-4 pe-3"><i class="bi bi-check-circle text-success"></i></h2>
                                    @elseif($consult->status == 'requested')
                                        <h2 class="mt-4 pe-3"><i class="bi bi-exclamation-circle text-warning"></i></h2>
                                    @elseif($consult->status == 'referred')
                                        <h2 class="mt-4 pe-3"><i class="bi bi-arrow-right-circle text-primary"></i></h2>
                                    @elseif($consult->status == 'in_progress')
                                        <h2 class="mt-4 pe-3"><i class="bi bi-hourglass-split text-danger"></i></h2>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
</main>

<footer id="footer" class="footer">
    <div class="copyright">
        &copy; Copyright <strong><span>HealthMate</span></strong>. All Rights Reserved
    </div>
</footer>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/socket.js" data-id="{{auth()->id}}"></script>
</body>
</html>


