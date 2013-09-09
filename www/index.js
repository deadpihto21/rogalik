jQuery(document).ready(function(){
        $('a').on('click', function(e){
            e.preventDefault();
            var link = jQuery(this).attr('href');
            jQuery('#container').load(''+link+'#main-block');
        });
    jQuery('.box_table table').find('td').each(function(){
        jQuery(this).on('click',function(){
            drawLine(this);
        });
    });
});
    jQuery(window).load(function(){
        $(document).keyup(function(e) {
            e.preventDefault();
            if(e.keyCode == 37) {
                jQuery('#moveleft').click();
            }
            else if (e.keyCode == 38){
                jQuery('#moveup').click();
            }
            else if (e.keyCode == 39){
                jQuery('#moveright').click();
            }
            else if (e.keyCode == 40){
                jQuery('#movedown').click();
            }
            else if (e.keyCode == 13){
                jQuery('#pickup').click();
            }

        });
    });
/*var json = $('#json').text();
var global_obj = $.parseJSON(json);
console.log(global_obj);
for (var i = 0; i<global_obj.Width; i++){
    jQuery('#tab1').append('<tr class='+i+'>');
}
jQuery('#tab1 tr').each(function(){
    for (var i = 0; i<global_obj.Height; i++){
        jQuery(this).append('<td class='+i+'>');
    }
}); */



function drawLine(element) {
    jQuery('.path').removeClass('path');
    var x1 = jQuery('.hero').parent().index() + 1;
    var y1 = jQuery('.hero').parent().parent().index() + 1;
    var x2 = jQuery(element).index() + 1;
    var y2 = jQuery(element).parent().index() + 1;
    var deltaX = Math.abs(x2 - x1);
    var deltaY = Math.abs(y2 - y1);
    var signX = x1 < x2 ? 1 : -1;
    var signY = y1 < y2 ? 1 : -1;
    var error = deltaX - deltaY;
    jQuery('.box_table table').find('td').each(function(){
        if(jQuery(this).index() + 1 == x2 && jQuery(this).parent().index() + 1 == y2 && !jQuery('table').find('.wall').length){
            jQuery(this).addClass('path');
        }
    });
    while(x1 != x2 || y1 != y2) {

        var error2 = error * 2;
        if(error2 > -deltaY) {
            error -= deltaY;
            x1 += signX;
        }
        if(error2 < deltaX) {
            error += deltaX;
            y1 += signY;
        }
        jQuery('.box_table table').find('td').each(function(){
            if(jQuery(this).index() + 1 == x1 && jQuery(this).parent().index() + 1 == y1){
                if(!jQuery(this).children('.wall').length){
                    jQuery(this).addClass('path');
                }
                if(jQuery(this).children('.wall').length){
                    console.log('STENA, BLYAT');
                    x1 = x2;
                    y1 = y2;
                }
            }
        });
    }
}


