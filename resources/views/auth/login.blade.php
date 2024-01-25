{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Alamat E-Mail')
                                }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password')
                                }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{
                                        old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Ingat Saya') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                                <a class="btn btn-link" href="{{ route('register') }}">
                                    {{ __('Register Karyawan') }}
                                </a>
                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Lupa Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | Web Absensi</title>

    <style>
        @media only screen and (max-width: 600px) {
            body {
                margin: 0;
                margin-top: 50px;
                box-sizing: border-box;
                font-family: "poppins";
                background-image: url('bg-login-mobile.png');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                color: var(--color);
                letter-spacing: 1px;
                transition: background 0.2s ease;
                -webkit-transition: background 0.2s ease;
                -moz-transition: background 0.2s ease;
                -ms-transition: background 0.2s ease;
                -o-transition: background 0.2s ease;
            }

            .circle {
                display: none;
            }
        }

        @media only screen and (min-width: 600px) {
            body {
                margin: 0;
                margin-top: 50px;
                box-sizing: border-box;
                font-family: "poppins";
                background-image: url('bg-login-mobile.png');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                color: var(--color);
                letter-spacing: 1px;
                transition: background 0.2s ease;
                -webkit-transition: background 0.2s ease;
                -moz-transition: background 0.2s ease;
                -ms-transition: background 0.2s ease;
                -o-transition: background 0.2s ease;
            }

            .circle {
                display: none;
            }
        }


        /* Large devices (laptops/desktops, 992px and up) */
        @media only screen and (min-width: 992px) {
            body {
                margin: 0;
                box-sizing: border-box;
                font-family: "poppins";
                background-image: url('bg-login.png');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                color: var(--color);
                letter-spacing: 1px;
                transition: background 0.2s ease;
                -webkit-transition: background 0.2s ease;
                -moz-transition: background 0.2s ease;
                -ms-transition: background 0.2s ease;
                -o-transition: background 0.2s ease;
            }

            .circle {
                display: block;
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

            .circle-one {
                display: block;
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
                display: block;
                bottom: 0;
                right: 0;
                z-index: -1;
                transform: translate(45%, 45%);
                -webkit-transform: translate(45%, 45%);
                -moz-transform: translate(45%, 45%);
                -ms-transform: translate(45%, 45%);
                -o-transform: translate(45%, 45%);
            }
        }

        /* Extra large devices (large laptops and desktops, 1200px and up) */
        @media only screen and (min-width: 1200px) {
            body {
                margin: 0;
                box-sizing: border-box;
                font-family: "poppins";
                background-image: url('bg-login.png');
                background-size: cover;
                background-repeat: no-repeat;
                color: var(--color);
                letter-spacing: 1px;
                transition: background 0.2s ease;
                -webkit-transition: background 0.2s ease;
                -moz-transition: background 0.2s ease;
                -ms-transition: background 0.2s ease;
                -o-transition: background 0.2s ease;
            }

            .circle {
                display: block;
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

            .circle-one {
                display: block;
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
                display: block;
                bottom: 0;
                right: 0;
                z-index: -1;
                transform: translate(45%, 45%);
                -webkit-transform: translate(45%, 45%);
                -moz-transform: translate(45%, 45%);
                -ms-transform: translate(45%, 45%);
                -o-transform: translate(45%, 45%);
            }
        }

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

        ::placeholder {
            color: white;
            opacity: 0.5;
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
            height: 100vh;
        }

        .login-container {
            position: relative;
            width: 22.2rem;
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

        .illustration {
            position: absolute;
            top: -14%;
            right: -2px;
            width: 90%;
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
                <img src="https://raw.githubusercontent.com/hicodersofficial/glassmorphism-login-form/master/assets/illustration.png"
                    alt="illustration" class="illustration" />
                <h1 class="opacity">LOGIN</h1>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="text" name="username" placeholder="Email" required />
                    <input type="password" name="password" placeholder="Password" required />
                    <button class="opacity" type="submit">SUBMIT</button>
                </form>
            </div>
            <div class="circle circle-two"></div>
        </div>
        <div class="theme-btn-container"></div>
    </section>


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

</html>