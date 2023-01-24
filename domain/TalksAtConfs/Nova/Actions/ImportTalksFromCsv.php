<?php

namespace Domain\TalksAtConfs\Nova\Actions;

use Domain\TalksAtConfs\Actions\ImportTalksFromCsv as ActionsImportTalksFromCsv;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;

class ImportTalksFromCsv extends Action
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
        (new ActionsImportTalksFromCsv())->handle($fields['csv']->getPathname());
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            File::make('CSV'),
        ];
    }
}
