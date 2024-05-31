<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Admin | HealthMate</title>
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
@include('partials.header-admin')
@include('partials.sidebar-admin')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Users Table</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a class="btn btn-success mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#addUser">Add New
                            User <i class="bi bi-plus"></i></a>
                        @if (isset($error))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $error }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        @endif
                        @if (isset($errors))
                            @foreach ($errors as $field => $messages)
                                @foreach ($messages as $message)
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ $message }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                    </div>
                                @endforeach
                            @endforeach
                        @endif
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($users))
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->role}}</td>
                                        <td>
                                            <div class="d-flex justify-content-sm-center">
                                                <button class="btn btn-primary me-2" data-bs-toggle="modal"
                                                        data-bs-target="#editUser{{$user->id}}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <form action="/delete-user" method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    <input type="hidden" name="id" value="{{$user->id}}">
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="editUser{{$user->id}}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="row g-3 needs-validation" action="/update-user"
                                                          method="POST" novalidate>
                                                        <input type="hidden" name="id" value="{{$user->id}}">
                                                        <div class="col-12 mb-3">
                                                            <label for="yourName" class="form-label">Your Name</label>
                                                            <input type="text" name="name" class="form-control"
                                                                   id="yourName"
                                                                   value="{{$user->name}}" required>
                                                            <div class="invalid-feedback">Please, enter your name!</div>
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <label for="userRole" class="form-label">Role</label>
                                                            <select name="role" class="form-control" id="userRole"
                                                                    required>
                                                                <option value="{{$user->role}}"
                                                                        selected>{{$user->role}}</option>
                                                                <option value="patient" selected>Patient</option>
                                                                <option value="doctor">Doctor</option>
                                                                <option value="hospital">Hospital</option>
                                                                <option value="admin">Admin</option>
                                                            </select>
                                                            <div class="invalid-feedback">Please select a role.</div>
                                                        </div>
                                                        <div class="col-12 mt-3">
                                                            <button class="btn btn-primary w-100" type="submit">Insert
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
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
<div class="modal fade" id="addUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Insert User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" action="/add-user" method="POST" novalidate>
                    <div class="col-12">
                        <label for="yourName" class="form-label">Your Name</label>
                        <input type="text" name="name" class="form-control" id="yourName"
                               value="{{ old('name') }}" required>
                        <div class="invalid-feedback">Please, enter your name!</div>
                    </div>
                    <div class="col-12">
                        <label for="yourEmail" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="yourEmail"
                               value="{{ old('email') }}" required>
                        <div class="invalid-feedback">Please enter your email.</div>
                    </div>
                    <div class="col-12">
                        <label for="userRole" class="form-label">Role</label>
                        <select name="role" class="form-control" id="userRole" required>
                            <option value="patient" selected>Patient</option>
                            <option value="doctor">Doctor</option>
                            <option value="hospital">Hospital</option>
                            <option value="admin">Admin</option>
                        </select>
                        <div class="invalid-feedback">Please select a role.</div>
                    </div>
                    <div class="col-12">
                        <label for="yourPassword" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="yourPassword"
                               required>
                        <div class="invalid-feedback">Please enter your password!</div>
                    </div>
                    <div class="col-12">
                        <label for="yourRepeatPassword" class="form-label">Repeat Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               id="yourRepeatPassword"
                               required>
                        <div class="invalid-feedback">Please re-enter your password!</div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit">Insert</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
</body>
</html>


