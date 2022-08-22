<!-- Fonts -->
{{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> --}}

<!-- Styles -->
@vite(['resources/css/app.css'])
<!-- Scripts -->
@env(['staging', 'production'])
    <x-splitbee />
@endenv
@vite(['resources/js/app.js'])
