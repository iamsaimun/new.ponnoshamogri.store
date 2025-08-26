<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">

    @yield('head')

    @vite('resources/landing/css/app.css', 'build-landing')

    @if(env('APP_FB_TRACK'))
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{env("PIXEL_ID")}}');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{env("PIXEL_ID")}}&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
    @endif
</head>
<body class="bg-[#f4f8fa]">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <div class="loader" style="display: none;">
        <img src="data:image/png;base64,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" alt="Loader Animation">
    </div>

    @yield('master')

    <footer class="border-t border-[#DDDDDD]">
        <div class="container py-5 md:py-10">
            <div class="flex gap-2 flex-col md:flex-row md:gap-5 items-center justify-center">
                <a href="#" class="text-base text-[#566376] hover:text-black transition-all duration-300">Refund Policy</a>
                <a href="#" class="text-base text-[#566376] hover:text-black transition-all duration-300">Privacy Policy</a>
                <a href="#" class="text-base text-[#566376] hover:text-black transition-all duration-300">Terms & Conditions</a>
            </div>
        </div>
    </footer>

    <script>
        $(document).on('submit', '.disableDoubleClickOnSubmit', function(){
            $('.loader').show();
        });
    </script>
    <!-- Sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.3.0/dist/sweetalert2.all.min.js"></script>
    <script src="{{asset('landing/asset/scrollIt.js/scrollIt.min.js')}}"></script>

    <script>
        let _token = "{{csrf_token()}}";
        let SAAS_USER_ID = "{{env('SAAS_USER_ID')}}";
        let landing_orderFailedTrackSaas_route = "{{route('landing.orderFailedTrackSaas')}}";
    </script>

    @if(env('APP_FB_TRACK') && env('PIXEL_ID'))
    <script>
        let fbTrackLanding = "{{route('fbTrackLanding')}}";
    </script>
    @endif

    <script src="{{asset('landing/main.js')}}?c=5"></script>


    <script>
        // Alert Script
        const Toast = Swal.mixin({
            toast: true,
            position: 'center-center',
            showConfirmButton: false,
            background: '#E5F3FE',
            timer: 4000
        });
        function cAlert(type, text){
            Toast.fire({
                icon: type,
                title: text
            });
        }
    </script>

    @if(session('success-alert'))
    <script>
        cAlert('success', "{{session('success-alert')}}");
    </script>
    @endif

    @if(session('error-alert'))
    <script>
        cAlert('error', "{{session('error-alert')}}");
    </script>
    @endif

    @yield('footer')
</body>
</html>
