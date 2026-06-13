<?php

namespace Joserick\Filament\DevTool\Commands;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Joserick\Filament\DevTool\Commands\Concerns\InteractsWithCanvasPreset;
use Throwable;

use function Filament\Support\discover_app_classes;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;
use function Orchestra\Sidekick\join_paths;

class MakeRelationManagerCommand extends \Filament\Commands\MakeRelationManagerCommand
{
    use InteractsWithCanvasPreset;

    protected function askForResource(string $question, ?string $initialResource = null, ?string $resourcesNamespace = null): string
    {
        $resourcesNamespace ??= $this->resourcesNamespace;

        if (is_string($initialResource)) {
            $initialResource = (string) str($initialResource)
                ->trim('/')
                ->trim('\\')
                ->trim(' ')
                ->replace('/', '\\');

            if (class_exists($initialResource)) {
                return $initialResource;
            }

            $resourceNamespace = (string) str($initialResource)
                ->beforeLast('Resource')
                ->pluralStudly()
                ->replace('/', '\\')
                ->prepend("{$resourcesNamespace}\\");

            $resourceBasename = (string) str($initialResource)
                ->classBasename()
                ->beforeLast('Resource')
                ->singular()
                ->append('Resource');

            $fqn = "{$resourceNamespace}\\{$resourceBasename}";

            // When class_exists fails in the Canvas context (e.g. due to
            // autoloader not picking up newly created files), manually
            // load the file based on the PSR-4 mapping.
            $preset = $this->generatorPreset();
            $relativePath = str($fqn)
                ->after($preset->rootNamespace() . '\\')
                ->replace('\\', '/')
                ->append('.php')
                ->toString();

            $filePath = join_paths($preset->sourcePath(), $relativePath);

            if (file_exists($filePath)) {
                require_once $filePath;
            }

            if (class_exists($fqn)) {
                return $fqn;
            }

            // Also try without the extra namespace segment
            $resourceNamespace2 = (string) str($resourceNamespace)
                ->beforeLast('\\');

            $fqn2 = "{$resourceNamespace2}\\{$resourceBasename}";

            $relativePath2 = str($fqn2)
                ->after($preset->rootNamespace() . '\\')
                ->replace('\\', '/')
                ->append('.php')
                ->toString();

            $filePath2 = join_paths($preset->sourcePath(), $relativePath2);

            if (file_exists($filePath2)) {
                require_once $filePath2;
            }

            if (class_exists($fqn2)) {
                return $fqn2;
            }

            // If we still can't find the class, return the best-guess FQN.
            // The file will be created by the generator regardless.
            return $fqn;
        }

        if (! $this->input->isInteractive()) {
            // In non-interactive mode, return a default based on the argument
            $default = $this->argument('resource');

            if (is_string($default)) {
                $default = (string) str($default)
                    ->trim('/')
                    ->trim('\\')
                    ->trim(' ')
                    ->replace('/', '\\');

                $resourceNamespace = (string) str($default)
                    ->beforeLast('Resource')
                    ->pluralStudly()
                    ->replace('/', '\\')
                    ->prepend("{$resourcesNamespace}\\");

                $resourceBasename = (string) str($default)
                    ->classBasename()
                    ->beforeLast('Resource')
                    ->singular()
                    ->append('Resource');

                return "{$resourceNamespace}\\{$resourceBasename}";
            }

            throw new \RuntimeException('The resource argument is required in non-interactive mode.');
        }

        if ($this->panel) {
            $resourceFqns = array_filter(
                array_values($this->panel->getResources()),
                fn (string $resource): bool => str($resource)->startsWith("{$resourcesNamespace}\\"),
            );

            if ($resourceFqns) {
                return search(
                    label: $question,
                    options: function (?string $search) use ($resourceFqns, $resourcesNamespace): array {
                        $search = str($search)->trim()->replace(['\\', '/'], '');

                        return collect($resourceFqns)
                            ->when(
                                filled($search = (string) str($search)->trim()->replace(['\\', '/'], '')),
                                fn ($resourceFqns) => $resourceFqns->filter(fn (string $fqn): bool => str($fqn)->replace(['\\', '/'], '')->contains($search, ignoreCase: true)),
                            )
                            ->mapWithKeys(function (string $fqn) use ($resourcesNamespace): array {
                                $label = (string) str($fqn)->after("{$resourcesNamespace}\\");

                                if (str($label)->contains('\\')) {
                                    $finalSegment = (string) str($label)->afterLast('\\');
                                    $penultimateSegment = (string) str($label)->beforeLast('\\');

                                    if (str($penultimateSegment)->contains('\\')) {
                                        $penultimateSegment = (string) str($penultimateSegment)->afterLast('\\');
                                    }

                                    if (str($finalSegment)->endsWith('Resource') && ($finalSegment !== 'Resource')) {
                                        $expectedPenultimateSegment = (string) str($finalSegment)
                                            ->beforeLast('Resource')
                                            ->pluralStudly();
                                    }

                                    if ($penultimateSegment === ($expectedPenultimateSegment ?? null)) {
                                        $label = (string) str($label)->beforeLast('\\');
                                    }
                                }

                                return [$fqn => $label];
                            })
                            ->all();
                    },
                );
            }
        }

        return (string) str(text(
            label: "No resources were found within [{$resourcesNamespace}]. {$question}",
            placeholder: $this->generatorPreset()->rootNamespace() . 'Filament\\Resources\\Posts\\PostResource',
            required: true,
            validate: function (string $value): ?string {
                $value = (string) str($value)
                    ->trim('/')
                    ->trim('\\')
                    ->trim(' ')
                    ->replace('/', '\\');

                return match (true) {
                    ! class_exists($value) => 'The resource class doesn\'t exist, please use the fully-qualified class name.',
                    ! is_subclass_of($value, Resource::class) => 'The resource class or one of its parents must extend [' . Resource::class . '].',
                    default => null,
                };
            },
            hint: 'Please provide the fully-qualified class name of the resource.',
        ))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
    }

