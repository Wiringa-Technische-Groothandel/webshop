<template>
    <div id="page-carousel">
        <carousel-create-modal @close="showModal = false" @hide="showModal = false" @slideCreated="getSlides"
                               :show="showModal"></carousel-create-modal>

        <button class="btn btn-success bmd-btn-fab tooltip-toggle" @click="showModal = true"
                title="Slide toevoegen aan de carousel">
            <i class="fal fa-fw fa-plus"></i>
        </button>

        <draggable v-model="slides" v-bind="dragOptions" group="carousel-slides" @start="drag=true" @end="drag=false">
            <transition-group class="row mb-3" type="transition" :name="!drag ? 'flip-list' : null">
                <div class="col-sm-6 col-md-4" v-for="slide in slides" :key="slide.id">
                    <carousel-slide :slide="slide" @delete="deleteSlide"></carousel-slide>
                </div>
            </transition-group>
        </draggable>
    </div>
</template>

<style scoped>
    .bmd-btn-fab {
        position: fixed !important;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .flip-list-move {
        transition: transform 0.5s;
    }

    .no-move {
        transition: transform 0s;
    }
</style>

<script>
    import {debounce} from 'lodash'
    import Draggable from 'vuedraggable'
    import CarouselSlide from '../components/Carousel/Slide'
    import CarouselCreateModal from '../components/Carousel/CreateModal'

    export default {
        props: {
            carouselSlides: {
                required: false,
                type: Array,
                default() {
                    return []
                }
            }
        },
        components: {
            Draggable,
            CarouselSlide,
            CarouselCreateModal
        },
        data() {
            let slides = this.carouselSlides;

            return {
                showModal: false,
                drag: false,
                slideOrder: [],
                slides: slides
            }
        },
        watch: {
            drag(val, oldVal) {
                if (val || val === oldVal) {
                    return;
                }

                this.slides.forEach((slide, index) => slide.order = index);

                this.updateSlideOrder(this);
            }
        },
        computed: {
            dragOptions() {
                return {
                    animation: 200,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            }
        },
        methods: {
            updateSlideOrder: debounce((_this) => {
                _this.$http.post(route('admin.api.update-slides'), {
                    slides: _this.slides
                })
                    .then((response) => {
                        if (!response.data.success) {
                            _this.$root.$emit('send-notify', {
                                text: response.data.message,
                                success: false
                            });
                        }
                    })
                    .catch(_this._handleAxiosError);
            }, 1000),
            getSlides() {
                this.$http.get(route('admin.api.carousel-slides'))
                    .then((response) => {
                        this.slides = response.data.slides;
                    })
                    .catch(this._handleAxiosError);
            },
            deleteSlide(slide) {
                this.$http.delete(route('admin.api.delete-slide'), {
                    params: {
                        id: slide.id
                    }
                })
                    .then((response) => {
                        this.$root.$emit('send-notify', {
                            text: response.data.message,
                            success: response.data.success
                        });

                        this.getSlides();
                    })
                    .catch(this._handleAxiosError);
            },
            _handleAxiosError(error) {
                console.error(error);

                this.$root.$emit('send-notify', {
                    text: error,
                    success: false
                });
            }
        },
        mounted() {
            this.getSlides();
        }
    }
</script>