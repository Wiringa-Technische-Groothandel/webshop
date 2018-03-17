<template>
    <div id="header-carousel" class="carousel slide" data-ride="carousel" v-if="slides.length > 0">
        <ol class="carousel-indicators">
            <li data-target="#header-carousel" :data-slide-to="slide.order" v-for="slide in slides"
                :class="{active: slide.order === 0}"></li>
        </ol>

        <div class="carousel-inner">
            <div class="carousel-item" :class="{active: slide.order === 0}" v-for="slide in slides">
                <img :src="'/img/carousel/' + slide.image" :alt="slide.image" class="carousel-image float-md-right">
                <div class="carousel-caption">
                    <h3>{{ slide.title }}</h3>
                    <p>{{ slide.caption }}</p>
                </div>
            </div>
        </div>

        <a class="carousel-control-prev" href="#header-carousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Vorige</span>
        </a>

        <a class="carousel-control-next" href="#header-carousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Volgende</span>
        </a>
    </div>
</template>

<script>
    export default {
        props: ['slides']
    }
</script>