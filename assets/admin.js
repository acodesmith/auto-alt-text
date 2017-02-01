jQuery(function () {

    jQuery('[data-aat-run="batch"]').on('click', function(){

        setTimeout( function() {

            responsive_thickbox();
            aat_batch_ajax( 'start' );
        }, 200 )
    })

})

/**
 * Resize thickbox if the device has a small screen
 */
responsive_thickbox = function() {

    var windowWidth = jQuery('body').width();

    if( windowWidth < 650 ) {

        windowWidth -= 50;

        jQuery("#TB_window").css( {
            marginLeft: '-' + parseInt((windowWidth / 2),10) + 'px',
            width: windowWidth + 'px'
        } );
    }
}

aat_batch_ajax = function( stage ) {

    console.log('running state', stage);

    jQuery.get( ajaxurl, { action: 'aat_batch', stage: stage })
        .done(function(data) {

            console.log("data",data);

            /** @var { html: '', state: '', percentage: 0 } json **/
            var json = JSON.parse( data );

            aat_batch_stage( json.stage );
            aat_update_content( json.html );
            aat_update_percentage( json.percentage );
        } )
        .fail(function(){
            aat_batch_stage( 'error' );
        });
}

aat_batch_stage = function(stage) {

    switch( stage ) {
        case 'processing':
            aat_batch_ajax('processing');
            break;
        case 'complete':
            setTimeout(function(){
                tb_remove();
            }, 500);
            break;
        case 'error':
            //@todo handle batch errors
            aat_update_content('<h2>Something has gone wrong! Please try again!</h2>');
            setTimeout(function(){
                tb_remove();
            }, 2000)
            break;
    }
}

aat_update_content = function( html ) {

    if( html !== '' ) {
        jQuery('.aat-thickbox-content .aat-content').empty()
            .html( html )
    }

}

aat_update_percentage = function( percentage ) {

    jQuery('.aat-progress-bar span').css({ width: percentage + '%' });
}