<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Dashboard | HealthMate</title>
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
@include('partials.sidebar-patient')
<main id="main" class="main">

    <section class="section">
        <div class="row align-items-top">
            <div class="col-lg-8">
                @if(isset($consult))
                    <div class="card">
                        <div class="card-body row">
                            <div class="col-11">
                                <h5 class="card-title">{{$consult->status}} #{{$consult->id}} </h5>
                                <p class="text-secondary">
                                    <span class="text-dark">Note : </span>{{$consult->notes}}
                                </p>
                                <p class="text-secondary">
                                    <span class="text-dark">Doctor : </span>{{(isset($consult->doctor->name))?$consult->doctor->name:""}}
                                </p>
                                <p class="text-secondary">
                                    <span class="text-dark">Specialization : </span>{{(isset($consult->specialization->name))?$consult->specialization->name:''}}
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
                @endif
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Consultation</h5>
                        <p>You can only add a consultation or make a request if there is no consultation currently in
                            progress.</p>
                        <a class="btn btn-primary {{ $statusCheck ? '' : 'disabled' }}" data-bs-toggle="modal"
                           data-bs-target="#requestConsult">
                            Request a Consultation<i class="bi bi-plus"></i>
                        </a>

                        <div class="modal fade" id="requestConsult" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="requestConsultForm">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Request a Consultation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" value="{{auth()->id}}" id="user_id">
                                            <div class="mb-3">
                                                <label for="type" class="form-label">Consultation Type</label>
                                                <select id="type" class="form-select" onchange="toggleFields()">
                                                    <option value="free">Free</option>
                                                    <option value="specialization">Specialization</option>
                                                    <option value="specific_doctor">Specific Doctor</option>
                                                </select>
                                            </div>
                                            <div class="mb-3" id="specializationField" style="display:none;">
                                                <label for="specialization_id" class="form-label">Specialization</label>
                                                <select id="specialization_id" class="form-select">
                                                    @foreach($specializations as $specialization)
                                                        <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3" id="doctorField" style="display:none;">
                                                <label for="doctor_id" class="form-label">Doctor</label>
                                                <select id="doctor_id" class="form-select">
                                                    @foreach($doctors as $doctor)
                                                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="notes" class="form-label">Notes</label>
                                                <textarea id="notes" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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


