$(document).ready(function(){
    $(".filters-button").wrapInner('<div class="botontext"></div>');

    $(".botontext").clone().appendTo($(".filters-button"));

    $(".filters-button").append('<span class="twist"></span><span class="twist"></span><span class="twist"></span><span class="twist"></span>');

    $(".twist").css("width", "25%").css("width", "+=3px");
});