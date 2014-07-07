<h1>Products Dashboard</h1>


<h2>Hello, <?php echo Auth::user()->firstname; ?>.</h2>
     <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

{{ Form::open(array('url'=>'products/add', 'class'=>'form-signup', 'id'=>'myForm', 'files'=>'true')) }}
<h3 class="form-signin-heading">Add Product</h3>
 
    {{ Form::text('name', null, array('class'=>'input-block-level', 'placeholder'=>"Whats the name?")) }}
    {{ Form::text('price', null, array('class'=>'input-block-level', 'placeholder'=>"What's the price?")) }}
    {{ Form::text('count', null, array('class'=>'input-block-level', 'placeholder'=>"Count the number of the products you have")) }}
    What Category is it in?
    {{ Form::text('hiddenCategory', null, array('class'=>'input-block-level', 'id'=>'hideAddProduct'))}}
    <select name = 'categories' id ='allCategories' onchange="getAllCategories(this);">
            @foreach($allCats as $cat)
                <option value='{{ $cat->catName }}' name='{{ $cat->catName }}'>{{ $cat->catName }}</option> 
            @endforeach
    </select>
    <p id="yesSubCategory">What Sub-Category is it in?</p>
    <select id="subCategory" name="subCategory" onchange="getAllCategories(this);">
        <option>Please choose category first</option>
    </select>
    {{ Form::file('files[]', array('multiple'=>true)) }}

    {{ Form::submit('Add', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}

<div class="form-signin">
    <h3 class="form-signin-heading">Edit Product</h3>
    <select name = 'products' id ='editProduct' onchange="getEditProduct(this);">
            @foreach($allProducts as $prod)
                <option value='{{ $prod->name }}' name='{{ $prod->name }}'>{{ $prod->name }}</option> 
            @endforeach
    </select>
    
    <div id='editProductsDiv'><h3 id = 'EPText'></h3>
        <img onclick="Wajj()" alt="Image hai bayi."/>
    </div><br />
    <div id="addProductImagesDiv">
        {{ Form::open(array('url'=>'products/edit', 'files'=>'true')) }}
            {{ Form::text('hidden', null, array('id'=>'HPCText')) }}
            {{ Form::text('name', null, array('class'=>'input-block-level', 'placeholder'=>"Whats the name?")) }}
            {{ Form::text('count', null, array('class'=>'input-block-level', 'placeholder'=>"Whats the count?")) }}
            {{ Form::text('price', null, array('class'=>'input-block-level', 'placeholder'=>"Whats the price?")) }}
            {{ Form::file('files[]', array('multiple'=>true)) }}
            {{ Form::submit('Edit', array('class'=>'btn btn-large btn-primary btn-block')) }}
        {{ Form::close() }}
    </div>
</div>

<div class = 'form-signin'>
    <h3 class="form-signin-heading">Delete Product</h3>
    {{ Form::open(array('url'=>'products/delete')) }}
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                </tr>
            </thead>
            <tbody>
                <input style = "display: none;" type="hidden" name="asalDel" id = 'asalDelProduct' value="acs" />
                    @foreach($allProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td><input type='submit' class="btn btn-danger" value="Delete"/></a></td>
                    </tr>
                    @endforeach
            </tbody>
        </table>
    {{ Form::close() }}
</div>













<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
    function chal (sel) {
        var str = sel.src;
        var arr = str.split('/');
        var imageAddr = arr[arr.length-1];
        window.open('delete-image?addr=' + imageAddr, '_self');
    }
    $(document).ready(function(){

        $('#hideAddProduct').hide();
        $('#editProductsDiv').hide();
        $('#HPCText').hide();
        $('#subCategory').hide();
        $('#noSubCategory').hide();
        $('#yesSubCategory').hide();
        $('#addProductImagesDiv').hide();

        $("#allCategories").val($("#target option:first").val());
        $("#editProduct").val($("#target option:first").val());
        $("#subCategory").val($("#target option:first").val());

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
            document.getElementById('asalDelProduct').value=data[0];
        };                /*END TABLE CODE*/

        $('#allCategories').change(function(){
            $.getJSON("{{ url('products/fetch-sub-category')}}", 
            { option: $(this).val() },
            function(data) {
                var i = 0;
                var model = $('#subCategory');
                model.empty();
                $('#noSubCategory').show();
                $('#yesSubCategory').hide();
                model.append("<option value='asd'>" + "No sub-category found" + "</option>");

                $.each(data, function(index, element) {
                    if(i == 0)
                        model.empty();
                    i=1;
                    $('#noSubCategory').hide();
                    $('#yesSubCategory').show();
                    $('#subCategory').show();
                    model.append("<option value='"+element.catName+"'>" + element.catName + "</option>");
                });
            });
        });

        $('#editProduct').change(function(){
            $.getJSON("{{ url('products/fetch-images')}}",
            { option: $(this).val() },
            function(data) {
                var model = $('#editProductsDiv');
                model.empty();

                $.each(data, function(index, element) {
                    $('#addProductImagesDiv').show();
                    var str = element.images;
                    var array = str.split(',');
                    model.append('<p class="alert">Click to delete</p>');
                    for(i = 0 ; i < array.length-1 ; i++) {
                        model.append("<img onclick='javascript:chal(this)' width = 200 height = 200 src='../uploads/products/" + array[i] + "'/>")
                    }
                });
            });
        });
    });

    function getAllCategories(sel)
    {
        document.getElementById('hideAddProduct').value=sel.value;
    }

    function getEditProduct (sel) {
        $('#editProductsDiv').show();
        document.getElementById('HPCText').value=sel.value;
    }
</script>
