function cmsCommentDeleteSubmit(that) {
    var token = $("input[name='_token']").val();

    $.ajax({
        url: $(that).attr("href"),
        type: 'DELETE',
        dataType: "json",
        data: {
            _token: token
        },
        success: function(data, status, xhr) {
            if (!xhr.responseJSON) {
                cmsCommentLock = false;
                return;
            }
            if (xhr.responseJSON.success !== true || !xhr.responseJSON.msg || !xhr.responseJSON.comment_id) {
                cmsCommentLock = false;
                return;
            }
            $("#comment_"+xhr.responseJSON.comment_id).slideUp(cmsCommentTime, function() {
                $(this).remove();
                if ($("#comments > div").length == 0 && $("#comments > p").length == 0) {
                    $("<p id=\"nocomments\">There are currently no comments.</p>").prependTo("#comments").hide().fadeIn(cmsCommentTime, function() {
                        cmsCommentLock = false;
                    });
                } else {
                    cmsCommentLock = false;
                }
            });
        },
        error: function(xhr, status, error) {
            cmsCommentLock = false;
        }
    });
}

function cmsCommentDelete(bindval) {
    bindval = bindval || ".deletable";
    $(bindval).click(function() {
        var that = this;
        var cmsCommentDeleteCheck = setInterval(function() {
            if (!cmsCommentLock) {
                cmsCommentLock = true;
                cmsCommentDeleteSubmit(that);
            } else {
                clearInterval(cmsCommentDeleteCheck);
            }
        }, 1);
        return false;
    });
}
