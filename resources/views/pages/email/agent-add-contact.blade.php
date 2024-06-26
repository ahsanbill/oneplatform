<style>@import url('https://fonts.googleapis.com/css?family=Open+Sans');</style>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
    </head>
    <body>
        <div bgcolor="#E5EDF6" style="background:#f6f5f2;margin-top:0;margin-right:0;margin-bottom:0;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">







    <table style="margin-top:0;text-align:center;width:100%" width="100%" cellspacing="0" cellpadding="0" bgcolor="#999999" align="center">
        <tbody>
        <tr>
            <td style="min-width:8px"></td>
            <td height="30px">&nbsp; </td>
            <td style="min-width:8px"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                    <tr>
                        <td style="text-align:left" align="left">
                            <a style="margin: 0 auto 10px auto; font-size: 15px; color: #000; font-family: Open sans, sans-serif; letter-spacing: 1px; display: flex; align-items: center; text-decoration: none;" class="logo8" href="{{route('site.home')}}">
                                <img style="height: 22px;margin: 0 6px 0 0 !important;" src="https://www.1platform.tv/images/1logo8.png" alt="">
                                <div>Platform</div>
                            </a>
                        </td>

                    </tr>
                    </tbody>
                </table>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td style="text-align:center" width="500" align="center">


                <table style="padding: 15px 60px;background:#ffffff;border-width:1px;border-style:solid;border-color:#e6e6e6;max-width:500px;min-width:320px;text-align:left;width:100%" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center">
                    <tbody>
                    <tr>
                        <td>

                            <table style="width:100%" cellspacing="0" cellpadding="0" border="0" align="center">
                                <tbody>

                                <tr>
                                    <td width="14">&nbsp;</td>
                                    <td height="31">&nbsp;</td>
                                    <td width="14">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td style="width: 90%;">

                                        <table style="width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tbody>

                                            <tr>
                                                <td style="font-family:Open Sans,sans-serif;font-weight: 500;font-size:17px;">
                                                    <span style="color:#fc064c;">Hi {{ $contact->name }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="center">

                                                    <table cellspacing="0" cellpadding="0" border="0" align="center">
                                                        <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td width="290">

                                                                <table style="width:100%;max-width:290px" cellspacing="0" cellpadding="0" border="0" align="center">
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>

                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                </td>
                                            </tr>


                                            <tr>
                                                <td height="22">&nbsp;</td>
                                            </tr>
                                            @if($action == 'create')
                                            <tr>
                                                <td style="color:#8c8c8c;font-family:Open Sans,sans-serif;font-weight: 500;font-size:11px">
                                                    You have been invited by
                                                    <span style="color:#fc064c;">{{$agent->name}}</span>
                                                     to join 1platform.<br>{{$agent->namePartOne()}} would like you to join the network<br><br>Before you join you are required to review and sign the following document.

                                                     <br><br>
                                                     <tr>
                                                         <td height="22">&nbsp;</td>
                                                     </tr>

                                                     <tr>
                                                        <td align="center" style="cursor: pointer; padding: 3px;background: #737373;color: #fff;font-family: Open Sans,sans-serif;border-radius: 5px;font-size: 14px;">
                                                            <a target="_blank" style="color: #fff;text-decoration: none;" href="{{route('agent.contact.approve.agreement', ['id' => $contact->id, 'agentId' => $agent->id])}}">
                                                            Open Document
                                                            </a>
                                                        </td>

                                                     </tr>
                                                     <br><br>
                                                </td>
                                            </tr>
                                            @elseif($action == 'questionnaire')
                                            <tr>
                                                <td style="font-family:Open Sans,sans-serif;font-weight: normal;font-size:13px;" align="left">
                                                    <span style="color:#8c8c8c;width: 100%; font-size: 11px; margin-bottom: 5px;float: left;">
                                                        Please fill out <a style="color: #fc064c" href="{{route('agent.contact.details', ['code' => $contact->code])}}">this form</a> so {{$agent->name}} can understand your goals.<br><br>
                                                    </span>
                                                </td>
                                            </tr>
                                            @endif
                                            </tbody>
                                        </table>

                                    </td>
                                    <td>&nbsp;</td>
                                </tr>

                                </tbody>
                            </table>


                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td height="70"></td>
            <td>&nbsp;</td>
        </tr>

        </tbody>
    </table>
</div>
</body>
</html>
