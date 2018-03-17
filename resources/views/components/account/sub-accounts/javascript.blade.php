<script>
    $('.delete-user').click(function() {
        var username = $(this).attr('data-username');

        $('#deleteUsername').html(username);
        $('#deleteUsernameInput').attr('value', username);

        $('#deleteAccountDialog').modal('toggle');
    });

    function updateRole(target) {
        var user = $.parseJSON($(target).attr('data-user'));
        var spinner = $(target).siblings('.fa-spinner');

        $(target).hide();
        $(spinner).show();

        $.ajax({
            url: $(target).attr('data-url'),
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            dataType: 'json',
            success: function (data) {
                $(target).prop('checked', target.checked);
            },
            error: function (data) {
                $(target).prop('checked', target.checked != true);
            },
            complete: function () {
                $(spinner).hide();
                $(target).show();
            }
        });
    }
</script>