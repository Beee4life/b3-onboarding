// https://www.w3schools.com/howto/howto_js_tabs.asp
function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tabbutton;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("b3__tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tabbutton" and remove the class "active"
    tabbutton = document.getElementsByClassName("b3__tab-button");
    for (i = 0; i < tabbutton.length; i++) {
        tabbutton[i].className = tabbutton[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}
