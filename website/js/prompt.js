// 弹窗
function showPrompt(propmtTitle, propmtText) {
    $("html").append(
        '<div class="alert-wrap">\n' +
        '        <div class="alert-inr-box">\n' +
        '            <h2>' + propmtTitle + '</h2>\n' +
        '            <p>' + propmtText + '</p>\n' +
        '            <button>確定</button>\n' +
        '        </div>\n' +
        '</div>'
    );
    $('.alert-wrap button').click(function () {
        $('.alert-wrap').remove();
    })
}
