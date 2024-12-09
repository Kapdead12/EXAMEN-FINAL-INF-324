var camara, escena, renderizador;
var controlesCamara;
var reloj = new THREE.Clock();
var luzAmbiente, luzDireccional;
var triangulo;

function iniciar() {
    var anchoCanvas = window.innerWidth * 0.5;
    var altoCanvas = window.innerHeight * 0.85;

    // CÁMARA
    camara = new THREE.PerspectiveCamera(45, anchoCanvas / altoCanvas, 1, 80000);
    camara.position.set(1, 1, 5);
    camara.lookAt(0, 0, 0);

    // LUCES
    luzDireccional = new THREE.DirectionalLight(0xFFFFFF, 0.7);
    luzDireccional.position.set(1, 1, 1);

    luzAmbiente = new THREE.AmbientLight(0x111111);

    // RENDERIZADOR
    renderizador = new THREE.WebGLRenderer({ antialias: true });
    renderizador.setSize(anchoCanvas, altoCanvas);
    renderizador.setClearColor(0x000000, 1.0);

    // Agregar al DOM
    var contenedor = document.getElementById('container');
    contenedor.appendChild(renderizador.domElement);

    // CONTROLES DE CÁMARA
    controlesCamara = new THREE.OrbitControls(camara, renderizador.domElement);

    // GEOMETRÍA
    var geometria = new THREE.Geometry();
    geometria.vertices.push(new THREE.Vector3(-0.5, -0.5, 0.0)); // V. Izq.
    geometria.vertices.push(new THREE.Vector3(0.5, -0.5, 0.0));  // V Der.
    geometria.vertices.push(new THREE.Vector3(0.0, 0.3, 0.0));   // V Sup.

    geometria.faces.push(new THREE.Face3(0, 1, 2)); // Caras de triangulo, indices
    geometria.computeFaceNormals();

    var material = new THREE.MeshBasicMaterial({ color: 0xFF0000, side: THREE.DoubleSide });
    triangulo = new THREE.Mesh(geometria, material);

    // ESCENA
    escena = new THREE.Scene();
    escena.add(luzDireccional);
    escena.add(luzAmbiente);
    escena.add(triangulo);
}

function animar() {
    window.requestAnimationFrame(animar);
    actualizar();
    renderizar();
}

var angulo = 0; // Ángulo inicial en radianes
var radio = 1; // Radio del círculo

function actualizar() {
    angulo += 0.05; // Ajusta la velocidad del movimiento (más alto es más rápido)

    // Calcula las nuevas coordenadas usando funciones trigonométricas
    triangulo.position.x = radio * Math.cos(angulo); // Coordenada x
    triangulo.position.y = radio * Math.sin(angulo); // Coordenada y
}


function renderizar() {
    var delta = reloj.getDelta();
    controlesCamara.update(delta);
    renderizador.render(escena, camara);
}

try {
    iniciar();
    animar();
} catch (e) {
    var reporteError = "Encontró un error <br/><br/>";
    document.getElementById('container').innerHTML = reporteError + e;
}
