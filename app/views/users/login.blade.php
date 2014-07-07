<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
<script type="text/javascript">
	function openPage () {
		window.open("http://localhost:801/loginFacebook")
	}
</script>

<div class='form-signin'>
    <h2 class="form-signin-heading">Please Login</h2>
	<button class="btn btn-facebook" onclick="openPage();"><i class="fa fa-facebook"></i> | Connect with Facebook</button><br/><br/>
	{{ Form::open(array('url'=>'users/signin')) }}

	    {{ Form::text('email', null, array('class'=>'input-block-level', 'placeholder'=>'Email Address')) }}
	    {{ Form::password('password', array('class'=>'input-block-level', 'placeholder'=>'Password')) }}
	 
	    {{ Form::submit('Login', array('class'=>'btn btn-large btn-primary btn-block'))}}
	{{ Form::close() }}
</div>
