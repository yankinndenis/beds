$('#btn-filter').on('click', function () {
    var btn = $('#filters-body');
    if (btn.css('display') === 'none'){
        btn.css('display','flex')
    } else {
        btn.css('display','none')

    }
})

$('.filter-wrap-block').on('click', function (){
    if ($(this).hasClass('active-filter') === false){
        $(this).addClass('active-filter');
    } else {
        $(this).removeClass('active-filter')
    }
})