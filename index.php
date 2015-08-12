<?php
require_once('class/dblogin.inc.php');
require_once('class/config.inc.php');
require_once('global/global_vars.php');
require_once("class/Pagination.class.php");
require_once("class/class.phpmailer.php");
require_once('global/functions.php');
require_once('global/global_meta.php');
if (file_exists('pages/' . $page . '/local.css')) {
    echo '<link rel="stylesheet" type="text/css" href="pages/' . $page . '/local.css"/>';
}
require_once('global/header.php');
if (file_exists('pages/' . $page . '/display.php')) {
    if (file_exists('pages/' . $page . '/local_vars.php')) {
        require('pages/' . $page . '/local_vars.php');
    }
    require 'pages/' . $page . '/display.php';
} else {
    require 'pages/home/display.php';
}
require('global/footer.php');
?>


<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/static.getclicky.com.js" type="text/javascript"></script>
<script type="text/javascript">try {
        clicky.init(100728789);
    } catch (e) {
    }</script>
<?php if (isset($_SESSION['error'])) unset($_SESSION['error'], $_SESSION['msg']); ?>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/custom-form-elements.js"></script>
<script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<?php
if (file_exists('pages/' . $page . '/local.js')) {
    echo '<script type="text/javascript" src="pages/' . $page . '/local.js"></script>';
}
?>
<script type="text/javascript" src="js/custom.js"></script>
<?php
if ($lang == 'cn') {
    echo '<script type="text/javascript" src="js/custom_cn.js"></script>';
} else {
    echo '<script type="text/javascript" src="js/custom_en.js"></script>';
}
?>

