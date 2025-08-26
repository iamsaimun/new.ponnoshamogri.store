$.scrollIt();

function activityTrack(event){
    let shipping_name = $('.shipping_name').val();
    let shipping_mobile_number = $('.shipping_mobile_number').val();
    let shipping_address = $('.shipping_address').val();
    if(shipping_mobile_number.length < 11){
        return false;
    }
    let shipping_charge = $('.shipping_charge').val();
    let uu_id = $('.uu_id').val();
    const rawData = $('.products_data').val();
    if (!rawData || rawData.trim() === "") {
        return false; // Or show an error message
    }
    let product_datas;
    try {
        product_datas = JSON.parse(rawData).map(item => ({
            product_data_id: item.product_data_id,
            quantity: item.quantity,
            selling_price: item.selling_price,
        }));
    } catch (error) {
        console.error("Invalid JSON:", error);
        return false;
    }

    if (product_datas.length === 0) {
        return false;
    }

    $.ajax({
        url: landing_orderFailedTrackSaas_route,
        method: "POST",
        data: {
            _token,
            uid: uu_id,
            shipping_name,
            event,
            url: window.location.href,
            inventory_id: SAAS_USER_ID,
            shipping_mobile_number,
            shipping_address,
            shipping_charge,
            product_datas
        },
        success: function(){},
        error: function(){}
    });
}

$(document).on('focusout', '.information_field', function(){
    activityTrack('Information Inserted');
});

// pixel
let scrolled_50 = false;
let scrolled_90 = false;

if(fbTrackLanding){
    $(window).on('load', function() {
        tCI('PageView');
    });
}

function tCI(track_type){
    $.ajax({
        type: "POST",
        url: fbTrackLanding,
        data: {
            _token,
            track_type: track_type
        },
        success: function (response) {
            if(response == 'true'){
                console.log('F T!');
            }else{
                console.log('F T Failed');
            }
        },
        error: function(){
            console.log('F T Error!');
        }
    });
}


