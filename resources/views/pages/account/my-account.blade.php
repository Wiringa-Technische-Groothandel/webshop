@extends('layouts.account')

@section('title', __('Account / Mijn Account'))

@section('account.title')
    <h2 class="text-center block-title">
        {{ __('Welkom, :company', [ 'company' => $customer->getCompany()->getName() ]) }}
    </h2>
@endsection

@section('account.content')
    <div class="row">
        <div class="col-12 col-sm-6">
            @include('components.account.my-account.delivery-address')
        </div>
    </div>

    <div class="row my-4">
        <form method="POST">
            {{ csrf_field() }}

            <div class="col-12 col-sm-6 mb-3">
                @include('components.account.my-account.contact-email')
            </div>

            <div class="col-12 col-sm-6">
                @include('components.account.my-account.confirmation-email')
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        /**
         * Toggle the save button below the contact input fields.
         *
         * @return void
         */
        function toggleSaveButton () {
            const $target = $(event.target);
            const $button = $target.parent('form').find('.btn');

            if (
                $target.data('initial') === $target.val() ||
                ( $target.val() === "" && $target.data('required') )
            ) {
                $button.slideUp();
            } else {
                $button.slideDown();
            }
        }
    </script>
@endpush