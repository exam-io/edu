<?php

namespace Modules\LiveClass\Http\Requests;

class LiveClassUpdateRequest extends LiveClassStoreRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('live_class.update');
    }
}
