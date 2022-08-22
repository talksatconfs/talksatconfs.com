<?php

namespace Domain\TalksAtConfs\Nova\Actions;

use Domain\TalksAtConfs\Actions\ImportTalks;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;

class AddTalksFromYaml extends Action implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            try {
                (new ImportTalks(
                    $model->id,
                    $fields->get('year'),
                    $fields->get('file_name')
                )
                )->handle();
            } catch (Exception $e) {
                $this->markAsFailed($model, $e);
            }
        }

        return Action::message('Added to the queue!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('Year'),
            Text::make('File Name'),
        ];
    }
}
