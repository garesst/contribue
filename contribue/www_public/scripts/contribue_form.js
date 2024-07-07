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
        console.log("Method: " + method);
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
                procesarData(data, this);
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

function procesarData(data, formOrigin) {
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
            case "N":
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

function checkCookie(name) {
    const cookies = document.cookie;
    const cookieExists = cookies.split(';').some(cookie => {
        cookie = cookie.trim();
        return cookie.startsWith(name + "=");
    });

    return cookieExists;
}

async function consumirREST(url, metodo, datos, token) {
    try {
        const opciones = {
            method: metodo,
            headers: {
                'Content-Type': 'application/json',
            },
            ...(metodo === 'GET' && datos && {body: JSON.stringify(datos)}),
            ...(metodo !== 'GET' && datos && {body: JSON.stringify(datos)})
        };

        const respuesta = await fetch(url, opciones);

        if (!respuesta.ok) {
            throw new Error(`Error al realizar la solicitud: ${respuesta.status}`);
        }

        const datosRespuesta = await respuesta.json();
        return datosRespuesta;
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
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

function cerrarSesiones(useID) {
    const url = 'api/users/0.0.1/logout2/?userId=' + useID;
    const metodo = 'GET';
    //const datos = { parametro1: 'valor1', parametro2: 'valor2' };

    consumirREST(url, metodo, null, '')
        .then(respuesta => {
            procesarData(respuesta, null);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function cerrarTodasSesiones(useID) {
    const url = 'contribue/api/users/0.0.1/logout/?userId=' + useID;
    const metodo = 'GET';
    //const datos = { parametro1: 'valor1', parametro2: 'valor2' };

    consumirREST(url, metodo, null, '')
        .then(respuesta => {
            procesarData(respuesta, null);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function cerrarSesion(){
    cerrarSesiones(obtenerDataUsr('user_id'));
    localStorage.clear();
    sessionStorage.clear();
    clearCookies();
    window.location.replace("/");
}

function clearCookies() {
    const cookies = document.cookie.split(";");

    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i];
        const eqPos = cookie.indexOf("=");
        const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
    }
}

function validarSesionLogin() {
    //
    if (tokenExp) {
        if (checkCookie('jwt')) {
            window.location.replace("/contribue/main");
        } else {
            if(localStorage.getItem('jwt')!=null)
            document.cookie = "jwt=" + localStorage.getItem('jwt') + ";expires=" + localStorage.getItem('exp') + ";path=/";
        }
    }
}

// Función para convertir el string de data-object a un objeto JavaScript
function parseDataObject(dataObject) {
    const obj = {};
    const pairs = dataObject.split(';');
    pairs.forEach(pair => {
        const [key, value] = pair.split('=');
        obj[key.trim()] = value.trim().replace(/'/g, '');
    });
    return obj;
}

// Buscar todos los botones que cumplan los criterios
document.querySelectorAll('button[data-sbm="true"]').forEach(button => {
    const metodo = button.getAttribute('data-method');
    const url = button.getAttribute('data-action');
    const dataObject = button.getAttribute('data-object');

    if (metodo && url && dataObject) {
        const datos = parseDataObject(dataObject);

        button.addEventListener('click', async () => {
            loadSpinner();
            try {

                const resultado = await consumirREST(url, metodo, datos).then(respuesta => {
                    procesarData(respuesta, null);
                })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                ;
                console.log('Resultado:', resultado);
            } catch (error) {
                console.error('Error al consumir la API:', error);
            }
        });
    }
});

function loadSpinner() {
    Swal.fire({
        width: '550px',
        html: `
        <div class="bg-transparent flex flex-col items-center justify-center gap-4">
            <img class="w-20" src="/www_public/img/Ghost.gif" alt="Loading gif">
            
            <strong>Espera, por favor</strong>
        </div>
    `,
        color: '#014766',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
            Swal.getActions().style.display = 'none'; // Hide the action bar as it's not needed
        },
    });
}

function redirectTo(path){
    window.location.replace(path);
}

