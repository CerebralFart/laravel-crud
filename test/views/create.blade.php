{
	"type": "create",
@if($errors)
	"errors":{!! json_encode($errors) !!},
@endif
	"data": {!! json_encode($pokemon) !!}
}
