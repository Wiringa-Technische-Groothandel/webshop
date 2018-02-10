@extends('layouts.account')

@section('title', __('Account / Dashboard'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ trans('titles.account.dashboard', ['company' => $customer->getCompany()->getName() ]) }}
    </h2>
@endsection

@section('account.content')
    <div class="row">
        <div class="col-6">
            @include('components.account.dashboard.delivery-address')
        </div>
    </div>

    <div class="row my-4">
        <div class="col-6">
            @include('components.account.dashboard.contact-email')
        </div>

        <div class="col-6">
            @include('components.account.dashboard.confirmation-email')
        </div>
    </div>
@endsection

@section('extraJS')
    <script>
        /**
         * Toggle the save button below the contact input fields.
         *
         * @return void
         */
        function toggleSaveButton () {
            var $target = $(event.target);
            var $button = $target.parent('form').find('.btn');

            if (
                $target.data('initial') === $target.val() ||
                (
                    $target.val() === "" && $target.data('required')
                )
            ) {
                $button.slideUp();
            } else {
                $button.slideDown();
            }
        }
    </script>
@endsection