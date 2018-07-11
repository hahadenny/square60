<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table class="content" align="center" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="header" align="center" style="background-color: #fff; padding: 1rem; padding-left:0px; padding-bottom:15px;">
                        <a href="{{$domain}}">
                            <img src="{{$domain}}images/logo2.png" alt="Square60" width="150px">
                        </a>
                    </td>
                </tr>

                <!-- Email Body -->
                <tr>
                    <td align=center class="body" width="100%" cellpadding="0" cellspacing="0">
                        <table class="inner-body" align="center" width="auto" cellpadding="0" cellspacing="0">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell">
                                    <h2 align="left" style="font-size:16px;padding-bottom:10px;">{{$subject}}</h2>
                                    <p style="font-family: Arial; box-sizing: border-box; color: #000; line-height: 1.5em; margin-top: 0; text-align: left;">
                                        {!!$msg!!}
                                    </p>                                
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding-top:30px;"><br>
                        <table class="footer" align="center" width="100%" cellpadding="0" cellspacing="0" style="background-color: whitesmoke">
                            <tr>
                                <td class="content-cell" align="center" style="padding-top: 1rem">

                                    <a href="#" style="margin-right: 1rem; text-decoration: none;">
                                        <i class="fa fa-facebook-official"></i>
                                    </a>

                                    <a href="#" style="margin-right: 1rem; text-decoration: none;">
                                        <i class="fa fa-twitter"></i>
                                    </a>

                                    <a href="#" style="margin-right: 1rem; text-decoration: none;">
                                        <i class="fa fa-instagram"></i>
                                    </a>

                                </td>
                            </tr>
                            <tr>
                                <td class="content-cell" align="center" style="padding: 1rem;font-size: 13px;font-family: Arial;">

                                    <strong>Square60</strong> &copy; All copyright reserved <a href="http://jgthms.com">Jeremy Thomas</a>. The source code is licensed
                                    <a href="http://opensource.org/licenses/mit-license.php">MIT</a>. The website content is licensed <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/">CC BY NC SA 4.0</a>.

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>