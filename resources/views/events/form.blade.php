<form class="form-horizontal" action="{{ $form['url'] }}" method="{{ $form['method'] }}">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="{{ isset($form['_method'])? $form['_method'] : $form['method'] }}">

    <div class="form-group{!! ($errors->has('title')) ? ' has-error' : '' !!}">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input name="title" value="{!! Request::old('title', $form['defaults']['title']) !!}" type="text"
                   class="form-control" placeholder="Event Title">
            {!! ($errors->has('title') ? $errors->first('title') : '') !!}
        </div>
    </div>

    <div class="form-group{!! ($errors->has('location')) ? ' has-error' : '' !!}">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input name="location" value="{!! Request::old('location', $form['defaults']['location']) !!}" type="text"
                   class="form-control" placeholder="Event Location">
            {!! ($errors->has('location') ? $errors->first('location') : '') !!}
        </div>
    </div>

    <div class="date input-group{!! ($errors->has('date')) ? ' has-error' : '' !!}" data-provide="datepicker">
        <input name="date" value="{!! Request::old('date', $form['defaults']['date']) !!}" type='text'
               class="form-control" placeholder="Event Date">
        <div class="input-group-addon">
            <span class="glyphicon glyphicon-th"></span>
        </div>
    </div>

    <div class="form-group{!! ($errors->has('body')) ? ' has-error' : '' !!}">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <textarea name="body" type="text" class="form-control" data-provide="markdown" placeholder="Event Body"
                      rows="10">{!! Request::old('body', $form['defaults']['body']) !!}</textarea>
            {!! ($errors->has('body') ? $errors->first('body') : '') !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-offset-4 col-sm-offset-4 col-sm-4 col-xs-4">
            <button class="btn btn-primary" type="submit"><i class="fa fa-rocket"></i> {!! $form['button'] !!}</button>
        </div>
    </div>

</form>