</body>
<script>

    var redirecturl;
    var totallayers;
    $("body").delegate('.show_popup', 'click', function ()
    {

        var resturante = $(this).attr("i");
        var add_item = $(this).attr("dir");
        var id = $(this).attr("s");
        var menu_name = $(this).attr("b");
        var page = "menu";
        var size = $(this).attr("a");
        
        var lang = '<?php if(@$lang=="cn"){echo "cn";}else{echo "en";}?>';

        redirecturl = "page=" + page + "&restaurant=" + resturante + "&add_item=" + add_item + "&size=" + size + "&id=" + id;
        //alert(redirecturl);
        // <i><?php echo $item['price']; ?><a href="index.php?page=menu&restaurant=<?php echo urlText($res['name']); ?>&add_item=<?php echo $item['id']; //             ?>&size=0&id=<?php echo $res['id']; ?>"><img src="img/add.png" alt="" /></a></i>
        //alert(menu_id);
        $.ajax({type: "GET",
            url: "<?php echo 'menu-layers.php'; ?>",
            data: "page=" + page + "&restaurant=" + resturante + "&add_item=" + add_item + "&size=" + size + "&id=" + id+ "&lang=" + lang,
            async: false, //blocks window close
            success: function (data) {
                //alert(data);
                if (data != 1)
                {
                    $('.makelayer').append(data);
                    totallayers = $('#total_layers').val();
                    //alert(totallayers);
                    $('#myModal1').show();
                }
                else {
                    $('#myModal1').hide();
                    $.ajax({type: "GET",
                        url: "<?php echo 'set-cart-session.php'; ?>",
                        data: redirecturl + "layerandattribute=",
                        success: function (data) {
                            //alert(data);

                            window.location.reload();
                        }

                    });
                }

            }

        });
        var layer_attributs = "A";

        $("body").delegate('.skip', 'click', function ()
        {
            var attr_id = "#attributeid" + $(this).attr('dir');
            var layer_values = $(attr_id).val();
            //alert(layer_values);
            layer_attributs = layer_attributs + "*" + $(this).attr('dir') + layer_values;
            //alert(layer_attributs);
            var layer_no = parseInt($(this).parent().parent().attr("b")) + 1;
            $(this).parent().parent().hide();
            var nextlayer = "#layer" + layer_no;

            $(nextlayer).show();
            //alert(totallayers+'<'+layer_no)
            if (layer_no < totallayers)
            {
                $(nextlayer).show();
            }
            else
            {
                $.ajax({type: "GET",
                    url: "<?php echo 'set-cart-session.php'; ?>",
                    data: redirecturl + "&layerandattribute=" + layer_attributs,
                    success: function (data) {
                        //alert(data);

                        window.location.reload();
                    }

                });



                $('#myModal1').hide();
                $('.fatchdata').remove();
                //window.location.reload();
            }
        });


        $('.close').click(function () {

            $('#myModal1').hide();
            $('.fatchdata').remove();

        });



        $("body").delegate('.back_button', 'click', function ()
        {
            var current = $(this).parent().parent().attr("b");
            var previous = $(this).parent().parent().attr("b") - 1;
            if (previous > 0) {
                $('#layer' + current).hide();
                $('#layer' + previous).show();
            }

        });




    }
    );
    /*ajax over cart
     $.ajax({type: "POST",
     
     url: "<?php echo 'pages/menu/sidebar-order.php'; ?>",
     
     
     
     success: function (data) {
     
     $(".orderbar ").append(data);
     }
     
     });*/

    // add extra 
    $("body").delegate('.addbutton', 'click', function ()
    {

        var attribute_id = $(this).attr("dir");
        var a = $(this).attr('a');
        var max_selected = $('#max_selected' + a).val();
        var max = parseInt(max_selected) + 1;
        var max_limit = parseInt($('#max_selected' + a).attr('max_value'));
        var max_input_dir = $('#max_selected' + a).attr('dir');



        if (max > max_limit && attribute_id != max_input_dir) {
            alert('you already have selected maximum attributes');
            return false;
        } else {
            var attr_val = parseInt($("#attr_id-" + attribute_id).val());
            var max_attr_limit = attr_val + 1;
            var attr_limit = parseInt($("#attr_id-" + attribute_id).attr('max'));
            if (max_attr_limit > attr_limit) {
                alert('Maximum attribute selected');
                return false;
            } else {
                $("#attr_id-" + attribute_id).val(max_attr_limit);
            }
            if (attribute_id != max_input_dir) {
                $('#max_selected' + a).val(max);
            }
            $('#max_selected' + a).attr('dir', attribute_id);
        }





        var layer_id = $(this).attr("q");

        var name = $(this).attr("b");
        var attr_price = parseFloat($(this).attr("s"));
        //alert(price);

        var rowid = $(this).attr("a");
        var yes = "#yes" + rowid;
        var skip = "#skip" + rowid;
        $(yes).show();
        $(skip).hide();
        //this is for total price of a specfic layer
        var totalprice_id = "#totalPrice" + layer_id;
        var total_price = parseFloat($(totalprice_id).html());

        var new_total = total_price + attr_price;
        new_total = new_total.toFixed(2);
        $(totalprice_id).html(new_total);

        //alert(new_total);

        //this is for adding attributes id to a hidden field
        var sttrib_id = "#attributeid" + layer_id;
        var hidden_value = $(sttrib_id).attr("value");





        if (hidden_value == "")
        {

            //alert("if");
            $(sttrib_id).val("," + attribute_id);

        }
        else {
            //alert("else");
            new_val = hidden_value + "," + attribute_id;

            $(sttrib_id).val(new_val);
        }

        // minus div show 
        // minus div quantity increase
        var minus_quantity_id = "#attr_quantity" + attribute_id;
        var minus_quantity = parseInt($(minus_quantity_id).html());

        var minus_new = minus_quantity + 1;
        $(minus_quantity_id).html(minus_new);

        // minus div price increase
        var single_total_id = "#single_total" + attribute_id;
        var single_total = parseFloat($(single_total_id).html());

        var newtotal = single_total + attr_price;
        newtotal = newtotal.toFixed(2);
        $(single_total_id).html(newtotal);



        var minus_id = "#minus" + attribute_id;
        $(minus_id).show();




    });


    // remove attributes of a layer				
    $("body").delegate('.removebutton', 'click', function ()
    {
        var attribute_id = $(this).attr("dir");
        var a = $(this).attr('q');
        var max_selected = parseInt($('#max_selected' + a).val());
        if (max_selected > 0) {
            var max = max_selected - 1;
        }
        var aid = parseInt(attribute_id);
        var attr_quantity = parseInt($("#attr_quantity" + aid).html());
        //alert(attr_quantity);
        if (attr_quantity == 1) {
            $('#max_selected' + a).val(max);
        }
        //attr_quantity


        var layer_id = $(this).attr("q");
        var name = $(this).attr("b");
        var attr_price = parseFloat($(this).attr("s"));
        $('#max_selected' + a).attr('dir', 0);

        var attr_val = parseInt($("#attr_id-" + attribute_id).val());
        var max_attr_limit = attr_val - 1;
        $("#attr_id-" + attribute_id).val(max_attr_limit);



        // total price minus
        var totalprice_id = "#totalPrice" + layer_id;
        var total_price = parseFloat($(totalprice_id).html());

        var new_total = total_price - attr_price;
        new_total = new_total.toFixed(2);
        $(totalprice_id).html(new_total);



        // minus div quantity increase
        var minus_id = "#minus" + attribute_id;
        var minus_quantity_id = "#attr_quantity" + attribute_id;
        var minus_quantity = parseInt($(minus_quantity_id).html());


        var minus_quantity_id = "#attr_quantity" + attribute_id;
        var minus_quantity = parseInt($(minus_quantity_id).html());


        var minus_new = minus_quantity - 1;
        $(minus_quantity_id).html(minus_new);

        // minus div price increase
        var single_total_id = "#single_total" + attribute_id;
        var single_total = parseFloat($(single_total_id).html());

        var newtotal = single_total - attr_price;
        newtotal = newtotal.toFixed(2);
        $(single_total_id).html(newtotal);



        //minus hidden attribute amendments
        var sttrib_id = "#attributeid" + layer_id;
        var hidden_value = $(sttrib_id).attr("value");
        var amended_value = hidden_value.replace("," + attribute_id, "");
        $(sttrib_id).val(amended_value);

        // minus div hide
        if (minus_new == 0)
        {
            $(minus_id).hide();

        }





    });



</script>
</html>
