$(function () {

    // Setup key to access API backend
    $.ajaxSetup({
        headers: {"Authorization": "Bearer " + access_token}
    });

    // note form submission
    $("#note_form").submit(function (e) {
        var note_id = $("input[name='note_id']", "#note_form").val();
        var note_text = $("textarea[name='note_text']", "#note_form").val();

        $("#io_form").css('visibility', 'visible');


        if (note_id.trim().length) {
            // Edit note
            var url = api_base_url + '/api/notes/' + note_id;
            var data = {
                "text": note_text
            };
        } else {
            // Post new note
            var url = api_base_url + '/api/notes';
            var data = {
                "text": note_text
            };
        }

        $.ajaxq('backend-write', {
            type: "POST",
            url: url,
            contentType: "application/json",
            data: JSON.stringify(data),
            dataType: 'json',
            success: function (data) {
                // Set current form note_id after saving
                $("input[name='note_id']", "#note_form").val(data.note_id);
            },
            error: function (xhr) {
                var data = JSON.parse(xhr.responseText);
                console.log(data);
            },
            complete: function () {
                $("#io_form").css('visibility', 'hidden');
            }
        }).always(function () {
            $("#io_api").css('visibility', 'hidden');
        });


        e.stopPropagation();
        return false;
    });

    // modal form setup
    var form_modal = UIkit.modal("#modal-note-form");
    $("#modal-note-form").on('show', function () {
        $("textarea[name='note_text']", "#note_form").focus();
    });
    $("#modal-note-form").on('hide', function () {
        recentNotes();
    });

    $('body').on('click', ".edit_note", function (e) {
        var note_id = $(this).parents('.note_block').attr('note_id');
        var note_text = $(this).parents('.note_block').find('.note_text').text();
        edit_form(note_id, note_text);
        return false;
    });

    $('body').on('click', '.delete_note', function (e) {
        var note_id = $(this).parents('.note_block').attr('note_id');
        var note_node = $(this).parents('.note_block');

        e.preventDefault();
        $(this).blur();
        UIkit.modal.confirm('Delete the note?').then(function () {

            deleteNote(note_id, function () {
                $(note_node).detach();
            }, function (data) {
                alert(data.error_message);
            });

        }, function () {
            //
        });
    });

    // open note form
    $("#post_note_button").click(function (e) {
        edit_form('', '');
        e.stopPropagation();
        return false;
    });

    // Search input focus
    $(document).keypress(function (event) {

        // only react if no input is focussed
        if (!$(":input").is(":focus")) {
            event.preventDefault();

            if (event.keyCode == 115) {
                $("#search_input").focus();
            } else if (event.keyCode == 112) {
                edit_form('', '');
            }
        }

    });
    $("#search_input").keyup(_.debounce(searchNotes, 500));


// Get notes qith query
    function searchNotes(query) {


        var query = $("#search_input").val().trim();
        if (!query.length) {
            drawNotes([]);
            return;
        }

        $("#io_api").css('visibility', 'visible');

        $.ajaxq.abort('backend-read');
        $.ajaxq('backend-read', {
            type: "GET",
            url: api_base_url + '/api/notes/search/' + query,
            contentType: "application/json",
            dataType: 'json',
            success: function (data) {
                drawNotes(data);
            },
            error: function (xhr) {
                var data = JSON.parse(xhr.responseText);
                if (data.error_code == "BAD_TOKEN") {
                    window.location.href = '/logout';
                }
            }
        }).always(function () {
            $("#io_api").css('visibility', 'hidden');
        });
    }

    function recentNotes() {
        $("#search_input").val('');
        $("#io_api").css('visibility', 'visible');

        $.ajaxq.abort('backend-read');
        $.ajaxq('backend-read', {
            type: "GET",
            url: api_base_url + '/api/notes/recent?count=20',
            contentType: "application/json",
            dataType: 'json',
            success: function (data) {
                drawNotes(data);
            },
            error: function (xhr) {
                var data = JSON.parse(xhr.responseText);
                if (data.error_code == "BAD_TOKEN") {
                    window.location.href = '/logout';
                }
            }
        }).always(function () {
            $("#io_api").css('visibility', 'hidden');
        });
    }

    function deleteNote(note_id, success, fail) {
        var url = api_base_url + '/api/notes/' + note_id;
        $.ajaxq('backend-write', {
            type: "DELETE",
            url: url,
            contentType: "application/json",
            dataType: 'json',
            success: function (data) {
                success();
            },
            error: function (xhr) {
                fail();
            }
        });
    }

// Update UI with given notes
    function drawNotes(notes) {
        $("#feed").html('');
        $(notes).each(function (i, e) {
            el = $('<div class="note_block uk-card uk-card-default uk-card-body uk-width-1-1"></div>');
            $(el).prepend('<div class="note_text">' + e.note_text + '</div>');
            $(el).attr('note_id', e.id);
            var buttons = $('<div class="uk-align-right"></div>');
            $(buttons).append('<a href="#" class="edit_note  uk-icon-link uk-margin-small-left" uk-icon="icon: file-edit"></a>');
            $(buttons).append('<a href="#" class="delete_note uk-icon-link uk-margin-small-left" uk-icon="icon: trash"></a>');
            $(el).prepend(buttons);
            $("#feed").append(el);
        });
    }

    function edit_form(note_id, note_text) {
        $("input[name='note_id']", "#note_form").val(note_id);
        $("textarea[name='note_text']", "#note_form").val(note_text);

        form_modal.show();
    }

    // Load notes on start
    recentNotes();

});

