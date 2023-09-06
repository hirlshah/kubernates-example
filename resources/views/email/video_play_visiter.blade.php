<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{__('Welcome to'). config('app.name')}}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    </head>
    <body style="margin: 0; box-sizing: border-box; font-family: 'Open Sans', sans-serif;">
        <div style="margin: 0 auto;">
            <table width="100%" style="overflow: auto;">
                <tbody style="box-sizing: border-box;">
                    <tr style="box-sizing: border-box; background:linear-gradient(180deg, #323232 0, #323232 80%, #fff 328px, #fff);">
                        <td style="padding: 78px 30px; box-sizing: border-box;">
                            <table style="min-width: 1200px; width: 1200px; margin: 0 auto; overflow: auto;">
                                <tr style="width: 100%; float: left; box-sizing: border-box;">
                                    <td style="width: 50%; float: left; box-sizing: border-box;">
                                        <img width="200" src="{{ asset(config('app.rankup.company_logo_path')) }}">
                                        <h4 style="color: white; font-size: 18px;">{{__('Hi')}}, {{ $video_user->name}}! </h4>
                                        <h1 style="color: #56B2FF; font-size: 30px;">{{ $watching_user_name }} {{__('has started watching your video')}}</h1>
                                        <p style="color: white; line-height: 32px; font-size:16px;">{{ $watching_date}}</p>
                                        <a href="{{ config('app.url') }}" style="font-Weight:600; line-height:36px; float: left; background-color: #fff; padding: 8px 39px;Letter-spacing:0.32px; border-radius: 44px; color: #323232; text-decoration: none; box-sizing: border-box;">{{__('Check it on')}} {{ config('app.rankup.company_title') }}</a>
                                    </td>
                                    <td>
                                        <table style="border: 1px solid #0000001f; box-sizing: border-box; width: 100%; border-radius: 12px; background-color: white; float: left;">
                                            <tr>
                                                <td style="border-radius: 20px;">
                                                    <img src="{{ $video_cover_image }}" style="border-radius: 20px; width:584px; height:285px; object-fit: cover;"/>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="background-color: #323232; width: 100%;">
                        <td>
                            <table style="min-width: 1200px; width: 1200px; margin: 0 auto; overflow: auto;">
                                <tr style="float: left; padding: 40px 30px; width: 100%; box-sizing: border-box;">
                                    <td style="width: 50%; float: left; box-sizing: border-box; ">
                                        <img width="200" src="{{ asset(config('app.rankup.company_logo_path')) }}">
                                    </td>
                                    <!-- <td style="width: 50%; float: left; box-sizing: border-box;">
                                        <a href="{{ config('app.url') }}" style="box-sizing: border-box; float: right; background-color: white; padding: 17px 40px; border-radius: 44px; color: #323232; text-decoration: none; box-sizing: border-box; text-align: right;">{{__('Access')}} {{ config('app.rankup.company_title') }}</a>
                                    </td> -->
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
