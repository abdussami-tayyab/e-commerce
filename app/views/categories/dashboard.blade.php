<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $('#editCatDiv').hide();
        $('#delCat').hide();
        $('#addSubCatDiv').hide();
        $('#SCParentText').hide();

        var table = document.getElementsByTagName("table")[0];        //START TABLE CODE
        var tbody = table.getElementsByTagName("tbody")[0];
        tbody.onclick = function (e) {
            e = e || window.event;
            var data = [];
            var target = e.srcElement || e.target;
            while (target && target.nodeName !== "TR") {
                target = target.parentNode;
            }
            if (target) {
                var cells = target.getElementsByTagName("td");
                for (var i = 0; i < cells.length; i++) {
                    data.push(cells[i].innerHTML);
                }
            }
            document.getElementById('asalDelCat').value=data[0];
        };                /*END TABLE CODE*/
    });
    function getEditCat (sel) {
        $('#addSubCatDiv').show();
        $('#editCatDiv').show();
        $('#ECText').text('Edit ');
        $('#ECText').append(sel.value);
        $('#HECText').val(sel.value);
        $('#SCParentText').val(sel.value);
        $('#HECText').hide();
        document.getElementsByName('SCText')[0].placeholder='Add Sub-Category for ' + sel.value;
    }
</script>

<h1>Categories Dashboard</h1>

<h2>Hello, {{Auth::user()->firstname;}}.</h2>
     <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

{{ Form::open(array('url'=>'categories/add', 'class'=>'form-signin')) }}
    <h3 class="form-signin-heading">Add Category</h3>
    {{ Form::text('category', null, array('class'=>'input-block-level', 'placeholder'=>'Add Category')) }}
    {{ Form::submit('Add', array('class'=>'btn btn-large btn-primary btn-block')) }}
{{ Form::close() }}
<div class="form-signin">
    <h3 class="form-signin-heading">Edit Category</h3>
    <select name = 'categories' id ='editCat' onchange="getEditCat(this);">
        @foreach($allCats as $cat)
            <option value='{{ $cat->catName }}' name='{{ $cat->catName }}'>{{ $cat->catName }}</option> 
        @endforeach
    </select>
    
    <div id='editCatDiv'><h3 id = 'ECText'></h3>
        {{ Form::open(array('url'=>'categories/edit')) }}
            {{ Form::text('hidden', null, array('id'=>'HECText')) }}
            {{ Form::text('category', null, array('class'=>'input-block-level', 'placeholder'=>'Edit Category Name')) }}
            {{ Form::submit('Edit', array('class'=>'btn btn-large btn-primary btn-block')) }}
        {{ Form::close() }}
    </div>
    <div id='addSubCatDiv'><h3 id = 'ECText'></h3>
        {{ Form::open(array('url'=>'categories/add-sub')) }}
            {{ Form::text('SCParent', null, array('id'=>'SCParentText')) }}
            {{ Form::text('SCText', null, array('class'=>'input-block-level', 'placeholder'=>'Add Sub-Category')) }}
            {{ Form::submit('Add', array('class'=>'btn btn-large btn-primary btn-block')) }}
        {{ Form::close() }}
    </div>
</div>

<div class = 'form-signin'>
    <h3 class="form-signin-heading">Delete Category</h3>
    {{ Form::open(array('url'=>'categories/delete')) }}
        <table class="table">
            <thead>
                <tr>
                    <th>Category Name</th>
                </tr>
            </thead>
            <tbody>
                <input style = "display: none;" type="hidden" name="asalDel" id = 'asalDelCat' value="acs" />
                @foreach($allCats as $cat)
                <tr>
                    <td>{{ $cat->catName }}</td>
                    <td><input type='submit' class="btn btn-danger" value="Delete" /></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    {{ Form::close() }}
</div>