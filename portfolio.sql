-- Base de datos: portfolio
CREATE DATABASE portfolio;
USE portfolio;

-- Tabla: usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(128) NOT NULL,
    apellidos VARCHAR(128) NOT NULL,
    foto VARCHAR(128) DEFAULT 'img/default.jpg',
    categoria_profesional VARCHAR(64),
    email VARCHAR(64) UNIQUE NOT NULL,
    resumen_perfil TINYTEXT,
    password VARCHAR(64) NOT NULL,
    visible TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    token VARCHAR(128),
    fecha_creacion_token TIMESTAMP,
    cuenta_activa TINYINT DEFAULT 0
);

-- Tabla: trabajos
CREATE TABLE trabajos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(128) NOT NULL,
    descripcion VARCHAR(256),
    fecha_inicio DATE NOT NULL,
    fecha_final DATE,
    logros VARCHAR(512), -- Lista separada por comas
    visible TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarios_id INT,
    FOREIGN KEY (usuarios_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla: proyectos
CREATE TABLE proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(128) NOT NULL,
    descripcion VARCHAR(256),
    logo VARCHAR(128),
    tecnologias VARCHAR(256), -- Lista separada por comas
    visible TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarios_id INT,
    FOREIGN KEY (usuarios_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla: redes_sociales
CREATE TABLE redes_sociales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    redes_sociales VARCHAR(64) NOT NULL,
    url VARCHAR(256) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuarios_id INT,
    FOREIGN KEY (usuarios_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla: categorias_skills
CREATE TABLE categorias_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria VARCHAR(32) UNIQUE NOT NULL
);

-- Tabla: skills
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    habilidades VARCHAR(256) NOT NULL, -- Lista separada por comas
    visible TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    categorias_skills_categoria VARCHAR(32),
    usuarios_id INT,
    FOREIGN KEY (categorias_skills_categoria) REFERENCES categorias_skills(categoria) ON DELETE SET NULL,
    FOREIGN KEY (usuarios_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
