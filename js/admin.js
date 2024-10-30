document.addEventListener('DOMContentLoaded', function() {
    var leadbooster_inputElement = document.getElementById('leadbooster-nexline_options');

    leadbooster_inputElement.addEventListener('input', function() {
        var leadbooster_inputValue = leadbooster_inputElement.value;
        var leadbooster_regex = /leadbooster\.com\.br\/chatbot\/(.*?)\.js/;
        var leadbooster_matches = leadbooster_inputValue.match(leadbooster_regex);

        if (leadbooster_matches) {
            var leadbooster_codeLeadbooster = leadbooster_matches[1];
            leadbooster_inputElement.value = leadbooster_codeLeadbooster;
        }
    });
});
