<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMApp</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
    <link rel="manifest" href="/icon/manifest.json">
    <link rel="mask-icon" href="/icon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/icon/favicon.ico">
    <meta name="msapplication-config" content="/icon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
</head>
<body>


<div>
    <div class="uk-container">
        <div class="uk-card uk-card-body">
            <h3 class="uk-card-title">Please use your gmail account</h3>
            <div class="uk-card-body uk-card-default">
                <a href="#" class="uk-button uk-button-default" id="google_auth_link">
                    <span uk-icon="icon: google"></span>
                    Login with Google
                </a>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="/uikit/css/uikit.min.css"/>
<script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
<script src="/uikit/js/uikit.min.js"></script>
<script src="/uikit/js/uikit-icons.min.js"></script>

<script>
    $(function () {
        var client_id = "<?=$google_client_id?>";
        var callback_url = "<?=$google_redirect_url?>";

        var auth_url = "https://accounts.google.com/o/oauth2/v2/auth?";
        auth_url += "scope=profile&";
        auth_url += "access_type=offline&";
        auth_url += "include_granted_scopes=true&";
        auth_url += "state=state_parameter_passthrough_value&";
        auth_url += "redirect_uri=" + callback_url + "&";
        auth_url += "&response_type=code&client_id=" + client_id;

        $("#google_auth_link").attr('href', auth_url).show();
    });
</script>


</body>
</html>