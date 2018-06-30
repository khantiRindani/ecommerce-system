//---------------------------------This file executes every action linked with buttons-------------------------------------------------------
var id = 0;
$(document).ready(function () {
    
    //------------------id=result is a division to display table------------------------
    $("#results").load("fetch_pages.php");

    var search = '';
    var sort = '';
    var page = 1;
    var order = '';
    //we wil load the page delivering all data for every action------in this way we can execute multiple actions simultaneously
    
    //------------------when serach button is clicked
    $('#mysearch').click(function () {
        search = $('#search').val();// input field having search string
        $("#results").load("fetch_pages.php", {"page": page, "search": search, "sort": sort, "order": order});
    });
    //------------------when sort button is clicked
    $('#mysort').click(function () {
        sort = $('#sort').val();// input field having sort field
        
        //if user clicked the button without entering a field
        if (sort == '') {
            alert("Please select a field for sorting");
        }
        
        //radio buttons for sorting order
        if ($('#asc').prop('checked') === true)
            order = 'asc';
        else if ($('#desc').prop('checked') === true)
            order = 'desc';
        //load
        $("#results").load("fetch_pages.php", {"page": page, "search": search, "sort": sort, "order": order});
    });
    
    //reset the data with no filtering and front page
    $('#reset').click(function () {
        search = '';
        sort = '';
        page = 1;
        order = '';
        $('#search').val('');
        $('#sort').val('');
        $("#results").load("fetch_pages.php", {"page": page, "search": search, "sort": sort, "order": order});
    });
    
    //executes code below when user click on pagination links
    $("#results").on("click", ".pagination a", function (e) {
        e.preventDefault();
        // $(".loading-div").show(); //show loading element
        var page = $(this).attr("data-page"); //get page number from link

        //get content from PHP page
        $("#results").load("fetch_pages.php", {"page": page, "search": search, "sort": sort, "order": order});

    });

    //---------------this modals are used to display pop-up forms, loaded by common-ajax file------------------
    $("#myModal").on("show.bs.modal", function (e) {
        id = $(e.relatedTarget).attr('id');
        $(this).find(".modal-body").load("../../includes/common-ajax.php?type=loadForm&id=" + id);
      
    });
    $("#myModal2").on("show.bs.modal", function () {
  
        $(this).find(".modal-body").load("../../includes/common-ajax.php?type=loadForm&form_item="+1); 
    });
    //---------------------------------------------------------------------------------------------------------
    
    //-------in user-details, display text field when other hobby is checked
    $(document).on('click','#other',function(){
                    $('#othertext').html("<input class='form-control' name='hobbytext' id='hobbytext'>");
    });
                
    //---------when delete button against a particular record is clicked
    $(document).on('click', '.delete', function ()
    {
        //we execute it by common-ajax file
        $.ajax({
            url: "../../includes/common-ajax.php?type=delete&id=" + $(this).attr('id'),//we need to deliver the id also
            //-------------response div is used to display messages
            beforeSend: function () {
                $('#response').focus();//focuses the element
                $('#response').html('<span class="text-info">Loading response...</span>');// display msg of loading
            },
            success: function (data) {
                $('#response').focus();
                $('#response').html('<span class="text-info" style="color:red">' + data + '</span>');//any msg(it can be error msg or success msg) delivered by backend file
                setTimeout(function () {
                    $('#response').fadeOut("slow");//---msg is displayed on main page...it should fade out after user sees it
                }, 5000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);//errors in ajax loading are displayed in console window
            }
        });
    });
    //--------id=update button is placed in the pop-up form
    //      when it's clicked, we send data to common-ajax file with post method------------//
    $(document).on('click', '#update', function ()
    {
        var data = new FormData();

        //Form data
        var form_data = $('#myform').serializeArray();
        $.each(form_data, function (key, input) {
            data.append(input.name, input.value);
        });
        
        $.ajax({
            type: "POST",
            url: "../../includes/common-ajax.php?type=update&id=" + id,
            data: data,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            cache: false,
            beforeSend: function () {
                $('#updateResponse').focus();
                $('#updateResponse').html('<span class="text-info">Loading response...</span>');
            },
            success: function (data) {
                //$('#response').focus();
                $('#updateResponse').html('<span class="text-info" style="color:red">' + data + '</span>');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }

        });

    });
    
    /*---------------------------Now the whole section is only for products table--------------------------------------
     * -----------------------------------------------------------------------------------------------------------------
     */
    
    //In insertion of new item, code to display image as sson as admin places it
    $(document).on('change','#fileToUpload', function () { //on file input change
        if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
        {
            $('#thumb').html(''); //clear html of output element
            var data = $(this)[0].files; //this file data

            $.each(data, function (index, file) { //loop though each file
                if (/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)) { //check supported file type
                    var fRead = new FileReader(); //new filereader
                    fRead.onload = (function (file) { //trigger function on successful read
                        return function (e) {
                            //thumb div is used to display image
                            var img = $('<img/>').addClass('thumb').attr('src', e.target.result); //create image element
                            $('#thumb').append(img); //append image to output element
                        };
                    })(file);
                    fRead.readAsDataURL(file); //URL representing the file's data.
                }
            });

        } else {
            alert("Your browser doesn't support File API!"); //if File API is absent
        }
    });
    
    //-------------id=insert_item button is clicked on the pop up form for inserting new items
    $(document).on('click', '#insert_item', function ()
    {
        
        var data = new FormData();

        //Form data
        var form_data = $('#myInsert').serializeArray();
        $.each(form_data, function (key, input) {
            data.append(input.name, input.value);
        });
        //File data
        var file_data = $('input[name="fileToUpload"]')[0].files;
        for (var i = 0; i < file_data.length; i++) {
            data.append("fileToUpload", file_data[i]);
        }
        $.ajax({
            type: "POST",
            url: "../../includes/common-ajax.php?type=insert_item" ,
            data: data,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',//necessary for sending files
            cache: false,
            beforeSend: function () {
                $('#updateRes').focus();
                $('#updateRes').html('<span class="text-info">Loading response...</span>');
            },
            success: function (data) {
                //$('#response').focus();
                $('#updateRes').html('<span class="text-info" style="color:red">' + data + '</span>');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }

        });

    });
    
    //--------when button is clicked to insert a category
    //          Here, we don't need to pass data with post method, because there is only one field
    $(document).on('click', '#insert_category', function () {
        $.ajax({
            url: "../../includes/common-ajax.php?type=insert_category&cat=" + $('#category_name').val(),//input field having value of category
            beforeSend: function () {
                $('#response').focus();
                $('#response').html('<span class="text-info">Loading response...</span>');
            },
            success: function (data) {
                $('#response').focus();
                // $('#response').fadeIn('4000');
                $('#response').html('<span class="text-info" style="color:red">' + data + '</span>');
                setTimeout(function () {
                    $('#response').fadeOut("slow");//-----------button is not placed on pop-up, therefore it sould fade out
                }, 5000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    });



});


