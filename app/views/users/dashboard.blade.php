<h1>Your Dashboard</h1>


<h2>Hello, <?php echo Auth::user()->firstname; ?>!</h2>
     <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

{{ Form::open(array('url'=>'users/edit', 'class'=>'form-signup', 'files'=>'true')) }}
<h3 class="form-signin-heading">Edit Profile</h3>
 
    {{ Form::text('firstname', null, array('class'=>'input-block-level', 'placeholder'=>Auth::user()->firstname)) }}
    {{ Form::text('lastname', null, array('class'=>'input-block-level', 'placeholder'=>Auth::user()->lastname)) }}
    {{ Form::text('email', null, array('class'=>'input-block-level', 'placeholder'=>Auth::user()->email)) }}
    {{ Form::file('file') }}

    {{ Form::submit('Edit', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}