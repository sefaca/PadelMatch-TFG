window.onload = function() {
    var profileData = document.getElementById("Profile-data");
    profileData.style.display = "block";

    var friendsData = document.getElementById("Profile-data-2");
    friendsData.style.display = "none";
}

function data(event, id) {
    var i, profileContents;
    profileContents = document.getElementsByClassName("profileContent");
    for (i = 0; i < profileContents.length; i++) {
        profileContents[i].style.display = "none";
    }
    document.getElementById(id).style.display = "block";
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

function crearGrupo() {
    // Obtener los amigos seleccionados
    const checkboxes = document.querySelectorAll('input[name="selected_friends[]"]:checked');
    const selectedFriends = Array.from(checkboxes).map(checkbox => checkbox.value);

    // Establecer los IDs de los amigos seleccionados como valor de un input oculto en el formulario
    document.getElementById('selectedFriendsInput').value = selectedFriends.join(',');

    // Enviar el formulario al servidor
    document.getElementById('crearGrupoForm').submit();
}

// Función para mostrar el mensaje emergente
function mostrarMensaje() {
    // Crear el elemento de mensaje emergente
    var mensaje = document.createElement('div');
    mensaje.innerHTML = `
        <div class="modal">
            <div class="modal-content">
                <p>¡Grupo creado con éxito!</p>
                <button onclick="cerrarMensaje()">Aceptar</button>
            </div>
        </div>
    `;
    // Agregar el mensaje emergente al cuerpo del documento
    document.body.appendChild(mensaje);
}

// Función para cerrar el mensaje emergente
function cerrarMensaje() {
    // Eliminar el mensaje emergente del DOM
    var modal = document.querySelector('.modal');
    modal.parentNode.removeChild(modal);
}