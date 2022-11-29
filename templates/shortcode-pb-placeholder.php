<div id="ec-store-pb-placeholder">
    <style>
    /* PB placeholder */
    .ecwid-pb-placeholder {
        box-sizing: border-box;
        opacity: 0;
        min-height: 400px;
        padding-top: 1px;
    }

    .ecwid-pb-placeholder>div {
        box-sizing: border-box;
    }

    .ecwid-pb-placeholder--light,
    .ecwid-pb-placeholder--dark {
        opacity: 1;
    }

    .ecwid-pb-placeholder__grid {
        overflow: hidden;
        width: 100%;
        max-width: 1000px;
        height: 280px;
        margin: 35px auto 50px;
        text-align: center;
        transition: opacity .15s ease-in-out;
    }

    .ecwid-pb-placeholder__wrap {
        margin-right: -30px;
        margin-left: -30px;
    }

    .ecwid-pb-placeholder__grid-cell {
        display: inline-block;
        width: 282px;
        height: 280px;
        line-height: 280px;
        white-space: nowrap;
    }

    .ecwid-pb-placeholder__product {
        width: 222px;
        height: 222px;
        margin: 0 auto;
        padding: 0;
    }

    .ecwid-pb-placeholder__title {
        max-width: 222px;
        margin: 12px auto;
        padding: 0;
    }

    .ecwid-pb-placeholder__title>div {
        height: 7px;
        margin: 12px auto;
        padding: 0;
    }

    .ecwid-pb-placeholder__title>div:nth-child(3) {
        max-width: 100px;
    }

    /* PB placeholder light */
    .ecwid-pb-placeholder--light .ecwid-pb-placeholder__grid-cell .ecwid-pb-placeholder__product,
    .ecwid-pb-placeholder--light .ecwid-pb-placeholder__grid-cell .ecwid-pb-placeholder__title>div {
        background-color: rgba(0, 0, 0, .03);
    }

    .ecwid-pb-placeholder--light.ecwid-pb-placeholder--animate .ecwid-pb-placeholder__grid-cell:nth-child(1) .ecwid-pb-placeholder__product {
        animation: pb-flash-light 800ms ease-in-out 0ms infinite;
    }

    .ecwid-pb-placeholder--light.ecwid-pb-placeholder--animate .ecwid-pb-placeholder__grid-cell:nth-child(1) .ecwid-pb-placeholder__title>div {
        animation: pb-flash-light 800ms ease-in-out 83ms infinite;
    }

    .ecwid-pb-placeholder--light.ecwid-pb-placeholder--animate .ecwid-pb-placeholder__grid-cell:nth-child(2) .ecwid-pb-placeholder__product {
        animation: pb-flash-light 800ms ease-in-out 167ms infinite;
    }

    .ecwid-pb-placeholder--light.ecwid-pb-placeholder--animate .ecwid-pb-placeholder__grid-cell:nth-child(2) .ecwid-pb-placeholder__title>div {
        animation: pb-flash-light 800ms ease-in-out 250ms infinite;
    }

    .ecwid-pb-placeholder--light.ecwid-pb-placeholder--animate .ecwid-pb-placeholder__grid-cell:nth-child(3) .ecwid-pb-placeholder__product {
        animation: pb-flash-light 800ms ease-in-out 333ms infinite;
    }

    .ecwid-pb-placeholder--light.ecwid-pb-placeholder--animate .ecwid-pb-placeholder__grid-cell:nth-child(3) .ecwid-pb-placeholder__title>div {
        animation: pb-flash-light 800ms ease-in-out 416ms infinite;
    }

    /* PB placehoder dark */
    .ecwid-pb-placeholder--dark .ecwid-pb-placeholder__grid-cell .ecwid-pb-placeholder__product,
    .ecwid-pb-placeholder--dark .ecwid-pb-placeholder__grid-cell .ecwid-pb-placeholder__title>div {
        background-color: rgba(255, 255, 255, .1);
    }

    .ecwid-pb-placeholder--dark.ecwid-pb-placeholder--animate .ecwid-pb-placeholder__grid-cell:nth-child(1) .ecwid-pb-placeholder__product {
        animation: pb-flash-dark 800ms ease-in-out 0ms infinite;
    }

    .ecwid-pb-placeholder--dark.ecwid-pb-placeholder--animate .ecwid-pb-placeholder__grid-cell:nth-child(1) .ecwid-pb-placeholder__title>div {
        animation: pb-flash-dark 800ms ease-in-out 83ms infinite;
    }

    .ecwid-pb-placeholder--dark.ecwid-pb-placeholder--animate .ecwid-pb-placeholder__grid-cell:nth-child(2) .ecwid-pb-placeholder__product {
        animation: pb-flash-dark 800ms ease-in-out 167ms infinite;
    }

    .ecwid-pb-placeholder--dark.ecwid-pb-placeholder--animate .ecwid-pb-placeholder__grid-cell:nth-child(2) .ecwid-pb-placeholder__title>div {
        animation: pb-flash-dark 800ms ease-in-out 250ms infinite;
    }

    .ecwid-pb-placeholder--dark.ecwid-pb-placeholder--animate .ecwid-pb-placeholder__grid-cell:nth-child(3) .ecwid-pb-placeholder__product {
        animation: pb-flash-dark 800ms ease-in-out 333ms infinite;
    }

    .ecwid-pb-placeholder--dark.ecwid-pb-placeholder--animate .ecwid-pb-placeholder__grid-cell:nth-child(3) .ecwid-pb-placeholder__title>div {
        animation: pb-flash-dark 800ms ease-in-out 416ms infinite;
    }

    @keyframes pb-flash-light {
        0% {
        background-color: rgba(0, 0, 0, .03);
        }

        30% {
        background-color: rgba(0, 0, 0, .047);
        }

        100% {
        background-color: rgba(0, 0, 0, .03);
        }
    }

    @keyframes pb-flash-dark {
        0% {
        background-color: rgba(255, 255, 255, .06);
        }

        30% {
        background-color: rgba(255, 255, 255, .1);
        }

        100% {
        background-color: rgba(255, 255, 255, .06);
        }
    }
    </style>
    <div class="ecwid-pb-placeholder ecwid-pb-placeholder--animate ecwid-pb-placeholder--light" id="ecwidStorefrontPlaceholder">
    <div class="ecwid-pb-placeholder__grid">
        <div class="ecwid-pb-placeholder__wrap">
        <div class="ecwid-pb-placeholder__grid-cell">
            <div class="ecwid-pb-placeholder__product"></div>
            <div class="ecwid-pb-placeholder__title">
            <div></div>
            <div></div>
            <div></div>
            </div>
        </div>
        <div class="ecwid-pb-placeholder__grid-cell">
            <div class="ecwid-pb-placeholder__product"></div>
            <div class="ecwid-pb-placeholder__title">
            <div></div>
            <div></div>
            <div></div>
            </div>
        </div>
        <div class="ecwid-pb-placeholder__grid-cell">
            <div class="ecwid-pb-placeholder__product"></div>
            <div class="ecwid-pb-placeholder__title">
            <div></div>
            <div></div>
            <div></div>
            </div>
        </div>
        </div>
    </div>
    <div class="ecwid-pb-placeholder__grid">
        <div class="ecwid-pb-placeholder__wrap">
        <div class="ecwid-pb-placeholder__grid-cell">
            <div class="ecwid-pb-placeholder__product"></div>
            <div class="ecwid-pb-placeholder__title">
            <div></div>
            <div></div>
            <div></div>
            </div>
        </div>
        <div class="ecwid-pb-placeholder__grid-cell">
            <div class="ecwid-pb-placeholder__product"></div>
            <div class="ecwid-pb-placeholder__title">
            <div></div>
            <div></div>
            <div></div>
            </div>
        </div>
        <div class="ecwid-pb-placeholder__grid-cell">
            <div class="ecwid-pb-placeholder__product"></div>
            <div class="ecwid-pb-placeholder__title">
            <div></div>
            <div></div>
            <div></div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
