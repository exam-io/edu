<?php

namespace Modules\Calendar\Http\Requests;

class CalendarUpdateRequest extends CalendarStoreRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('calendar.update');
    }
}
