<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Panel;
use App\Models\Message;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\HasMany;
use App\Nova\Order;



class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

    public function listOfConversationsDetail() {
        $returnArray = [];
        $allMessages = Message::where('receiver_id', $this->id)->get();
        foreach ($allMessages as $message) {
            $count = Message::where('sender_id', $message->sender_id)->count();
            $returnArray[] = Text::make($message->sender_id, $count);
        }
        return $returnArray;
    }

    public $listOfConversationsDetail;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make('№', 'id')->sortable(),

            //Gravatar::make()->maxWidth(50),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Text::make('Телефон', 'phone')
                ->sortable()
                ->rules('required', 'max:255'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),
                
            Boolean::make('Администратор', 'superadmin')
                ->sortable()
                ->rules('required', 'boolean'),

            /*
                // Assuming you have a method on the User model that provides the array structure for the permissions
            BooleanGroup::make('Permissions', 'permissions')
                ->options([
                    'Designs' => 'Design Model',
                    // Add other resources as necessary
                ])
                ->hideFromIndex(),
            */

            Panel::make('Заказы - проекты', [
                HasMany::make('Заказы - сметы', 'allSmetaOrders', Order::class),
                HasMany::make('Заказы - фундаменты', 'allFoundationOrders', Order::class),
            ]),

            Text::make('Регион', 'regions')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Переписок', function() {
                return $this->listOfConversationsIndex();
            }),
            
            //не работает ни хрена - надо группировать а не повторять каждую сто раз
            //new Panel('Переписки', $this->conversationFields())
        ];
    }

    protected function conversationFields()
    {
        $fields = [];
        $allMessages = Message::where('receiver_id', $this->id)->get();
        foreach ($allMessages as $message) {
            $senderString = ($message->sender->name . ' (' . $message->sender->email . ')') ?? 'Unknown';
            $count = Message::where('sender_id', $message->sender_id)->count();
            $fields[] = Text::make($senderString, function() use ($count) {
                return $count;
            })->readonly()->onlyOnDetail();
        }
        return $fields;
    }

    public function listOfConversationsIndex() {
        $allMessages = Message::where('receiver_id', $this->id)->get();
        $uniqueConversations = $allMessages->unique('sender_id');
        return $uniqueConversations->count();
    }

    

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