    /**
     * @return ?class-string
     */
    protected function askForRelatedResource(): ?string
    {
        if (! $this->input->isInteractive()) {
            return null;
        }

        info('Linking to an existing resource will open the resource\'s pages instead of modals when links are clicked. It will also inherit the resource\'s configuration.');

        if (! confirm(
            label: 'Do you want to link this to an existing resource?',
            default: false,
        )) {
            return null;
        }

        $clusterFqn = $this->askForCluster(
            initialQuestion: 'Is the resource in a cluster?',
            question: 'Which cluster is the resource in?',
        );

        if (filled($clusterFqn)) {
            [$resourcesNamespace] = $this->getClusterResourcesLocation($clusterFqn);
        } else {
            [$resourcesNamespace] = $this->getResourcesLocation(
                question: 'Which namespace would you like to search for resources in?',
            );
        }

        return $this->askForResource(
            question: 'Which resource do you want to use?',
            resourcesNamespace: $resourcesNamespace,
        );
    }

    /**
     * @return ?class-string
     */
    protected function askForSchema(string $intialQuestion, string $question, string $questionPlaceholder): ?string
    {
        if (! $this->input->isInteractive()) {
            return null;
        }

        if (! confirm(
            label: $intialQuestion,
            default: false,
        )) {
            return null;
        }

        $schemaFqns = array_filter(
            discover_app_classes(),
            fn (string $schemaFqn): bool => method_exists($schemaFqn, 'configure'),
        );

        return suggest(
            label: $question,
            options: function (?string $search) use ($schemaFqns): array {
                if (blank($search)) {
                    return $schemaFqns;
                }

                $search = str($search)->trim()->replace(['\\', '/'], '');

                return array_filter($schemaFqns, fn (string $schemaFqn): bool => str($schemaFqn)->replace(['\\', '/'], '')->contains($search, ignoreCase: true));
            },
            placeholder: $questionPlaceholder,
            hint: 'Please provide the fully-qualified class name.',
        );
    }

    protected function configureHasViewOperation(): void
    {
        $this->hasViewOperation = $this->option('view') || ($this->input->isInteractive() && confirm(
            label: 'Should there be a read-only "view" modal on the relation manager?',
            default: false,
        ));
    }

    protected function configureIsGeneratedIfNotAlready(?string $question = null): void
    {
        $this->isGenerated ??= $this->option('generate') || ($this->input->isInteractive() && confirm(
            label: $question ?? 'Should the configuration be generated from the current database columns?',
            default: false,
        ));
    }

    protected function configureRecordTitleAttributeIfNotAlready(): void
    {
        $this->recordTitleAttribute ??= $this->option('record-title-attribute') ?? $this->argument('recordTitleAttribute');

        if (filled($this->recordTitleAttribute)) {
            return;
        }

        if (! $this->input->isInteractive()) {
            $this->recordTitleAttribute = 'name';

            return;
        }

        info('The "title attribute" is used to label each record in the UI.');

        $this->recordTitleAttribute = text(
            label: 'What is the title attribute for this model?',
            placeholder: 'name',
            required: true,
        );
    }

    protected function configureRelationshipType(): void
    {
        // Try to auto-detect the relationship type from the model
        try {
            $resourceModelFqn = $this->resourceFqn::getModel();

            if (
                class_exists($resourceModelFqn) &&
                method_exists($resourceModelFqn, $this->relationship) &&
                (($relationshipInstance = app($resourceModelFqn)->{$this->relationship}()) instanceof Relation) &&
                class_exists($relationshipInstance->getRelated()::class) &&
                in_array($relationshipInstance::class, [
                    HasMany::class,
                    BelongsToMany::class,
                    MorphMany::class,
                    MorphToMany::class,
                ])
            ) {
                $this->relationshipType = $relationshipInstance::class;

                return;
            }
        } catch (Throwable) {
            //
        }

        if (! $this->input->isInteractive()) {
            // Default to HasMany in non-interactive mode
            $this->relationshipType = HasMany::class;

            return;
        }

        $this->relationshipType = select(
            label: 'What type of relationship is this?',
            options: [
                HasMany::class => 'HasMany',
                BelongsToMany::class => 'BelongsToMany',
                MorphMany::class => 'MorphMany',
                MorphToMany::class => 'MorphToMany',
                'other' => 'Other',
            ],
        );

        if ($this->relationshipType === 'other') {
            $this->relationshipType = null;
        }
    }

    protected function configureIsSoftDeletable(): void
    {
        if (! $this->input->isInteractive()) {
            $this->isSoftDeletable = false;

            return;
        }

        parent::configureIsSoftDeletable();
    }
}
