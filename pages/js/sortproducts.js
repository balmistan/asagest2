$(document).ready(function () {

    gridster = $(".gridster ul").gridster({
        widget_base_dimensions: [180, 90],
        helper: 'clone',
        //gridster api ?
        serialize_params: function (w, wgd) {
            return {
                pos: (wgd.row * 10) + wgd.col,
                id: w.attr("product_id")

            };
        },
    }).data('gridster');

    $("#btn").click(function () {
       $.post("../Ajax/ajax.sortproducts.php",JSON.stringify(gridster.serialize()));
    });



})