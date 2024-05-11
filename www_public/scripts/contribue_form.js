// Definir una función para manejar el envío del formulario

function handleFormSubmit(event) {
    // Detener el envío del formulario
    event.preventDefault();
    console.log(event);

    // Obtener la URL del action
    var url = this.action;
    var method = this.method;

    var formData = new FormData(this);

    // Validar los componentes (puedes agregar tus validaciones aquí)
    var isValid = true;
    /**
    if (!formData.nombre || !formData.email) {
        isValid = false;
        alert("Por favor, complete todos los campos.");
    }**/

    // Si los datos son válidos, enviarlos a la API
    if (isValid) {
        const jsonData = method.toUpperCase() === "POST" ? JSON.stringify(Object.fromEntries(formData)) : null;
        const queryString = new URLSearchParams(formData).toString();

        // Construir la URL completa con los parámetros de la consulta
        const fullUrl = method.toUpperCase() === "GET" ? `${url}/?${queryString}` : url;

        // Mostrar la URL en la consola
        console.log("URL:", fullUrl);
        // Aquí puedes hacer una solicitud a tu API con la URL y los datos del formulario
        console.log("URL:", url);
        console.log("Datos:", jsonData);
        console.log("Method: "+method);
        // Ejemplo de solicitud a la API utilizando fetch
        fetch(fullUrl, {
          method: method,
          headers: {
            "Content-Type": "application/json"
          },
          body: jsonData
        })
        .then(response => response.json())
        .then(data => {
          console.log("Respuesta de la API:", data);
            procesarData(data,this);
        })
        .catch(error => {
          console.error("Error al enviar datos a la API:", error);
        });
    }
}

// Obtener todos los formularios en la página
var forms = document.getElementsByTagName("form");

// Iterar sobre cada formulario y agregar el evento de envío
for (var i = 0; i < forms.length; i++) {
    forms[i].addEventListener("submit", handleFormSubmit);
}

function procesarData(data,formOrigin) {
    if (data && data.action) {
        switch (data.action) {
            case "MSG":
                if (data.execute) {
                    eval(data.execute);
                }
                break;
            case "RD":
                if (data.execute) {
                    window.location.href = data.execute;
                }
                break;
            default:
                break;
        }
    }
}

function guardarTkUsr(token, datosUsuario) {
    localStorage.setItem('jwt', token);
    localStorage.setItem('usr', JSON.stringify(datosUsuario));
    var tiempoExpiracion = new Date().getTime() + (8 * 3600 * 1000);
    localStorage.setItem('exp', tiempoExpiracion);
    document.cookie = "jwt=" + token + ";expires=" + new Date(tiempoExpiracion).toUTCString() + ";path=/";
}

function tokenExp() {
    var tiempoExpiracion = localStorage.getItem('exp');
    return tiempoExpiracion < new Date().getTime();
}

function obtenerDataUsr(nombreCampo) {
    var datosUsuario = localStorage.getItem('usr');
    if (datosUsuario) {
        var usuario = JSON.parse(datosUsuario);
        if (usuario.hasOwnProperty(nombreCampo)) {
            return usuario[nombreCampo];
        } else {
            console.error("El campo especificado no existe en los datos del usuario.");
            return null;
        }
    } else {
        console.error("No se han encontrado datos de usuario almacenados.");
        return null;
    }
}

