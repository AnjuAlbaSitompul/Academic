@extends('layouts.pages.login')

@section('content')
    <!-- PAGE -->
    <div class="page">
        <div class="">
            <div class="container-login100">
                <div class="wrap-login100 p-6">
                    <form class="login100-form validate-form" id="loginForm" method="POST" action="/login">
                        @csrf
                        <span class="login100-form-title pb-5">
                            Aplikasi Predikisi Prestasi Akademik Siswa
                        </span>
                        <div class="panel panel-primary">
                            <div class="tab-menu-heading">
                                <div class="tabs-menu1">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs">
                                        <li class="mx-0"><a href="#tab5" class="active" data-bs-toggle="tab">Email</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body p-0 pt-5">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab5">
                                        <div class="wrap-input100 validate-input input-group"
                                            data-bs-validate="Valid email is required: ex@abc.xyz">
                                            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                                <i class="zmdi zmdi-email text-muted" aria-hidden="true"></i>
                                            </a>
                                            <input class="input100 border-start-0 form-control ms-0" name="email"
                                                type="email" placeholder="Email">
                                        </div>
                                        <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                                <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                            </a>
                                            <input class="input100 border-start-0 form-control ms-0" name="password"
                                                type="password" placeholder="Password">
                                        </div>
                                        <div class="container-login100-form-btn">
                                            <input type="submit" class="login100-form-btn btn-primary" value="Login">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '/login',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    window.location.href = response.redirect || '/dashboard';
                },
                error: function(xhr) {
                    if (xhr.status === 419) {
                        alert('Sesi kedaluwarsa (CSRF mismatch). Silakan refresh halaman lalu coba login lagi.');
                        location.reload();
                        return;
                    }

                    var message = 'Login gagal. Silakan coba lagi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        message = Object.values(errors).flat().join('\n');
                    }
                    alert(message);
                }
            });
        });
    </script>
@endsection
