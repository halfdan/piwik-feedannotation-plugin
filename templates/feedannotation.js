/*!
 * Validate Feed-URL and show error if invalid.
 */
$(document).ready(function() {
    $("#feed_url").focusout(function() {
        var url = $("#feed_url").val();
        var dataUrlParams = {
            module: 'API',
            method: 'FeedAnnotation.isValidFeedUrl',
            url: url,
            format: 'json'
        }
        var ajaxRequest = new ajaxHelper();
        ajaxRequest.addParams(dataUrlParams, 'GET');
        ajaxRequest.setFormat('json');
        ajaxRequest.setCallback(function(r) {
            if(r["value"] == false) {

            }
        });
        ajaxRequest.setErrorElement("#ajaxErrorFeedUrl");
        ajaxRequest.setLoadingElement("#ajaxLoadingFeedUrl");
        ajaxRequest.send(false);
    });


});