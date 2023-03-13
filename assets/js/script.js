let site_url = document.location.origin;
let now = new Date();
today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

new Litepicker({
  element: document.getElementById('date-3_1'),
  elementEnd: document.getElementById('date-3_2'),
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
    lockDaysFilter: (day) => {
       const d = day.getDay();
       return [1,2,3,5,6].includes(d);

    },
  position: 'left'
})


// $("body").on('click','#date-3_1', function () {
//     $('.container__days .day-item').each(function() {
//         let e = $(this);
//     if (e.hasClass('is-start-date')){
//         e.removeClass('is-start-date')
//     }
//     if (e.hasClass('is-end-date')){
//         e.removeClass('is-end-date')
//     }
//     if (e.hasClass('is-in-range')){
//         e.removeClass('is-in-range')
//     }
//     })
//     function interval() {
//         $('.container__days .day-item').addClass('is-locked');  
//         $('.container__days .day-item').attr('tabindex', "-1"); 
//         addAvDays();
//     }
//     setTimeout(interval, 1); 
// });
// $("body .month-item").on('click', function () {
//     function interval() {
//         $('.container__days .day-item').addClass('is-locked');  
//         $('.container__days .day-item').attr('tabindex', "-1"); 
//         addAvDays();
//     }
//     setTimeout(interval, 1); 
// });

// $("body").on('mouseover','.day-item', function () {
//     addAvailable();
// });

// function addAvDays(){
//     $('.container__days .day-item').each(function() {
//         let e = $(this);

//         function interval() {
//             let time = $(e).attr('data-time');
//             let time_int = parseInt(time);
//             const date = new Date(time_int);
//             let d = date.getDay();
//             let now = new Date();
//             let today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
//             if(date > today){
//                 if(d == 4 || d == 0){
//                     $(e).removeClass('is-locked');
//                     $(e).attr('tabindex',"0"); 
//                 }
//             }
//         }
//         setTimeout(interval, 100); 
//     });
// }

// function addAvailable(){
//     $('.container__days .day-item').each(function() {
//         let start_date = $('.is-start-date').html();
//         let now = new Date();
//         let today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

//         if(start_date !== undefined){
//             if($(this).hasClass('is-start-date')){
//                 let e = $(this);
//                 function interval() {
//                     let time = $(e).attr('data-time');
//                     let time_int = parseInt(time);
//                     $('.container__days .day-item').addClass('is-locked');
//                     $('.container__days .day-item').attr('tabindex', "-1");
//                     $(e).removeClass('is-locked');
//                     $(e).attr('tabindex',"0"); 
//                     const date = new Date(time_int);
//                     const date_min = new Date(time_int);

//                     let datePlus,dateMin,days=4,i=0,dates_arr = [];
//                     while(i < 60){
//                         datePlus = date.setDate(date.getDate() + days);
//                         if(date > today){
//                             $('[data-time="'+datePlus+'"]').removeClass('is-locked');
//                             $('[data-time="'+datePlus+'"]').attr('tabindex',"0");
//                         }
//                         if(days==4)
//                             {days = 3}
//                         else{days = 4}
//                         i = i+days;
//                     } 
//                     i=0;

//                     if(date_min.getDay() == 0){
//                         days=3;
//                     }
//                     while(i < 60){
//                         dateMin = date_min.setDate(date_min.getDate() - days);
//                         if(date_min > today){
//                             $('[data-time="'+dateMin+'"]').removeClass('is-locked');
//                             $('[data-time="'+dateMin+'"]').attr('tabindex',"0");
//                         }
//                         if(days==4)
//                             {days = 3}
//                         else{days = 4}
//                         i = i+days;
//                     } 
//                 }
//                 setTimeout(interval, 1); 
//                 let time = $(e).attr('data-time');
//                 let time_int = parseInt(time);
//                 $('.container__days .day-item').addClass('is-locked');  
//                 $('.container__days .day-item').attr('tabindex', "-1"); 
//                 $(e).removeClass('is-locked');
//                 $(e).attr('tabindex',"0"); 
//                 const date = new Date(time_int);
//                 const date_min = new Date(time_int);

//                 let datePlus,dateMin,days=4,i=0,dates_arr = [];
//                 while(i < 60){
//                     datePlus = date.setDate(date.getDate() + days);
//                     if(date > today){
//                         $('[data-time="'+datePlus+'"]').removeClass('is-locked');
//                         $('[data-time="'+datePlus+'"]').attr('tabindex',"0");
//                     }
//                     if(days==4)
//                         {days = 3}
//                     else{days = 4}
//                     i = i+days;
//                 } 
//                 i=0;
//                 if(date_min.getDay() == 0){
//                         days=3;
//                     }

//                 while(i < 60){
//                     dateMin = date_min.setDate(date_min.getDate() - days);
//                     if(date_min > today){
//                         $('[data-time="'+dateMin+'"]').removeClass('is-locked');
//                         $('[data-time="'+dateMin+'"]').attr('tabindex',"0");
//                     }
//                     if(days==4)
//                         {days = 3}
//                     else{days = 4}
//                     i = i+days;
//                 } 
//             }
//         }else{
//             addAvDays();
//         }  
//     });
// }

// function previosMonth(e){
//     $('.container__days .day-item').addClass('is-locked');  
//     $('.container__days .day-item').attr('tabindex', "-1"); 
//     addAvDays();       
// }
// function nextMonth(e){
//     $('.container__days .day-item').addClass('is-locked');  
//     $('.container__days .day-item').attr('tabindex', "-1"); 
//     addAvDays();       
// }

// setInterval(function() {
//     addAvailable();
// }, 100);

$("body").on('click','.beds_add_to_cart', function (e) {
    e.preventDefault();
    let custom_price = $(this).attr('data-custom_price');
    let product_id = $(this).attr('data-product_id');
    let add_button = $(this);
    let date_from = $("#date-3_1").val()
    let date_to = $("#date-3_2").val()
    let persons = $("#adult-select").val()
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
});

$('body').on('click','.filter-wrap-block', function(){
    filter_products();
});
$('body').on('change','.range-filter', function(){
    filter_products();
});

function filter_products(){
    let sovrum = $('#sovrum').val();
    let skidlift = $('#skidlift').val();
    let param1 = [];
    let param2 = [];
    $( ".top_blocks .filter-case .active-filter" ).each(function() {
        let data = $(this).attr('data-item');
        param1.push(data);
    });
    $( ".bottom_blocks .filter-case .active-filter" ).each(function() {
        let data = $(this).attr('data-item');
        param2.push(data);
    });
    let param1String = param1.join('/');
    let param2String = param2.join('/');

    $.ajax({
        type: 'POST',
        url: site_url + '/wp-admin/admin-ajax.php',
        data: {
            param1:param1String,
            param2:param2String,
            sovrum:sovrum,
            skidlift:skidlift,
            action: 'filter_products'
        },
    error: function(error){
        alert('error');
        },
    beforeSend: function(){
            $('body').append('<div class="backmodal"><div></div></div>');
        },
    success: function(data){
            $('.backmodal').remove();
            $('.hotels').html(data);
        }
    }); //endajax
}