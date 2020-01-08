$(document).ready(function(){

    /***********Dashboard */
    $('.toggle-info').click(function(){
        $(this).parent().next('.card-body').fadeToggle(100);

        if($(this).hasClass('fa-plus')){
            $(this).removeClass('fa-plus');
            $(this).addClass('fa-minus');
        }else{
            $(this).removeClass('fa-minus');
            $(this).addClass('fa-plus');
        }
    })

    /*************************** */

    $('.form-control').focus(function(){
        $(this).attr('data-text',$(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function(){
        $(this).attr('placeholder',$(this).attr('data-text'));
    })


    $('input').each(function(){
        
        if ($(this).attr('required') === 'required' ){

            $(this).after('<span> * </span>');
        }
    });

    $('.eye-icon').hover(function(){
        $('.password').attr('type','text');
    },function() {
        $('.password').attr('type','password');
    });

    
    //confirmation message on delete
    $('.confirm').click(function(){
        return confirm('Are you sure?');
    });

    //category view option 

})