<?php

namespace Domain\TalksAtConfs\Nova\Actions;

// use App\Actions\ImportEvent;
use Domain\TalksAtConfs\Actions\ImportEvent;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;

class AddEventToConferenceFromYaml extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            try {
                (new ImportEvent(
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
