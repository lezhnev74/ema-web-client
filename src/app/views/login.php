<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMApp</title>
</head>
<body>


<a href="" id="google_auth_link" style="display: none;">Login with Google</a>


<script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>

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