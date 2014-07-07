<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device_width" initial_scale="1.0">

		{{ HTML::style('css/bootstrap.min.css') }}
		{{ HTML::style('css/main.css') }}
		{{ HTML::style('css/social-buttons.css') }}
		{{ HTML::script('js/jquery.min.js')}}

		<title>Shop using Amazon!</title>
	</head>
	<body>
		<div class="navbar navbar fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<ul class="nav">
						<li>{{ HTML::link('users/register', 'Register') }}</li>
						<li>{{ HTML::link('users/login', 'Login') }}</li>
					</ul>
				</div><!-- end container -->
			</div><!-- end navbar-inner -->
		</div><!-- end navbar navbar fixed-top -->

		<div class="container">
			@if(Session::has('message'))
				<p class="alert">{{ Session::get('message') }}
			@endif

			{{ $content }}
		</div>
	</body>
</html>