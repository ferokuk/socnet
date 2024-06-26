<!DOCTYPE html>
<html lang="en">
@include("head")
<body>

            @include("nav")


<main class="py-4">
    @yield('content')
</main>

@yield('scripts')
<script>
    document.querySelectorAll( 'oembed[url]' ).forEach( element => {
        // Create the <a href="..." class="embedly-card"></a> element that Embedly uses
        // to discover the media.
        const anchor = document.createElement( 'a' );

        anchor.setAttribute( 'href', element.getAttribute( 'url' ) );
        anchor.className = 'embedly-card';

        element.appendChild( anchor );
    } );
</script>
</body>
</html>
