let site_url = document.location.origin;

let unavailDates = []
let product_id = $('.beds_add_to_cart').attr('data-product_id');
let av_days = [];
$.ajax({
    type: 'POST',
    url: site_url + '/wp-admin/admin-ajax.php',
    data: {
        product_id: product_id,
        action: 'getAvailByRoomID'
    },
    cache: false,
    error: function(error){
        alert(error);
    },
    success: function(data){
        data = $.parseJSON(data.slice(0,-1));
        av_days = data;
        
    }
});
let bookedDays = [];
$.ajax({
    type: 'POST',
    url: site_url + '/wp-admin/admin-ajax.php',
    data: {
        product_id: product_id,
        action: 'getPeriodByRoomID'
    },
    cache: false,
    error: function(error){
        alert(error);
    },
    success: function(data){
        data = $.parseJSON(data.slice(0,-1));
        bookedDays = data;

    }
});
$.each(bookedDays, function(key, val) {
    var dates1 = val.split("-");
    var newDate = dates1[1]+"/"+dates1[2]+"/"+dates1[0];
    let tsDatebooked = new Date(newDate).getTime()
    // $('.container__days .day-item').data('time')
    if(tsDatebooked > today){
        // $('*[data-time="'+tsDatebooked+'"]').addClass('is-locked')
        // $('*[data-time="'+tsDatebooked+'"]').attr('tabindex',"-1");      
        $('*[data-time="'+tsDatebooked+'"]').css('background',"#fff");
        $('*[data-time="'+tsDatebooked+'"]').css('color',"#fff");

    }
    $(this).attr('onclick',"triger()");
});

$("body").on('click','.beds_add_to_cart', function (e) {
    if ($('.beds_add_to_cart').hasClass( "notBuy" ).toString() === 'false'){
        e.preventDefault();
        let custom_price = $(this).attr('data-custom_price');
        let product_id = $(this).attr('data-product_id');
        let add_button = $(this);
        let date_from = $("#date-start").val()
        let date_to = $("#date-end").val()
        let persons = $("#adult").val()
        console.log(product_id);
        $.ajax({
            type: 'POST',
            url: site_url + '/wp-admin/admin-ajax.php',
            data: {
                product_id: product_id,
                custom_price: custom_price,
                date_from:date_from,
                date_to:date_to,
                persons:persons,
                action: 'addtocart'
            },
            dataType: "json",
            cache: false,
            error: function(error){
                alert('error');
            },
            beforeSend: function(){
                $('body').append('<div class="backmodal"><div></div></div>');
            },
            success: function(data){
                $('.backmodal').remove();
                $(add_button).closest('.content_bottom').children('.result').addClass('active');
                $(add_button).closest('.buy_button').children('.result').addClass('active');
                location = site_url+"/index.php/cart/";
            }
        }); //endajax
    } else {
        return false;
    }

});

$('.slider').slick({
  dots: false,
  arrows:true,
  infinite: true,
  speed: 500,
  fade: true,
  cssEase: 'linear'
});

let now = new Date();
today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

new Litepicker({
    element: document.getElementById('date-start'),
    elementEnd: document.getElementById('date-end'),
    singleMode: false,
    showWeekNumbers: true,
    numberOfMonths: 2,
    minDate: today,
    tooltipText:{"one":"night","other":"nights"},
    resetButton:false,
    buttonText:{"cancel":"Cancel","reset":"Reset"},
    tooltipNumber: (totalDays) => {
        return totalDays - 1;
    },
    disallowLockDaysInRange: true,
    lockDaysFilter: (date1, date2, pickedDates) => {
        return !av_days.includes(date1.format('YYYY-MM-DD'));
      },
    position: 'left'
});


$("body").on('click','#date-start', function () {
    addAvailable();
});
$("body .month-item").on('click', function () {
    addAvailable();
});
$("body").on('click','.day-item', function () {
    alert('test');
    addAvailable();
});
$("body").on('click','.day-item', function () {
    alert('test');
    addAvailable();
});
$("body").on('mouseover','.day-item', function () {
    addAvailable();
});

function addAvailable(){

        let start_date = $('.is-start-date').html();
        let now = new Date();
        let today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        let e = $(this);
        let time = $(e).attr('data-time');
        let time_int = parseInt(time);
        const date = new Date(time_int);
        const date_min = new Date(time_int);

        let datePlus,dateMin,days=4,i=0,dates_arr = [];
        $.each(bookedDays, function(key, val) {
            var dates1 = val.split("-");
            var newDate = dates1[1]+"/"+dates1[2]+"/"+dates1[0];
            let tsDatebooked = new Date(newDate).getTime()
            // $('.container__days .day-item').data('time')
            if(tsDatebooked > today){
                // $('*[data-time="'+tsDatebooked+'"]').addClass('is-locked')
                // $('*[data-time="'+tsDatebooked+'"]').attr('tabindex',"-1");
                $('*[data-time="'+tsDatebooked+'"]').css('background',"#fff");
                $('*[data-time="'+tsDatebooked+'"]').css('color',"#fff");
            }
        });
        function interval() {
            let time = $(e).attr('data-time');
            let time_int = parseInt(time);
            const date = new Date(time_int);
            const date_min = new Date(time_int);
            let datePlus,dateMin,days=4,i=0,dates_arr = [];
            $.each(bookedDays, function(key, val) {
                var dates1 = val.split("-");
                var newDate = dates1[1]+"/"+dates1[2]+"/"+dates1[0];
                let tsDatebooked = new Date(newDate).getTime()
                // $('.container__days .day-item').data('time')
                if(tsDatebooked > today){
                    // $('*[data-time="'+tsDatebooked+'"]').addClass('is-locked')
                    // $('*[data-time="'+tsDatebooked+'"]').attr('tabindex',"-1");
                    $('*[data-time="'+tsDatebooked+'"]').css('background',"#fff");
                    $('*[data-time="'+tsDatebooked+'"]').css('color',"#fff");
                }
            });
        }
        setTimeout(interval, 1); 
}

function previosMonth(e){
    addAvailable();
}
function nextMonth(e){
    addAvailable();
}

setInterval(function() {
    addAvailable();
}, 100);

const element = document.getElementById("date-end");
const elementS = document.getElementById("date-start");
const elementA = document.getElementById("adult");

let dateEnd = element.value;
let dateStart = elementS.value;
let urlGo = document.location.href.replace(document.location.search,'');
var interval = setInterval(function() {if ((element.value !== dateEnd) || (elementS.value !== dateStart)){
    // console.log(element.value)
    // console.log(elementS.value)
    $('.beds_add_to_cart').addClass('notBuy');
    window.location.href = urlGo+'?date_start='+elementS.value+'&date_end='+element.value+'&adult='+elementA.value;
    clearInterval(interval)
};}, 500);

$("body").on('click','#date-start', function () {
    let css = $('.litepicker').css('left');
    let winWidth = $(window).width();
    if (winWidth < '1440' & winWidth > 660){
        let newCss = css.split('px')
        let newLeft = newCss[0]-150;
        newCss = newLeft+'px'
        let p = newCss.split('px')
        if (p[0] > (winWidth/2)){
            $('.litepicker').css('left',newCss);
        }
    }
})
$("body").on('click','#date-end', function () {
    let css = $('.litepicker').css('left');
    let winWidth = $(window).width();
    if (winWidth < '1440'){
        let newCss = css.split('.')
        let newLeft = newCss[0]-150;
        newCss = newLeft+'.'+newCss[1]
        let p = newCss.split('px')

        if (p[0] > (winWidth/2)){
            $('.litepicker').css('left',newCss);
        }
    }
})