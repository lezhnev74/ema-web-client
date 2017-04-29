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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

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
                        <input autocomplete="off" class="uk-search-input" type="search" placeholder="[s]earch..."
                               id="search_input">
                    </form>
                </div>


            </div>
            <div class="uk-navbar-right">
                <div class="uk-navbar-item">
                    <a class="uk-button uk-button-default" href="#" id="post_note_button">[p]ost a note</a>
                </div>
            </div>
        </nav>


        <div uk-grid id="feed"></div>
        <br><br>
        <a href="#" uk-totop uk-scroll></a>
    </div>
</div>

<div id="modal-note-form" uk-modal="center: true" class="uk-modal-container">
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-outside" type="button" uk-close></button>
        <form id="note_form">
            <input type="hidden" name="note_id">
            <div class="uk-modal-body">
                <div class="uk-margin">
                    <textarea class="uk-textarea" id="note_form_textarea" rows="5" placeholder="" name="note_text"></textarea>
                </div>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <div uk-spinner style="visibility: hidden;" id="io_form"></div>
                <input type="submit" class="uk-button uk-button-primary" value="Save" />
            </div>
        </form>
        <div class="uk-modal-caption">Markdown supported</div>
    </div>
</div>


</body>
</html>