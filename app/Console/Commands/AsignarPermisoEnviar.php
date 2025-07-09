<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AsignarPermisoEnviar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:assign-enviar {user_id? : ID del usuario (opcional, si no se proporciona se asigna a todos los usuarios con rol ADMIN)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asigna el permiso ENVIAR a un usuario específico o a todos los usuarios ADMIN';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Verificar si existe el permiso ENVIAR
            $permiso = Permission::where('name', 'ENVIAR')->first();
            
            if (!$permiso) {
                $this->info('Creando permiso ENVIAR...');
                $permiso = Permission::create(['name' => 'ENVIAR']);
                $this->info(' Permiso ENVIAR creado exitosamente');
            } else {
                $this->info(' Permiso ENVIAR ya existe');
            }

            $userId = $this->argument('user_id');

            if ($userId) {
                // Asignar a usuario específico
                $usuario = User::find($userId);
                
                if (!$usuario) {
                    $this->error(" Usuario con ID {$userId} no encontrado");
                    return 1;
                }

                if ($usuario->hasPermissionTo('ENVIAR')) {
                    $this->info("ℹ  El usuario {$usuario->email} ya tiene el permiso ENVIAR");
                } else {
                    $usuario->givePermissionTo('ENVIAR');
                    $this->info(" Permiso ENVIAR asignado al usuario: {$usuario->email}");
                }
            } else {
                // Asignar a todos los usuarios con rol ADMIN
                $usuarios = User::role(['ADMIN', 'admin', 'Admin'])->get();
                
                if ($usuarios->isEmpty()) {
                    $this->info('ℹ  No se encontraron usuarios con rol ADMIN');
                    
                    // Mostrar todos los usuarios disponibles
                    $todosUsuarios = User::all();
                    if ($todosUsuarios->isNotEmpty()) {
                        $this->info(' Usuarios disponibles:');
                        foreach ($todosUsuarios as $user) {
                            $roles = $user->getRoleNames()->implode(', ');
                            $permisos = $user->hasPermissionTo('ENVIAR') ? ' Tiene ENVIAR' : ' Sin ENVIAR';
                            $this->line("   ID: {$user->id} | Email: {$user->email} | Roles: [{$roles}] | {$permisos}");
                        }
                        
                        $this->info(' Ejecuta: php artisan permission:assign-enviar {user_id} para asignar a un usuario específico');
                    }
                } else {
                    $this->info(' Asignando permiso ENVIAR a usuarios ADMIN...');
                    
                    foreach ($usuarios as $usuario) {
                        if ($usuario->hasPermissionTo('ENVIAR')) {
                            $this->line("   ℹ  {$usuario->email} ya tiene el permiso");
                        } else {
                            $usuario->givePermissionTo('ENVIAR');
                            $this->line("    Asignado a: {$usuario->email}");
                        }
                    }
                }
            }

            $this->info(' Proceso completado');
            return 0;

        } catch (\Exception $e) {
            $this->error(" Error: " . $e->getMessage());
            return 1;
        }
    }
}
