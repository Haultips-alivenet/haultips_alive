<style type="text/css">
    @media screen and (max-width: 600px) {
        table[class="container"] {
            width: 95% !important;
        }
    }

    #outlook a {
        padding: 0;
    }

    body {
        width: 100% !important;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        margin: 0;
        padding: 0;
    }

    .ExternalClass {
        width: 100%;
    }

    .ExternalClass,
    .ExternalClass p,
    .ExternalClass span,
    .ExternalClass font,
    .ExternalClass td,
    .ExternalClass div {
        line-height: 100%;
    }

    #backgroundTable {
        margin: 0;
        padding: 0;
        width: 100% !important;
        line-height: 100% !important;
    }

    img {
        outline: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
    }

    a img {
        border: none;
    }

    .image_fix {
        display: block;
    }

    p {
        margin: 1em 0;
    }

    table td {
        border-collapse: collapse;
    }

    table {
        border-collapse: collapse;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
    }

    @media only screen and (max-device-width: 480px) {
        a[href^="tel"],
        a[href^="sms"] {
            text-decoration: none;
            color: black;
            /* or whatever your want */
            pointer-events: none;
            cursor: default;
        }
        .mobile_link a[href^="tel"],
        .mobile_link a[href^="sms"] {
            text-decoration: default;
            color: orange !important;
            /* or whatever your want */
            pointer-events: auto;
            cursor: default;
        }
    }

    @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
        a[href^="tel"],
        a[href^="sms"] {
            text-decoration: none;
            color: blue;
            /* or whatever your want */
            pointer-events: none;
            cursor: default;
        }
        .mobile_link a[href^="tel"],
        .mobile_link a[href^="sms"] {
            text-decoration: default;
            color: orange !important;
            pointer-events: auto;
            cursor: default;
        }
    }

    p {
        color: #555;
        font-family: Helvetica, Arial, sans-serif;
        font-size: 16px;
        line-height: 160%;
    }

    .bgBody {
        background: #0E6159;
    }

    .container {
        background: #fff;
    }
</style>

</head>

<body style="font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;">

    <table cellpadding="0" width="100%" cellspacing="0" border="0" id="backgroundTable" class='bgBody' style="background:#0E6159;">
        <tr>
            <td>
                <table cellpadding="0" width="620" class="container" align="center" cellspacing="0" border="0">
                    <tr>
                        <td>

                            <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
                                <tr>
                                    <td class='movableContentContainer bgItem'>

                                        <div class='movableContent'>
                                            <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
                                                <tr height="40">
                                                    <td width="200">&nbsp;</td>
                                                    <td width="200">&nbsp;</td>
                                                    <td width="200">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="200" valign="top">&nbsp;</td>
                                                    <td width="200" valign="top" align="center">
                                                        <div class="contentEditableContainer contentImageEditable">
                                                            <div class="contentEditable" align='center' style="background:#0E6159; padding:10px; display:block;">
<!--                                                                {!!Html::image('resources/assets/images/emailTemplate/logo.png','Logo')!!}-->
                                                                <img src="{{asset('http://haultips.com/new_haultips/public/user/img/logo.png')}}" width="100" height="auto"  alt='Logo'  data-default="placeholder" />
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="200" valign="top">&nbsp;</td>
                                                </tr>
                                                <tr height="25">
                                                    <td width="200">&nbsp;</td>
                                                    <td width="200">&nbsp;</td>
                                                    <td width="200">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class='movableContent'>
                                            <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container" style="background:#fff; padding:30px;">
                                                <tr>
                                                    <td width="100%" colspan="3" align="center">
                                                        <div class="contentEditableContainer contentTextEditable">
                                                            <div class="contentEditable" align='center'>
                                                                <h2 style="color:#181818;font-family:Helvetica, Arial, sans-serif;font-size:22px;font-weight: normal; margin:0;"></h2>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="100">&nbsp;</td>
                                                    <td width="480" align="center">
                                                        <div class="contentEditableContainer contentTextEditable">
                                                            <div class="contentEditable" align='left'>

                                                                <p>Hello {{ $user->first_name }},
                                                                    </p>
                                                                    <p style="color:#0E6159; font-style:italic">Your booking has been sucessfully placed.</p>
                                                                <p>If You have any query, You can contact on below Details:</p><br>
                                                                
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="100">&nbsp;</td>
                                                </tr>
                                                <tr>
								<td width="100">&nbsp;</td>
								<td width="480" align="left">
									<p style="border-bottom: 1px solid #ddd"><span style="display: block; color: rgb(44, 159, 28); font-weight: bold;">FullName</span>{{ $carrier->first_name }}</p>
									<p style="border-bottom: 1px solid #ddd"><span style="display: block; color: rgb(44, 159, 28); font-weight: bold;">Email Address</span>{{ $carrier->email }}</p>
									<p style="border-bottom: 1px solid #ddd"><span style="display: block; color: rgb(44, 159, 28); font-weight: bold;">Phone Number</span>+91{{ $carrier->mobile_number }}</p>
									

								</td>
								<td width="100">&nbsp;</td>
							</tr>
                                                        <p>Thank you </p>
                                                                <p>Team Haultips !</p>
                                                        <tr>
                                                            <td width="100">&nbsp;</td>
                                                          <td width="480" align="left">

                                                          

                                                          </td>
                                                              <td width="100">&nbsp;</td>
                                                        </tr>


                                            </table>

                                        </div>

                                        <div class='movableContent'>
                                            <table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
                                                <tr>
                                                    <td width="100%" colspan="2" style="padding-top:25px;">
                                                        <hr style="height:1px;border:none;color:#333;background-color:#ddd;" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="80%" height="auto" valign="middle">
                                                        <div class="contentEditableContainer contentTextEditable">
                                                            <div class="contentEditable" align='center'>
                                                                <p style="margin:0; color:#fff;"> Copyright &#169; 2017 Haultips. All rights reserved.</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="5%" height="auto" align="right" valign="top" align='right'>

                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    <!-- End of wrapper table -->
