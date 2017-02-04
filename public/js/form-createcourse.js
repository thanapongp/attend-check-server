$(document).on('change', ':file', function() {
    var input = $(this),
    numFiles = input.get(0).files ? input.get(0).files.length : 1,
    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $(':file').on('fileselect', function(event, numFiles, label) {
        $('#filename').html(label);
    });

    $('.input-group.date').datetimepicker({
        locale: 'th',
        format: 'D MMM YYYY',
        useCurrent: false
    });

    $('#start_date').on('dp.change', function(e) {
        $('#end_date').data('DateTimePicker').minDate(e.date);
    });

    $('#end_date').on('dp.change', function(e) {
        $('#start_date').data('DateTimePicker').maxDate(e.date);
    });
});