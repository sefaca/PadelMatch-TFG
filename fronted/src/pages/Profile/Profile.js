window.onload = function() {
    var profileData = document.getElementById("Profile-data");
    profileData.style.display = "block";

    var friendsData = document.getElementById("Profile-data-2");
    friendsData.style.display = "none";
}

function data(evt, selectData) {
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("profileContent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    document.getElementById(selectData).style.display = "block";
    evt.currentTarget.className += " active";

    // Mostrar u ocultar la sección profile-data y profile-data-2 específicamente
    if (selectData === "Profile-data") {
        var profileData = document.getElementById("Profile-data");
        profileData.style.display = "block";

        var friendsData = document.getElementById("Profile-data-2");
        friendsData.style.display = "none";
    } else if (selectData === "Profile-data-2") {
        var profileData = document.getElementById("Profile-data");
        profileData.style.display = "none";

        var friendsData = document.getElementById("Profile-data-2");
        friendsData.style.display = "block";
    }
}
