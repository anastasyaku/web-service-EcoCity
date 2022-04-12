$(() => {
    if ($(window).width() < 1200) {
        $("#mark-selection-body").appendTo("#mark-selection-mobile");
    }
});
