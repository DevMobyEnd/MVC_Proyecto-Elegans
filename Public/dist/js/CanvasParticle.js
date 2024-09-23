var CanvasParticle = (function () {
    // Función para obtener elementos por su etiqueta
    function getElementByTag(name) {
        return document.getElementsByTagName(name);
    }

    // Función para obtener un elemento por su id
    function getElementById(id) {
        return document.getElementById(id);
    }

    // Función principal para inicializar el canvas
    function canvasInit(canvasConfig) {
        // Si no se proporciona ninguna configuración, se establece como un objeto vacío
        canvasConfig = canvasConfig || {};
        
        // Obtiene referencias a elementos HTML importantes
        var html = getElementByTag("html")[0];
        var body = getElementByTag("body")[0];
        var canvasDiv = getElementById("canvas-particle");
        
        // Crea un nuevo elemento canvas
        var canvasObj = document.createElement("canvas");

        // Configura el objeto canvas con sus propiedades iniciales
        var canvas = {
            element: canvasObj,
            points: [], // Almacena los puntos generados
            config: {
                vx: canvasConfig.vx || 4, // Velocidad horizontal
                vy: canvasConfig.vy || 4, // Velocidad vertical
                height: canvasConfig.height || 2, // Altura de los puntos
                width: canvasConfig.width || 2, // Ancho de los puntos
                count: canvasConfig.count || 100, // Cantidad de puntos
                color: canvasConfig.color || "0, 150, 150", // Color de los puntos
                stroke: canvasConfig.stroke || "0, 150, 150", // Color de las conexiones
                dist: canvasConfig.dist || 150, // Distancia de interacción entre puntos
                e_dist: canvasConfig.e_dist || 150, // Distancia de atracción del ratón
                max_conn: 5 // Número máximo de conexiones por punto
            }
        };

        // Si el contexto 2D del canvas está disponible, se establece en el objeto canvas
        if (canvas.element.getContext("2d")) {
            canvas.context = canvas.element.getContext("2d");
        } else {
            return null; // Si no hay soporte para canvas, se devuelve null
        }

        // Estilos del cuerpo HTML y del canvas
        body.style.padding = "0";
        body.style.margin = "0";
        body.appendChild(canvas.element);
        canvas.element.style.position = "absolute";
        canvas.element.style.top = "0";
        canvas.element.style.left = "0";
        canvas.element.style.zIndex = "-1";

        // Se ajusta el tamaño del canvas y se establece un evento de redimensionamiento
        canvasSize(canvas.element);
        window.onresize = function () {
            canvasSize(canvas.element);
        };
        
        // Se añaden eventos para detectar el movimiento del ratón
        body.onmousemove = function (e) {
            var event = e || window.event;
            canvas.mouse = {
                x: event.clientX,
                y: event.clientY
            };
        };
        document.onmouseleave = function () {
            canvas.mouse = undefined;
        };
        
        // Se establece un intervalo para dibujar los puntos
        setInterval(function () {
            drawPoint(canvas);
        }, 40);
    }

    // Función para ajustar el tamaño del canvas
    function canvasSize(canvas) {
        canvas.width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        canvas.height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    }

    // Función para dibujar los puntos
    function drawPoint(canvas) {
        var context = canvas.context,
            point,
            dist;
        
        // Se borra el contenido previo del canvas
        context.clearRect(0, 0, canvas.element.width, canvas.element.height);
        context.beginPath();
        
        // Se establece el color de los puntos
        context.fillStyle = "rgb(" + canvas.config.color + ")";
        
        // Se recorren todos los puntos
        for (var i = 0, len = canvas.config.count; i < len; i++) {
            // Se generan nuevos puntos aleatorios si aún no se han generado
            if (canvas.points.length != canvas.config.count) {
                point = {
                    x: Math.floor(Math.random() * canvas.element.width),
                    y: Math.floor(Math.random() * canvas.element.height),
                    vx: canvas.config.vx / 2 - Math.random() * canvas.config.vx,
                    vy: canvas.config.vy / 2 - Math.random() * canvas.config.vy
                };
            } else {
                point = borderPoint(canvas.points[i], canvas); // Se calculan los puntos en el borde
            }
            
            // Se dibuja el punto en el canvas
            context.fillRect(point.x - canvas.config.width / 2, point.y - canvas.config.height / 2, canvas.config.width, canvas.config.height);
            canvas.points[i] = point; // Se almacena el punto generado
        }
        
        // Se dibujan las líneas que conectan los puntos
        drawLine(context, canvas, canvas.mouse);
        context.closePath();
    }

    // Función para calcular los puntos en el borde del canvas
    function borderPoint(point, canvas) {
        var p = point;
        if (point.x <= 0 || point.x >= canvas.element.width) {
            p.vx = -p.vx;
            p.x += p.vx;
        } else if (point.y <= 0 || point.y >= canvas.element.height) {
            p.vy = -p.vy;
            p.y += p.vy;
        } else {
            p = {
                x: p.x + p.vx,
                y: p.y + p.vy,
                vx: p.vx,
                vy: p.vy
            };
        }
        return p;
    }

    // Función para dibujar las líneas que conectan los puntos
    function drawLine(context, canvas, mouse) {
        context = context || canvas.context;
        var dist;
    
        // Recorre todos los puntos
        for (var i = 0, len = canvas.config.count; i < len; i++) {
            canvas.points[i].max_conn = 0;
            for (var j = 0; j < len; j++) {
                if (i !== j) {
                    // Calcula la distancia entre los puntos
                    dist = Math.sqrt(Math.pow(canvas.points[i].x - canvas.points[j].x, 2) + Math.pow(canvas.points[i].y - canvas.points[j].y, 2));
    
                    // Si la distancia es menor que la distancia de interacción y aún hay conexiones disponibles para el punto
                    if (dist <= canvas.config.dist && canvas.points[i].max_conn < canvas.config.max_conn) {
                        canvas.points[i].max_conn++;
                        context.lineWidth = 0.6 - dist / canvas.config.dist;
                        context.strokeStyle = "rgba(" + canvas.config.stroke + "," + (1 - dist / canvas.config.dist) + ")";
                        context.beginPath();
                        context.moveTo(canvas.points[i].x, canvas.points[i].y);
                        context.lineTo(canvas.points[j].x, canvas.points[j].y);
                        context.stroke();
                    }
                     // Si los puntos están muy cerca, aplica una fuerza de repulsión
                    var minDist = 20; // Distancia mínima ajustable
                    if (dist < minDist) {
                        var repulsionFactor = 70; // Factor de repulsión ajustable
                        var dx = canvas.points[i].x - canvas.points[j].x;
                        var dy = canvas.points[i].y - canvas.points[j].y;
                        var repulsionX = dx / dist * repulsionFactor;
                        var repulsionY = dy / dist * repulsionFactor;
                        canvas.points[i].x += repulsionX;
                        canvas.points[i].y += repulsionY;
                    }
                }
            }
    
            // Si hay una posición de ratón definida
            if (mouse) {
                // Calcula la distancia entre el punto y el ratón
                dist = Math.sqrt(Math.pow(canvas.points[i].x - mouse.x, 2) + Math.pow(canvas.points[i].y - mouse.y, 2));
                // Ajustar la distancia de interacción entre los puntos y el ratón
                var interactionDistance = 200; // Distancia de interacción ajustable
                // Si la distancia es menor o igual que la distancia de atracción del ratón
                if (dist <= canvas.config.e_dist) {
                    // Atracción del ratón
                    var attractionFactor = 10; // Factor de atracción ajustable
                    var vx = (mouse.x - canvas.points[i].x) / attractionFactor;
                    var vy = (mouse.y - canvas.points[i].y) /  attractionFactor;
                    canvas.points[i].x += vx;
                    canvas.points[i].y += vy;
                }
            }
        }
    }
    

    return canvasInit; // Devolvemos la función canvasInit
})();
