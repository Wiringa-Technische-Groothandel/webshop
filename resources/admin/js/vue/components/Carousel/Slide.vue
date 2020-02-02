<template>
    <div class="card mb-3" id="carousel-slide">
        <div class="card-img-top text-center">
            <img :src="imageUrl" :alt="slide.title" class="slide-image">
        </div>

        <div class="card-body">
            <div class="caption">
                <h3 class="slide-title" :title="slide.title">
                    {{  (slide.title.length > 55 ? slide.title.substr(0, 55) + '...' : slide.title) }}
                </h3>

                <p class="slide-caption">{{ slide.caption }}</p>
            </div>

            <br />

            <button class="btn btn-raised btn-danger btn-block" @click="$emit('delete', slide)">
                <i class="fas fa-fw fa-trash-alt"></i> Verwijderen
            </button>
        </div>
    </div>
</template>

<style scoped>
    .card-img-top {
        height: 200px;
    }

    .slide-image {
        max-height: 200px;
        height: auto;
        width: auto;
        max-width: 100%;
    }

    .slide-title,
    .slide-caption {
        height: 2.15em;
    }
</style>

<script>
    export default {
        props: {
            slide: {
                required: true,
                type: Object
            }
        },
        computed: {
            imageUrl () {
                return 'storage/uploads/images/carousel/' + this.slide.image
            }
        }
    }
</script>