<template>
    <section id="Index" class="pt-3">
        <div class="row">
            <div class="col-lg-8 mb-3">
                <CompositionTop/>
            </div>
            <div class="col-lg-4 mb-3">
                <Videos/>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 mb-3">
                <MarketTop/>
                <GamblingTop/>
                <RouletteTop/>
            </div>
            <div class="col-lg-4 mb-3">
                <Banners/>
            </div>
        </div>
        <div class="mt-3">
            <Reviews/>
            <ContactForm/>
        </div>
    </section>
</template>

<script>
    import MarketTop from "../../modules/main/market/MarketTop";
    import GamblingTop from "../../modules/main/gambling/GamblingTop";
    import RouletteTop from "../../modules/main/roulette/RouletteTop";
    import Banners from "../../modules/main/components/Banners";
    import CompositionTop from "../../modules/main/compositions/CompositionTop";
    import Videos from "../../modules/main/components/Videos";
    import Reviews from "../../modules/main/components/Reviews";
    import ContactForm from "../../modules/main/components/ContactForm";
    export default {
        name: "Index",
        components: {ContactForm, Reviews, CompositionTop, Banners, RouletteTop, GamblingTop, MarketTop, Videos},
        data: () => ({
            title: window.config.appName
        }),
    }
</script>

<style lang="scss" scoped>
    .title {
        font-size: 64px;
    }
</style>
