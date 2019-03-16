jQuery(document).ready(function() {
    var content = ".foldout__content";
    jQuery(content).hide();

    jQuery(".foldout__toggle").click(function() {
        if (jQuery(content).hasClass('hidden')){
            jQuery(content).removeClass('hidden');
        }
        jQuery(this).next(content).toggle('fast');
        if (jQuery(this).hasClass('open')){
            jQuery(this).removeClass('open');
        } else {
            jQuery(this).addClass('open');
        }

        var jQueryicon = '.handlediv i';
        if (jQuery(this).prev().find('i.dashicons').hasClass('dashicons-plus')){
            jQuery(this).prev().find('i.dashicons').removeClass('dashicons-plus');
            jQuery(this).prev().find('i.dashicons').addClass('dashicons-no');

        } else if (jQuery(this).prev().find('i.dashicons').hasClass('dashicons-no')){
            jQuery(this).prev().find('i.dashicons').removeClass('dashicons-no');
            jQuery(this).prev().find('i.dashicons').addClass('dashicons-plus');
        }
    });
});

jQuery(function($){

    $("span.b3_message-close").click(function(e){

        $(".b3_message").fadeOut(750);

    });

});

// https://www.w3schools.com/howto/howto_js_tabs.asp
function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tabbutton;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("b3_tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tabbutton" and remove the class "active"
    tabbutton = document.getElementsByClassName("b3_tab-button");
    for (i = 0; i < tabbutton.length; i++) {
        tabbutton[i].className = tabbutton[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

