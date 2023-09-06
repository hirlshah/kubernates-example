@extends('email.layout1')
@section('content')
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
        <tr>
            <td>&nbsp;</td>
            <td class="container">
                <div class="content">
                    <!-- START CENTERED WHITE CONTAINER -->
                    <table role="presentation" class="main">
                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                            <td class="wrapper">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            <p>{{__('Hi')}} {{$contact->name}},</p>
                                            <p>{{__('You are invited to')}} {{config('app.name')}}</p>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                                   class="btn btn-primary">
                                                <tbody>
                                                <tr>
                                                    <td align="left">
                                                        <table role="presentation" border="0" cellpadding="0"
                                                               cellspacing="0">
                                                            <tbody>
                                                            <tr>
                                                                <td><a href="{{route('home')}}"
                                                                       target="_blank">{{__('Go to')}} {{config('app.name')}}</a>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <p>{{$contact->message}}</p>
                                            <p>{{__('Thanks!')}}</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- END MAIN CONTENT AREA -->
                    </table>
                    <!-- END CENTERED WHITE CONTAINER -->
                    <!-- START FOOTER -->
                    <div class="footer">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="content-block">
                                    <span class="apple-link">{{_('Address')}}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- END FOOTER -->
                </div>
            </td>
            <td>&nbsp;</td>
        </tr>
    </table>
@endsection