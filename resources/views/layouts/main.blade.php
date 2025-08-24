<!DOCTYPE html>
<html lang="en">
    @include('layouts.header')
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-fixed aside-secondary-disabled">
		<div class="d-flex flex-column flex-root">
			<div class="page d-flex flex-row flex-column-fluid">
				@include('layouts.sidebar')
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                    @include('layouts.navbar')
					@yield('content')
					@include('layouts.footer')
				</div>
			</div>
		</div>
        @include('layouts.scroll')
		@include('layouts.scripts')
	</body>
</html>