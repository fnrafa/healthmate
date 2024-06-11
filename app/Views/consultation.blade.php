<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Consultation | HealthMate</title>
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
            @if(isset($consult))
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body row">
                            <div class="col-10">
                                <h5 class="card-title">{{$consult->status}} #{{$consult->id}} </h5>
                                <p class="text-secondary">
                                    <span class="text-dark">Note : </span>{{$consult->notes}}
                                </p>
                                <p class="text-secondary">
                                    <span class="text-dark">Doctor : </span>{{$consult->doctor->name}}
                                </p>
                                <p class="text-secondary">
                                    <span class="text-dark">Specialization : </span>{{(isset($consult->specialization->name))? $consult->specialization->name :''}}
                                </p>
                            </div>
                            <div class="col-2">
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
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{$consult->doctor->name}}</h5>
                        </div>
                        <div class="card-body">
                            <div id="chatBox" class="mb-3 pt-1">
                                <div class="overflow-y-scroll" id="messageContainer" style="max-height: 51vh">
                                    @foreach($messages as $message)
                                        <div id="message-{{$message->id}}"
                                             class="fs-5 border border-secondary mt-2 p-2 pt-1 pb-1 {{ $message->sender_id == auth()->id ? 'text-end bg-primary-light message-bubble-right' : 'text-start bg-white message-bubble-left' }}">
                                            <div>{{ $message->message }}</div>
                                            @if(isset($message->media))
                                                <div><img src="{{ $message->media }}" class="img-fluid"
                                                          alt="Uploaded Image"></div>
                                            @endif
                                            <div class="text-secondary fs-6">
                                                @if($message->sender_id == auth()->id)
                                                    <span>{{ $message->created_at }}</span>
                                                    <span>{{ $message->is_read ? '✓✓' : '✓' }}</span>
                                                @else
                                                    <span>{{ $message->is_read ? '✓✓' : '✓' }}</span>
                                                    <span>{{ $message->created_at }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <form id="chatForm">
                                <div class="input-group">
                                    <input type="hidden" id="consultation_id" value="{{$consult->id}}">
                                    <label for="fileInput" class="input-group-text btn btn-outline-primary">Choose
                                        File</label>
                                    <input type="file" id="fileInput" class="custom-file-input" style="display:none;">
                                    <label for="message"></label>
                                    <input type="text" id="message" class="form-control" placeholder="Type a message">
                                    <button type="button" id="sendButton" class="btn btn-outline-primary">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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


