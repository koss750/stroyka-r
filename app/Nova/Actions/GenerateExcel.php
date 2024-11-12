<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Facades\Session;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use App\Models\Design;
use App\Models\InvoiceType;
use App\Models\Project;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\RuTranslationController as Translator;
use App\Http\Controllers\InvoiceController as IC;
use App\Http\Controllers\InvoiceModuleController as IM;
use App\Http\Controllers\DesignController as DC;

class GenerateExcel extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return Translator::translate('foundation_action');
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $ic = new IC;
        $im = new IM;
        $dc = new DC;
        $design = new Design;
        $array = $fields->toArray();
        $toProcess = [];
        foreach ($array as $key => $value) {
            $toProcess[] = $ic->getByLabel($value);
        }
        $result = $ic->processNovaFormFields($toProcess);
        // Assuming the action is run on a single resource, get the first model
        $model = $models->first();
        // Now you can access the resource ID
        $designID = $model->id;
        $design = $dc->getById($designID);
        $sections = $im->setSections($result["d"], $result["r"], $result["f"]);
        $data = $im->prepareForDemo($sections, $design);
        $data = [$design->title, $data];
        $encryptedData = (base64_encode(serialize($data)));
        Session::put('invoiceData', $encryptedData);
        

        $response = Http::withHeaders([
        ])->get("https://cloud-api.yandex.net/v1/disk/resources/upload", [
            'path' => '/path/to/your/folder/' . $filePath,
            'overwrite' => 'true'
        ]);

        if ($response->failed()) {
            return Action::danger('Failed to request upload URL.');
        }

        $uploadUrl = $response->json()['href'];

        // Upload the file using the obtained URL
        $uploadResponse = Http::put($uploadUrl, $fileContent);

        if ($uploadResponse->failed()) {
            return Action::danger('Failed to upload file to Yandex Disk.');
        }

        /* Optionally, email the file as well
        Mail::send('emails.invoice', [], function ($message) use ($fileContent) {
            $message->to('user@example.com')
                    ->subject('Generated Invoice')
                    ->attachData($fileContent, 'invoice.xlsx');
        });*/


        // You need a URL to redirect to that will trigger the POST request.
        // This will be a route that returns a view with JavaScript to auto-submit the form.
        return Action::redirect('/trigger-invoice-view');

        //return Action::message('Проект создан успешно. Ошибка в подсчетах');
        
    }

    public function fields(NovaRequest $request)
    {
        
        $ic = new IC;
        $field =  [];

        $currentParent = null;
        $currentChild = null;
        $allItems = $ic->fullList();
        foreach($allItems as $section) {
            
            $optionsToAdd = [];
            $label = $section['label'];
            $title = $section['title'];
            $children = $section['params'];
            //var_dump($children);
            // selection level0 from level 1
                foreach ($children as $child) {
                    $key = $child['label'];
                    $value = $child['title'];
                    $optionsToAdd[$key] = $value;
                }
            $fieldToAdd = Select::make(Translator::translate($title),$label)->options($optionsToAdd)->help('Выберите тип')
            ->displayUsingLabels();
            $field[]=$fieldToAdd;
            $optionsToAdd = [];

             // selection level1 from level 2
             foreach($children as $child) {
            
                $optionsToAdd = [];
                $label = $child['label'];
                $currentChild = $child['label'];
                $currentParent = $section['label'];
                $title = $child['title'];
                $gChildren = $child['params'];
                //var_dump($children);
                // selection level1 from level 2
                if(is_array($gChildren) && sizeof($gChildren)>0){    
                    foreach ($gChildren as $gChild) {
                            $key = $gChild['label'];
                            $value = $gChild['title'];
                            $optionsToAdd[$key] = $value;
                        }
                    $fieldToAdd = Select::make(Translator::translate($title),$label)->options($optionsToAdd)->hide()
                    ->rules('sometimes')->dependsOn($section['label'], function ($field, NovaRequest $request, FormData $formData) use ($currentChild, $currentParent) {
                        if ($formData->$currentParent == $currentChild) {
                            $field->show()->rules('required');
                        }})->help('Выберите тип')->displayUsingLabels();
                    $field[]=$fieldToAdd;
                }
                $optionsToAdd = [];

             foreach($gChildren as $gchild) {
            
                $optionsToAdd = [];
                $label = $gchild['label'];
                $currentChild = $gchild['label'];
                $currentParent = $child['label'];
                $title = $gchild['title'];
                $ggChildren = $gchild['params'];
                //var_dump($children);
                // selection level1 from level 2
                if(is_array($ggChildren) && sizeof($ggChildren)>0){    
                    foreach ($ggChildren as $ggChild) {
                            $key = $ggChild['label'];
                            $value = $ggChild['title'];
                            $optionsToAdd[$key] = $value;
                        }
                    $fieldToAdd = Select::make(Translator::translate($title),$label)->options($optionsToAdd)->hide()
                    ->rules('sometimes')->dependsOn($child['label'], function ($field, NovaRequest $request, FormData $formData) use ($currentChild, $currentParent) {
                        if ($formData->$currentParent == $currentChild) {
                            $field->show()->rules('required');
                        }})->help('Выберите тип')->displayUsingLabels();
                    $field[]=$fieldToAdd;
                }
                $optionsToAdd = [];
             }
             }
   
        }
        
        return [
            
            Select::make('Фундамент', 'f')
                ->options([
                    'fnone' => 'Без Фундамента',
                    'fLenta' => 'Ленточный тест',
                    'fLent' => 'Ленточный',
                    'fVinta' => 'Винтовой и ЖБ Сваи',
                    'fMono' => 'Монолитная плита'
                    // You can add more sheet types here in the future
                ])->help('Выберите тип фундамента')
                ->displayUsingLabels(),
            Select::make('Сечения', 'variation')
            ->options([
                '300x600' => '300x600',
                '300x700' => '300x700',
                '300x800' => '300x800',
                '300x900' => '300x900',
                '300x1000' => '300x1000',
                '400x600' => '400x600',
                '400x700' => '400x700',
                '400x800' => '400x800',
                '400x900' => '400x900',
                '400x1000' => '400x1000',
                '500x600' => '500x600',
                '500x700' => '500x700',
                '500x800' => '500x800',
                '500x900' => '500x900',
                '500x1000' => '500x1000',
                '600x700' => '600x700',
                '600x800' => '600x800',
                '600x900' => '600x900',
                '600x1000' => '600x1000',
            ])
            ->displayUsingLabels()
            ->hide()
            ->rules('sometimes')
            ->dependsOn('sheet_type', function ($field, NovaRequest $request, FormData $formData) {
                if ($formData->sheet_type == 'fLenta') {
                    $field->show()->rules('required');
                }})
            ->sortable()
            ->help('Выберите ширину ленты (только ленточный фундамент)'),
        Select::make('Домокомплект', 'home_type')
            ->options([
                'kBrus' => 'Брус',
                'kOCB' => 'ОЦБ',
                'kRubka' => 'Ручная Рубка',
                'fMono' => 'Монолитная плита'
                // You can add more sheet types here in the future
            ])->help('Выберите домокомплект')
            ->displayUsingLabels(),
        Select::make('Материал', 'material_type')
                ->options([
                    'mSosna' => 'Лиственница',
                    'mList' => 'Сосна',
                    'mKedr' => 'Кедр'
                ])->help('Выберите тип (только брус и ОЦБ)')
                ->displayUsingLabels(),
        Select::make('Диаметр', 'material_width')
                ->options([
                    '200' => '200',
                    '220' => '220',
                    '240' => '240',
                    '260' => '260',
                    '280' => '280',
                    '300' => '300'
                    ])->help('Выберите диаметр')
                    ->displayUsingLabels(),
                ];
            }
        }
    
        