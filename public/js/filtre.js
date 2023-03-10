
$(document).on('submit', '#search-form', function(event) {
    event.preventDefault();
    var url = $(this).attr('action');
    var minPrice = $('#min-price').val();
    var maxPrice = $('#max-price').val();
    $.ajax({
        type: 'GET',
        url: url,
        data: { min: minPrice, max: maxPrice },
        success: function(response) {
            $('#product-list').html(response);
        }
    });
});
