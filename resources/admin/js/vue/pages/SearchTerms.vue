<template>
    <div class="row mb-3" id="page-search-terms">
        <div class="col-12">
            <term-list></term-list>
        </div>
    </div>
</template>

<script>
    import TermList from '../components/SearchTerms/List'

    export default {
        components: {
            TermList
        }
    }
</script>