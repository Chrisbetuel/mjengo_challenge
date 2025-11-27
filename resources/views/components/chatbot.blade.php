@auth
    {{-- Only show chatbot for authenticated users, not on login/register pages --}}
    @if (!request()->routeIs('login', 'register', 'admin.login', 'password.forgot', 'password.reset'))
        <script src="{{ asset('js/chatbot.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
        
        <script>
            // Mark body as authenticated for chatbot initialization
            document.body.classList.add('authenticated');
        </script>
    @endif
@endauth
