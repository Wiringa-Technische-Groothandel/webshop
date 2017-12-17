<template>
    <footer id="footer" :class="{ 'footer-fixed-bottom': fixedFooter }">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <address>
                        <b>Wiringa Technische Groothandel</b><br />
                        Bovenstreek 1<br />
                        9731 DH Groningen<br />
                        <i class="fal fa-fw fa-phone"></i> <a href="tel:+31505445566">050 - 544 55 66</a>
                    </address>
                </div>
                <div class="col-sm-9">
                    <p>
                        Deze site maakt gebruik van <abbr title="Deze cookies houden de login status bij en zorgen voor de essentiele functionaliteit van de website">functionele cookies</abbr> en <abbr title="Deze cookies houden anoniem surfgedrag bij van uitsluitend deze website zodat wij de site beter kunnen laten werken">analytische cookies</abbr>.
                        <br />
                        Al onze leveringen geschieden volgens onze algemene leveringsvoorwaarden, gedeponeerd bij de Kamer van Koophandel te Groningen onder nummer 02023871.
                        <br />
                        Een kopie van deze leveringsvoorwaarden zenden wij u op verzoek toe.
                    </p>
                </div>
            </div>
        </div>

        <hr />

        <div class="text-center">
            <small>
                Ontwikkeling en design: <a href="https://linkedin.com/in/thomaswiringa" target="_blank">Thomas Wiringa</a> - <i class="fal fa-fw fa-copyright"></i> {{ (new Date()).getFullYear() }}
            </small>
        </div>
    </footer>
</template>

<script>
    export default {
        methods: {
            reposition () {
                let $footer = document.getElementById('footer');
                let contentHeight = document.body.clientHeight;
                let windowHeight = window.innerHeight - (this.$data.fixedFooter ? $footer.scrollHeight : 0);

                this.$data.fixedFooter = (windowHeight > contentHeight);
            }
        },
        data () {
            return {
                fixedFooter: false
            }
        },
        mounted () {
            this.reposition();
            setInterval(() => {
                this.reposition();
            }, 100);
        }
    }
</script>