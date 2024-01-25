@extends('layouts.app')

@section('content')

<!-- Main content -->
<div class="container">
    <div class="row">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="card card-primary">
                <div class="card-header">
                    <h5 class="text-center mt-2">Register Karyawan</h5>
                </div>
                @include('messages.alerts')
                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="card-body">

                        <fieldset>
                            <div class="form-group">
                                <label for="">Nama Awal</label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}"
                                    class="form-control">
                                @error('first_name')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Nama Akhir</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control">
                                @error('last_name')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="text" name="email" value="{{ old('email') }}" class="form-control">
                                @error('email')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="dob">Tanggal Lahir</label>
                                <input type="text" name="dob" id="dob" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Jenis Kelamin</label>
                                <select name="sex" class="form-control">
                                    <option hidden disabled selected value> -- Pilih Opsi -- </option>
                                    @if (old('sex') == 'Male')
                                    <option value="Male" selected>Laki-Laki</option>
                                    <option value="Female">Female</option>
                                    @elseif (old('sex') == 'Female')
                                    <option value="Male">Perempuan</option>
                                    <option value="Female" selected>Perempuan</option>
                                    @else
                                    <option value="Male">Laki-Laki</option>
                                    <option value="Female">Perempuan</option>
                                    @endif
                                </select>
                                @error('sex')
                                <div class="text-danger">
                                    Please select an valid option
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="join_date">Tanggal Bergabung</label>
                                <input type="text" name="join_date" id="join_date" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Jabatan</label>
                                    <select name="desg" class="form-control">
                                        <option hidden disabled selected value> -- Pilih Opsi -- </option>
                                        @foreach ($positions as $position)
                                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Department</label>
                                    <select name="departement_id" class="form-control">
                                        <option hidden disabled selected value> -- Pilih Opsi -- </option>
                                        <?php $conn = mysqli_connect("localhost","root","","absensi"); 
                                            $dt=mysqli_query($conn,"SELECT * FROM departments");
                                            while($dt2=mysqli_fetch_array($dt)) { echo"<option value=".$dt2['name'].">".$dt2['name']."
                                                </option>"; }?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Gaji</label>
                                <input type="text" name="salary" value="{{ old('salary') }}" class="form-control">
                                @error('salary')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Foto</label>
                                <input type="file" name="photo" class="form-control-file">
                                @error('photo')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" name="password" value="{{ old('password') }}"
                                    class="form-control">
                                @error('password')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation"
                                    value="{{ old('password_confirmation') }}" class="form-control">
                                @error('password_confirmation')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </fieldset>


                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-flat btn-primary"
                            style="width: 40%; font-size:1.3rem">Tambah</button>
                        <a href="{{ route('login') }}"><button type="button" class="btn btn-flat btn-primary"
                                style="width: 40%; background: orange;font-size:1.3rem">Batal</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<!-- /.content -->

<!-- /.content-wrapper -->

@endsection

@section('extra-js')
<script>
    $().ready(function() {
        if('{{ old('dob') }}') {
            const dob = moment('{{ old('dob') }}', 'DD-MM-YYYY');
            const join_date = moment('{{ old('join_date') }}', 'DD-MM-YYYY');
            console.log('{{ old('dob') }}')
            $('#dob').daterangepicker({
                "startDate": dob,
                "singleDatePicker": true,
                "showDropdowns": true,
                "locale": {
                    "format": "DD-MM-YYYY"
                }
            });
            $('#join_date').daterangepicker({
                "startDate": join_date,
                "singleDatePicker": true,
                "showDropdowns": true,
                "locale": {
                    "format": "DD-MM-YYYY"
                }
            });
        } else {
            $('#dob').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "locale": {
                    "format": "DD-MM-YYYY"
                }
            });
            $('#join_date').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "locale": {
                    "format": "DD-MM-YYYY"
                }
            });
        }
        
    });
</script>
@endsection

