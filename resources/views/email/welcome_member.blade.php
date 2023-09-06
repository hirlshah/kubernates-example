<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{__('Welcome to'). config('app.name')}}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;800;900&display=swap">
    <style>
        html,
        body {
            font-family: 'Rubik', sans-serif;
            margin: 0 auto;
            padding: 0;
            height: 100%;
            width: 100%;
            background: #ffffff;
        }

        h6,
        .h6,
        h5,
        .h5,
        h4,
        .h4,
        h3,
        .h3,
        h2,
        .h2,
        h1,
        .h1 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        h1,
        .h1 {
            font-size: calc(1.375rem + 1.5vw);
        }

        h2,
        .h2 {
            font-size: calc(1.325rem + 0.9vw);
        }

        h3,
        .h3 {
            font-size: calc(1.3rem + 0.6vw);
        }

        h4,
        .h4 {
            font-size: calc(1.275rem + 0.3vw);
        }

        h5,
        .h5 {
            font-size: 1.25rem;
        }

        h6,
        .h6 {
            font-size: 1rem;
        }

        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        div[style*="margin: 16px 0"] {
            margin: 0;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0 auto;
        }

        img {
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
        }

        svg {
            max-width: 100%;
        }

        a {
            text-decoration: none;
        }

        p {
            font-size: 16px;
            line-height: 2;
            margin-bottom: 32px;
            margin-top: 0;
        }

        .btn {
            padding: 16px 40px;
            font-weight: 500;
        }

        .btn.btn-primary {
            border-radius: 30px;
            background: #56B2FF;
            color: #ffffff;
        }

        .btn.btn-secondary {
            border-radius: 30px;
            background: #ffffff;
            color: #323232;
        }
        .bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        @media only screen and (max-width: 600px) {
            .btn {
                padding: 10px 10px;
            }
            .bottom {
                flex-direction: column;
                gap: 2rem;
            }
        }

        /* @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
            .email-container {
                min-width: 320px;
            }
        }

        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            .email-container {
                min-width: 375px;
            }
        }

        @media only screen and (min-device-width: 414px) {
            .email-container {
                min-width: 414px;
            }
        } */
    </style>
</head>

<body>
    <div style="width: 100%; background-color: #323232;">
        <div class="email-container" style="max-width: 1240px; padding: 0 30px; margin: 0 auto; padding: 30px 30px;">
            <table width="100%">
                <tr>
                    <td style="height: 20vh; width: 40%;">
                        <div style="margin-bottom: 25px">
                            <img width="200" src="{{ asset(config('app.rankup.company_logo_path')) }}">
                        </div>
                        <h3 style="color: #56B2FF">{{__('Hello, welcome')}} <span style="color:#ffffff;">{{ $name }}</span>
                        </h3>
                    </td>
                    <td
                        style="background: url('{{asset('images/triangle.svg')}}') no-repeat center/contain; width: 60%;">

                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div style="width: 100%; background-color: #ffffff; color: #323232; padding: 70px 0;">
        <div class="email-container" style="max-width: 1240px; padding: 0 30px; margin: 0 auto;">
            <table width="100%">
                <tr>
                    <td>
                        <h5>{{__('Hello')}} {{ $name }},</h5>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <p>{{ __('Please click the button below to verify your email address.') }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a target="_blank" rel="noopener noreferrer"
                            href="{{route($url)}}"
                            class="button button-blue"
                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #56B2FF; border-bottom: 8px solid #56B2FF; border-left: 18px solid #56B2FF; border-right: 18px solid #56B2FF; border-top: 8px solid #56B2FF;">{{ __('Verify Your Email Address') }}</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>{{ config('app.rankup.company_title') }}</b></p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div style="width: 100%; background-color: #323232; padding: 40px 0;">
        <div class="email-container" style="max-width: 1240px; padding: 0 30px; margin: 0 auto;">
            <table width="100%">
                <tr>
                    <td class="bottom">
                        <img width="200" src="{{ asset(config('app.rankup.company_logo_path')) }}">
                        <a class="btn btn-secondary" href="{{ route('login') }}">{{__('Access')}} {{ config('app.rankup.company_title') }}</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>