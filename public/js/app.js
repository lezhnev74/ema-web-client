$(function () {
    // Search input focus
    $(document).keypress(function (event) {
        if (event.keyCode == 115) {
            if(!$("#search_input").is(":focus")) {
                event.preventDefault();
            }
            $("#search_input").focus();
        }
    });
    $("#search_input").keypress(_.debounce(searchNotes, 500));

    // Setup key to access API backend
    $.ajaxSetup({
        headers: {"Authorization": "Bearer " + access_token}
    });

    // Get notes qith query
    function searchNotes(query) {
        var query = $("#search_input").val().trim();
        if (!query.length) {
            return;
        }

        $("#io_api").css('visibility', 'visible');

        //$("#io_api").css('visibility', 'hidden');
        $.ajaxq('backend', {
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

    // Update UI with given notes
    function drawNotes(notes) {
        $("#feed").html('');
        $(notes).each(function (i, e) {
            el = $('<div class="uk-card uk-card-default uk-card-body uk-width-1-1"></div>').text(e.note_text);
            $("#feed").append(el);
        });
    }


});