{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | Web Absensi</title>
    <style>
        :root {
            --background: #1a1a2e;
            --color: #ffffff;
            --primary-color: #0f3460;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            box-sizing: border-box;
            font-family: "poppins";
            background: var(--background);
            color: var(--color);
            letter-spacing: 1px;
            transition: background 0.2s ease;
            -webkit-transition: background 0.2s ease;
            -moz-transition: background 0.2s ease;
            -ms-transition: background 0.2s ease;
            -o-transition: background 0.2s ease;
        }

        a {
            text-decoration: none;
            color: var(--color);
        }

        h1 {
            font-size: 2.5rem;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .login-container {
            position: relative;
            width: 50.2rem;
        }

        .form-container {
            border: 1px solid hsla(0, 0%, 65%, 0.158);
            box-shadow: 0 0 36px 1px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            backdrop-filter: blur(20px);
            z-index: 99;
            padding: 2rem;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            -ms-border-radius: 10px;
            -o-border-radius: 10px;
        }

        .login-container form input {
            display: block;
            padding: 14.5px;
            width: 100%;
            margin: 2rem 0;
            color: var(--color);
            outline: none;
            background-color: #9191911f;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            letter-spacing: 0.8px;
            font-size: 15px;
            backdrop-filter: blur(15px);
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
        }

        .login-container form select {
            display: block;
            padding: 14.5px;
            width: 100%;
            margin: 2rem 0;
            color: var(--color);
            outline: none;
            background-color: #9191911f;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            letter-spacing: 0.8px;
            font-size: 15px;
            backdrop-filter: blur(15px);
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
        }

        .login-container form input:focus {
            box-shadow: 0 0 16px 1px rgba(0, 0, 0, 0.2);
            animation: wobble 0.3s ease-in;
            -webkit-animation: wobble 0.3s ease-in;
        }

        .login-container form button {
            background-color: var(--primary-color);
            color: var(--color);
            display: block;
            padding: 13px;
            border-radius: 5px;
            outline: none;
            font-size: 18px;
            letter-spacing: 1.5px;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            margin-bottom: 2rem;
            transition: all 0.1s ease-in-out;
            border: none;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
            -webkit-transition: all 0.1s ease-in-out;
            -moz-transition: all 0.1s ease-in-out;
            -ms-transition: all 0.1s ease-in-out;
            -o-transition: all 0.1s ease-in-out;
        }

        .login-container form button:hover {
            box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.15);
            transform: scale(1.02);
            -webkit-transform: scale(1.02);
            -moz-transform: scale(1.02);
            -ms-transform: scale(1.02);
            -o-transform: scale(1.02);
        }

        .circle {
            width: 8rem;
            height: 8rem;
            background: var(--primary-color);
            border-radius: 50%;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            -ms-border-radius: 50%;
            -o-border-radius: 50%;
            position: absolute;
        }

        .illustration {
            position: absolute;
            top: -14%;
            right: -2px;
            width: 90%;
        }

        .circle-one {
            top: 0;
            left: 0;
            z-index: -1;
            transform: translate(-45%, -45%);
            -webkit-transform: translate(-45%, -45%);
            -moz-transform: translate(-45%, -45%);
            -ms-transform: translate(-45%, -45%);
            -o-transform: translate(-45%, -45%);
        }

        .circle-two {
            bottom: 0;
            right: 0;
            z-index: -1;
            transform: translate(45%, 45%);
            -webkit-transform: translate(45%, 45%);
            -moz-transform: translate(45%, 45%);
            -ms-transform: translate(45%, 45%);
            -o-transform: translate(45%, 45%);
        }

        .register-forget {
            margin: 1rem 0;
            display: flex;
            justify-content: space-between;
        }

        .opacity {
            opacity: 0.6;
        }

        .theme-btn-container {
            position: absolute;
            left: 0;
            bottom: 2rem;
        }

        .theme-btn {
            cursor: pointer;
            transition: all 0.3s ease-in;
        }

        .theme-btn:hover {
            width: 40px !important;
        }

        @keyframes wobble {
            0% {
                transform: scale(1.025);
                -webkit-transform: scale(1.025);
                -moz-transform: scale(1.025);
                -ms-transform: scale(1.025);
                -o-transform: scale(1.025);
            }

            25% {
                transform: scale(1);
                -webkit-transform: scale(1);
                -moz-transform: scale(1);
                -ms-transform: scale(1);
                -o-transform: scale(1);
            }

            75% {
                transform: scale(1.025);
                -webkit-transform: scale(1.025);
                -moz-transform: scale(1.025);
                -ms-transform: scale(1.025);
                -o-transform: scale(1.025);
            }

            100% {
                transform: scale(1);
                -webkit-transform: scale(1);
                -moz-transform: scale(1);
                -ms-transform: scale(1);
                -o-transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <section class="container">
        <div class="login-container">
            <div class="circle circle-one"></div>
            <div class="form-container">
                <img
                    src="https://raw.githubusercontent.com/hicodersofficial/glassmorphism-login-form/master/assets/illustration.png"
                    alt="illustration" class="illustration" />
                <h1 class="opacity">Register</h1>
                <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <input type="text" placeholder="Nama Awal" name="first_name" value="{{ old('first_name') }}"
                            class="form-control">
                        @error('first_name')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Nama Akhir" name="last_name" value="{{ old('last_name') }}"
                            class="form-control">
                        @error('last_name')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Email" name="email" value="{{ old('email') }}"
                            class="form-control">
                        @error('email')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Tanggal Lahir" name="dob" id="dob" class="form-control">
                    </div>
                    <div class="form-group">
                        <select name="sex" class="form-control">
                            <option hidden disabled selected value> -- Jenis Kelamin -- </option>
                            @if (old('sex') == 'Male')
                            <option value="Male" selected>Laki-Laki</option>
                            <option value="Female">Female</option>
                            @elseif (old('sex') == 'Female')
                            <option value="Male">Perempuan</option>
                            <option value="Female" selected>Perempuan</option>
                            @else
                            <option value="Male">Laki-Laki</option>
                            <option value="Female">Perempuan</option>
                            @endif
                        </select>
                        @error('sex')
                        <div class="text-danger">
                            Please select an valid option
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Tanggal Bergabung" name="join_date" id="join_date"
                            class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <select name="position_id" class="form-control">
                                <option hidden disabled selected value> -- Pilih Jabatan -- </option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="departement_id" class="form-control">
                                <option hidden disabled selected value> -- Pilih Departement -- </option>
                                @foreach ($departements as $departement)
                                <option value="{{ $departement->id }}">{{ $departement->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="file" name="photo" class="form-control-file">
                        @error('photo')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder="Password" name="password" value="{{ old('password') }}"
                            class="form-control">
                        @error('password')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder="Konfirmasi Password" name="password_confirmation"
                            value="{{ old('password_confirmation') }}" class="form-control">
                        @error('password_confirmation')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button class="opacity" type="submit">SUBMIT</button>
                </form>
            </div>
            <div class="circle circle-two"></div>
        </div>
        <div class="theme-btn-container"></div>
    </section>

    <script>
        $().ready(function() {
            if('{{ old('dob') }}') {
                const dob = moment('{{ old('dob') }}', 'DD-MM-YYYY');
                const join_date = moment('{{ old('join_date') }}', 'DD-MM-YYYY');
                console.log('{{ old('dob') }}')
                $('#dob').daterangepicker({
                    "startDate": dob,
                    "singleDatePicker": true,
                    "showDropdowns": true,
                    "locale": {
                        "format": "DD-MM-YYYY"
                    }
                });
                $('#join_date').daterangepicker({
                    "startDate": join_date,
                    "singleDatePicker": true,
                    "showDropdowns": true,
                    "locale": {
                        "format": "DD-MM-YYYY"
                    }
                });
            } else {
                $('#dob').daterangepicker({
                    "singleDatePicker": true,
                    "showDropdowns": true,
                    "locale": {
                        "format": "DD-MM-YYYY"
                    }
                });
                $('#join_date').daterangepicker({
                    "singleDatePicker": true,
                    "showDropdowns": true,
                    "locale": {
                        "format": "DD-MM-YYYY"
                    }
                });
            }
            
        });
    </script>
    <script>
        const themes = [
    {
        background: "#1A1A2E",
        color: "#FFFFFF",
        primaryColor: "#0F3460"
    },
    {
        background: "#461220",
        color: "#FFFFFF",
        primaryColor: "#E94560"
    },
    {
        background: "#192A51",
        color: "#FFFFFF",
        primaryColor: "#967AA1"
    },
    {
        background: "#F7B267",
        color: "#000000",
        primaryColor: "#F4845F"
    },
    {
        background: "#F25F5C",
        color: "#000000",
        primaryColor: "#642B36"
    },
    {
        background: "#231F20",
        color: "#FFF",
        primaryColor: "#BB4430"
    }
];

const setTheme = (theme) => {
    const root = document.querySelector(":root");
    root.style.setProperty("--background", theme.background);
    root.style.setProperty("--color", theme.color);
    root.style.setProperty("--primary-color", theme.primaryColor);
    root.style.setProperty("--glass-color", theme.glassColor);
};

const displayThemeButtons = () => {
    const btnContainer = document.querySelector(".theme-btn-container");
    themes.forEach((theme) => {
        const div = document.createElement("div");
        div.className = "theme-btn";
        div.style.cssText = `background: ${theme.background}; width: 25px; height: 25px`;
        btnContainer.appendChild(div);
        div.addEventListener("click", () => setTheme(theme));
    });
};

displayThemeButtons();

    </script>
</body>

</html> --}}