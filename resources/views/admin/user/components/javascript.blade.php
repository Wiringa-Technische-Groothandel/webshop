<script type="text/javascript">
    var $form = $('#addUserForm');
    var $companyId = $form.find('#inputCompanyId');
    var $companyName = $form.find('#inputCompanyName');
    var $address = $form.find('#inputAddress');
    var $postcode = $form.find('#inputPostcode');
    var $city = $form.find('#inputCity');
    var $username = $form.find('#inputUsername');
    var $email = $form.find('#inputEmail');
    var $active = $form.find('#inputActive');
    var $deleteButton = $form.find('#deleteCompanyButton');

    $companyId.on('input', function() {
        var value = $companyId.val();

        $username.val(value);
        $('#companyID').html(value);

        if (value == $companyId.val()) {
            $.ajax({
                url: "{{ route('admin.user::get') }}",
                type: "GET",
                dataType: "json",
                data: {id: value},
                success: function(data) {
                    var data = data.payload;

                    if (value == $companyId.val() && data != null) {
                        $companyName.val(data.company);
                        $address.val(data.street);
                        $postcode.val(data.postcode);
                        $city.val(data.city);
                        $email.val(data.main_user.email);
                        $active.val(data.active);

                        $deleteButton.removeAttr('disabled');
                    }
                },
                error: function() {
                    if (value == $companyId.val()) {
                        $companyName.val('');
                        $address.val('');
                        $postcode.val('');
                        $city.val('');
                        $email.val('');
                        $active.val(0);

                        $deleteButton.attr('disabled', 'disabled');
                    }
                }
            });
        }
    });
</script>