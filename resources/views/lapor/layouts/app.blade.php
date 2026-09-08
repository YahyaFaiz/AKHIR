<!DOCTYPE html>
<html lang="en">

<head>
    @include('lapor.partials.head')
</head>

<body class="min-h-screen @yield('body-class')">
    @include('lapor.partials.navbar')


    <main class="px-4 py-6">
        @yield('content')
    </main>



    @stack('scripts')
</body>

</html>
