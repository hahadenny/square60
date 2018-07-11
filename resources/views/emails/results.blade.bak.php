<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
</head>
<body>
<style>
    @media only screen and (max-width: 600px) {
        .inner-body {
            width: 100% !important;
        }

        .footer {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 500px) {
        .button {
            width: 100% !important;
        }
    }
</style>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table class="content" align="center" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="header" align="center" style="background-color: #fff; padding: 1rem">
                        <a href="http://www.square81.com">
                            <img src="http://www.square81.com/images/logo.svg" alt="Square60" width="150">
                        </a>
                    </td>
                </tr>

                <!-- Email Body -->
                <tr>
                    <td>
                    <table class="inner-body" align="left" width="100%" cellpadding="0" cellspacing="0" style="margin-top: 1rem">
                        <!-- Body content -->

                        @foreach ($results as $result)

                            <tr>
                                <td>
                                <div style="width: 60%; color: #4a4a4a; display: block; padding: 1.25rem; margin-bottom: 0.75rem; margin-top: 0.75rem;">

                                    <div style="display: flex; margin: 0rem" class="columns">
                                        <div style="flex: none; width: 42%; padding: 0.75rem; box-sizing: inherit;" class="column is-5">
                                            @if ($result->img)
                                                <img style="height: auto; max-width: 100%;" src="{{$result->img}}" alt="">
                                            @else
                                                <img style="height: auto; max-width: 100%;" src="{{$domain}}}/images/default_image_coming.jpg" alt="">
                                            @endif
                                        </div>
                                        <div style="display: block; flex-basis: 0; flex-grow: 1; flex-shrink: 1; padding: 0.75rem; width: 52%" class="column">
                                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;" class="level">
                                                <a style="text-decoration: none;" href="{{$domain}}/show/{{str_replace(' ','_',$result->name)}}/{{$result->id}}">
                                                    <h4 style="margin: 0; color:#205aa2; margin-bottom: 0 !important; " class="is-6 main-color a">{{$result->full_address}} {{$result->unit}} <br>{{$result->neighborhood}}</h4></a>
                                                <span style="margin-left: 5rem;font-size: 1rem; color: #363636; font-weight: 600; line-height: 1.125;" class="button is-danger">{{$result->unit_type}}</span>
                                            </div>
                                            <div class="content">
                                                <p style="margin-bottom: 1em;">
                                                    <span style="font-size: 1rem;margin-bottom: 1.5rem;color: #363636;font-weight: 600;line-height: 1.125;">$ {{$result->price}}</span>
                                                <!--  <a href="#" class=""  id="calculate-mortage">
                                                <span class="icon"></span>
                                                        <i class="fa fa-calculator"></i>
                                                <span>${{$result->monthly_cost}}/monthly </span>
                                            </a> -->
                                                </p>
                                                <div id="listing-ads" class="">
                                                    <ul style="list-style: none; display: flex;margin-top: 1em;max-width: 70%; align-items: center;justify-content: flex-start;" >
                                                        <li id="listing-ad-bed" style="border-bottom: 0.5px #f1f1f1 solid;padding-bottom: 2px;">{{$result->beds}} beds|</li>
                                                        <li id="listing-ad-bath" style="border-bottom: 0.5px #f1f1f1 solid; padding-bottom: 2px;">{{$result->baths}} baths|</li>
                                                        <li id="listing-ad-ft" style="border-bottom: 0.5px #f1f1f1 solid; padding-bottom: 2px;">{{$result->sq_feet}} ft^<sup>2</sup>|</li>
                                                    </ul>
                                                </div>
                                                <div class="listing-ad-type">
                                                    @if ($result->agent_company)
                                                        {{$result->agent_company}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                </td>
                            </tr>

                        @endforeach

                    </table>
                    </td>
                </tr>

                <tr>
                    <td>
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
                                <td class="content-cell" align="center" style="padding: 1rem">
                                    <strong>Square6-</strong> &copy; All copyright reserved <a href="http://jgthms.com">Jeremy Thomas</a>. The source code is licensed
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