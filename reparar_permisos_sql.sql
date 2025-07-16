-- ============================================
-- SCRIPT DE REPARACIÓN DE PERMISOS YUNTAS
-- Ejecutar en phpMyAdmin / Administrador BD
-- ============================================

-- 1. CREAR PERMISOS NECESARIOS (si no existen)
INSERT IGNORE INTO permissions (name, guard_name, created_at, updated_at) VALUES
('gestionar-roles', 'web', NOW(), NOW()),
('gestionar-permisos', 'web', NOW(), NOW()),
('asignar-permisos-roles', 'web', NOW(), NOW()),
('asignar-roles-usuarios', 'web', NOW(), NOW()),
('ver-usuarios', 'web', NOW(), NOW()),
('crear-usuarios', 'web', NOW(), NOW()),
('editar-usuarios', 'web', NOW(), NOW()),
('eliminar-usuarios', 'web', NOW(), NOW()),
('ver-clientes', 'web', NOW(), NOW()),
('crear-clientes', 'web', NOW(), NOW()),
('editar-clientes', 'web', NOW(), NOW()),
('eliminar-clientes', 'web', NOW(), NOW()),
('ver-reclamos', 'web', NOW(), NOW()),
('crear-reclamos', 'web', NOW(), NOW()),
('editar-reclamos', 'web', NOW(), NOW()),
('eliminar-reclamos', 'web', NOW(), NOW()),
('crear-blogs', 'web', NOW(), NOW()),
('editar-blogs', 'web', NOW(), NOW()),
('eliminar-blogs', 'web', NOW(), NOW()),
('ver-productos', 'web', NOW(), NOW()),
('crear-productos', 'web', NOW(), NOW()),
('editar-productos', 'web', NOW(), NOW()),
('eliminar-productos', 'web', NOW(), NOW()),
('crear-tarjetas', 'web', NOW(), NOW()),
('editar-tarjetas', 'web', NOW(), NOW()),
('eliminar-tarjetas', 'web', NOW(), NOW());

-- 2. CREAR ROLES (si no existen)
INSERT IGNORE INTO roles (name, guard_name, created_at, updated_at) VALUES
('admin', 'web', NOW(), NOW()),
('user', 'web', NOW(), NOW());

-- 3. ASIGNAR TODOS LOS PERMISOS AL ROL ADMIN
INSERT IGNORE INTO role_has_permissions (permission_id, role_id)
SELECT p.id, r.id 
FROM permissions p 
CROSS JOIN roles r 
WHERE r.name = 'admin';

-- 4. ASIGNAR ROL ADMIN A TODOS LOS USUARIOS EXISTENTES
INSERT IGNORE INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'App\\Models\\User', u.id 
FROM roles r 
CROSS JOIN users u 
WHERE r.name = 'admin';

-- 5. VERIFICACIÓN - Mostrar usuarios con roles
SELECT 
    u.id,
    u.name,
    u.email,
    r.name as role_name
FROM users u
LEFT JOIN model_has_roles mhr ON u.id = mhr.model_id AND mhr.model_type = 'App\\Models\\User'
LEFT JOIN roles r ON mhr.role_id = r.id
ORDER BY u.id;

-- 6. VERIFICACIÓN - Mostrar permisos del rol admin
SELECT 
    r.name as role_name,
    p.name as permission_name
FROM roles r
JOIN role_has_permissions rhp ON r.id = rhp.role_id
JOIN permissions p ON rhp.permission_id = p.id
WHERE r.name = 'admin'
ORDER BY p.name;
