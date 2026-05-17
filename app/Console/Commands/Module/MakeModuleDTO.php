<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Aliases;
use Illuminate\Console\Command;

#[Signature('make-module:dto
    {name : Nombre del DTO (e.g. ArticleData)}
    {--module= : Nombre del módulo (e.g. RouteRegistrationTest)}
    {--m|model= : Nombre del modelo (e.g. Article)}
    {--form-data : Crear un DTO de formulario}
    {--with-media : Crear un DTO con media}
    {--s|singleton : Crear un DTO de singleton}
    {--f|force : Forzar la creación de un archivo existente}
    {--d|dry-run : Simular la creación del archivo}')]
#[Description('Generate a readonly DTO (Data Transfer Object) inside a module')]
#[Aliases(['mm:dto', 'module:dto'])]
class MakeModuleDTO extends Command
{
    use GeneratesModuleFiles;

    public function handle(): void
    {
        $fileName = $this->argument('name');
        $model = $this->option('model');
        $module = $this->validateField('module');

        if (!$module)
            return;

        $this->setup($module, $model);

        $options = $this->getFileOptions();

        $this->writeFile(
            stub: $options['stub'],
            path: 'Application/DTOs',
            label: $fileName,
            labelPrefix: $options['labelPrefix'],
        );

        $finalClassName = $this->getFileLabel($fileName, $options['labelPrefix']);

        $this->summary("DTO <fg=cyan>{$finalClassName}</> generated.");
    }



    /**
     * Obtiene las opciones de configuración del archivo según los flags provistos.
     *
     * @return array{labelPrefix: string, stub: string}
     */
    protected function getFileOptions(): array
    {
        if ($this->option('model') && $this->option('with-media')) {
            return [
                'stub' => 'module/dto/dto.model.media',
                'labelPrefix' => 'Data',
            ];
        }

        if ($this->option('model')) {
            return [
                'stub' => 'module/dto/dto.model',
                'labelPrefix' => 'Data',
            ];
        }

        if ($this->option('form-data')) {
            return [
                'stub' => 'module/dto/dto.form-data',
                'labelPrefix' => 'FormData',
            ];
        }

        if ($this->option('singleton')) {
            return [
                'stub' => 'module/dto/dto.singleton',
                'labelPrefix' => 'Data',
            ];
        }

        return [
            'stub' => 'module/dto/dto',
            'labelPrefix' => 'Data',
        ];
    }
}
