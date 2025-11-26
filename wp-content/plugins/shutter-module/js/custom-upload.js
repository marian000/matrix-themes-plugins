jQuery(document).ready(function($) {
    $('#file-upload-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Get the file input
        var fileInput = $('#upload-file')[0];
        if (fileInput.files.length === 0) {
            alert('Please select a CSV file.');
            return;
        }

        var file = fileInput.files[0];
        var formData = new FormData();
        formData.append('upload-file', file);
        formData.append('action', 'process_csv_file'); // Add the action to the formData

        // AJAX request to submit the CSV file to the REST API
        $.ajax({
            url: uploadAjax.ajax_url, // admin-ajax.php URL
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('response', response);
                if (response.success) { // Check if the response is successful
                    alert('Data processed successfully.');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(error) {
                console.log('error: ',error);
                alert('An error occurred while processing the file.');
            }
        });
    });
});