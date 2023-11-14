@extends('layouts.app')

@section('content')
    <div class="container-contact100">
        <div class="wrap-contact100">
            <form class="contact100-form validate-form" action="{{ route('feedback.store') }}" method="POST">
                <span class="contact100-form-title">
                    Submit Feedback
                </span>
                @csrf
                <div class="wrap-input100 validate-input" data-validate="Please enter your name">
                    <input type="text" class="input100" name="title" placeholder="Enter Title"
                        value="{{ old('title') }}" required>
                    <span class="focus-input100"></span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Please enter your email: e@a.x">
                    <select class="input100" required name="category">
                        <option>Select Category </option>
                        <option value="bug report">Bug Report</option>
                        <option value="feature request">Feature Request</option>
                        <option value="improveme">Improveme</option>
                    </select>
                    <span class="focus-input100"></span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Please enter your message">
                    <textarea name="description" class="input100" placeholder="Your Description" required>{{ old('description') }}</textarea>
                    <span class="focus-input100"></span>
                </div>
                <div class="container-contact100-form-btn">
                    <button class="contact100-form-btn" type="submit">
                        <span>
                            <i class="fa fa-paper-plane-o m-r-6" aria-hidden="true"></i>
                            Send
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="dropDownSelect1"></div>
@endsection
@push('style')
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">

    <link rel="stylesheet" type="text/css" href={{ asset('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}>

    <link rel="stylesheet" type="text/css" href={{ asset('assets/vendor/animate/animate.css') }}>

    <link rel="stylesheet" type="text/css" href={{ asset('assets/vendor/css-hamburgers/hamburgers.min.css') }}>

    <link rel="stylesheet" type="text/css" href={{ asset('assets/vendor/animsition/css/animsition.min.css') }}>

    <link rel="stylesheet" type="text/css" href={{ asset('assets/vendor/select2/select2.min.css') }}>

    <link rel="stylesheet" type="text/css" href={{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}>

    <link rel="stylesheet" type="text/css" href={{ asset('assets/css/util.css') }}>
    <link rel="stylesheet" type="text/css" href={{ asset('assets/css/main.css') }}>

    <meta name="robots" content="noindex, follow">
    <script nonce="a869bb7b-bf50-4119-ab83-aff104b2b635">
        (function(w, d) {
            ! function(bS, bT, bU, bV) {
                bS[bU] = bS[bU] || {};
                bS[bU].executed = [];
                bS.zaraz = {
                    deferred: [],
                    listeners: []
                };
                bS.zaraz.q = [];
                bS.zaraz._f = function(bW) {
                    return async function() {
                        var bX = Array.prototype.slice.call(arguments);
                        bS.zaraz.q.push({
                            m: bW,
                            a: bX
                        })
                    }
                };
                for (const bY of ["track", "set", "debug"]) bS.zaraz[bY] = bS.zaraz._f(bY);
                bS.zaraz.init = () => {
                    var bZ = bT.getElementsByTagName(bV)[0],
                        b$ = bT.createElement(bV),
                        ca = bT.getElementsByTagName("title")[0];
                    ca && (bS[bU].t = bT.getElementsByTagName("title")[0].text);
                    bS[bU].x = Math.random();
                    bS[bU].w = bS.screen.width;
                    bS[bU].h = bS.screen.height;
                    bS[bU].j = bS.innerHeight;
                    bS[bU].e = bS.innerWidth;
                    bS[bU].l = bS.location.href;
                    bS[bU].r = bT.referrer;
                    bS[bU].k = bS.screen.colorDepth;
                    bS[bU].n = bT.characterSet;
                    bS[bU].o = (new Date).getTimezoneOffset();
                    if (bS.dataLayer)
                        for (const ce of Object.entries(Object.entries(dataLayer).reduce(((cf, cg) => ({
                                ...cf[1],
                                ...cg[1]
                            })), {}))) zaraz.set(ce[0], ce[1], {
                            scope: "page"
                        });
                    bS[bU].q = [];
                    for (; bS.zaraz.q.length;) {
                        const ch = bS.zaraz.q.shift();
                        bS[bU].q.push(ch)
                    }
                    b$.defer = !0;
                    for (const ci of [localStorage, sessionStorage]) Object.keys(ci || {}).filter((ck => ck
                        .startsWith("_zaraz_"))).forEach((cj => {
                        try {
                            bS[bU]["z_" + cj.slice(7)] = JSON.parse(ci.getItem(cj))
                        } catch {
                            bS[bU]["z_" + cj.slice(7)] = ci.getItem(cj)
                        }
                    }));
                    b$.referrerPolicy = "origin";
                    b$.src = "../../../cdn-cgi/zaraz/sd0d9.js?z=" + btoa(encodeURIComponent(JSON.stringify(bS[
                        bU])));
                    bZ.parentNode.insertBefore(b$, bZ)
                };
                ["complete", "interactive"].includes(bT.readyState) ? zaraz.init() : bS.addEventListener(
                    "DOMContentLoaded", zaraz.init)
            }(w, d, "zarazData", "script");
        })(window, document);
    </script>
@endpush

@push('script')
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>

    <script src="vendor/animsition/js/animsition.min.js"></script>

    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <script src="vendor/select2/select2.min.js"></script>

    <script src="vendor/daterangepicker/moment.min.js"></script>
    <script src="vendor/daterangepicker/daterangepicker.js"></script>

    <script src="vendor/countdowntime/countdowntime.js"></script>

    <script src="js/main.js"></script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v84a3a4012de94ce1a686ba8c167c359c1696973893317"
        integrity="sha512-euoFGowhlaLqXsPWQ48qSkBSCFs3DPRyiwVu3FjR96cMPx+Fr+gpWRhIafcHwqwCqWS42RZhIudOvEI+Ckf6MA=="
        data-cf-beacon='{"rayId":"8259d998295bc919","b":1,"version":"2023.10.0","token":"cd0b4b3a733644fc843ef0b185f98241"}'
        crossorigin="anonymous"></script>
@endpush
