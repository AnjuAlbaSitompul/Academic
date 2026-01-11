@extends('layouts.pages.login')

@section('content')
    <!-- PAGE -->
    <div class="page">
        <div class="">
            <div class="container-login100">
                <div class="wrap-login100 p-6">
                    <form class="login100-form validate-form" id="loginForm">
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
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response)
                },
                error: function(xhr) {
                    alert('Login failed: ' + xhr.responseJSON.message);
                }
            });
        });
    </script>
@endsection
