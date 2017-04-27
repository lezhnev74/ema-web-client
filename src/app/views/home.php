<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMApp</title>
</head>
<body>

<link rel="stylesheet" href="/uikit/css/uikit.min.css"/>

<script>
    var api_base_url = '<?=$api_base_url?>';
    var access_token = '<?=$access_token?>';
</script>
<script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>

<script src="/uikit/js/uikit.min.js"></script>
<script src="/uikit/js/uikit-icons.min.js"></script>
<script src="/js/underscore-min.js"></script>
<script src="/js/ajaxq.js"></script>
<script src="/js/app.js"></script>

<div>
    <div class="uk-container">
        <nav class="uk-navbar-container" style="background-color: transparent;" uk-navbar>
            <div class="uk-navbar-left">

                <div class="uk-navbar-item" style="min-width:50px;">
                    <div uk-spinner id="io_api" style="visibility:hidden;"></div>
                </div>
                <div class="uk-navbar-item">
                    <div uk-search-icon id="search_icon" style=""></div>
                    <form class="uk-search uk-search-navbar">
                        <input autocomplete="off" class="uk-search-input" type="search" placeholder="[s]earch..." id="search_input">
                    </form>
                </div>

            </div>
        </nav>
        <div uk-grid id="feed"></div>
        <br><br>
        <a href="#" uk-totop uk-scroll></a>
    </div>
</div>


</body>
</html>