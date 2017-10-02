<style>
    .add_comment .row {
        margin-bottom: 10px;
    }

    .form_submit_btn {
        padding: 6px 15px;
        text-align: center;
        font-size: 16px;
        background: #689F38;
        color: white;
        border: none;
        border-radius: 3px;
        outline: none;
    }
</style>
<form class="add_comment" id="add_comment" action="{{route('store_comment')}}" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="movie_id" value="{{$movie_id}}">
    <h4>Добавить отзыв</h4>
    <hr>
    @if (session('message'))
        <div class=" alert
                            @if(session('success') == true) alert-success
                              @elseif(session('success') == false) alert-error
                            @endif">
            <ul>
                <li>{{ session('message') }}</li>
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-9">
            <input id="title" type="text" class="form-control" name="title"
                   placeholder="Заголовок отзыва" maxlength="100">
            @if ($errors->has('title'))
                <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
            @endif
        </div>
        <div class="col-lg-3">
            <select class="form-control" name="type">
                <option value="1">Положительный</option>
                <option selected value="3">Нейтральный</option>
                <option value="2">Отрицательный</option>
            </select>
            @if ($errors->has('type'))
                <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <textarea rows="4" name="text" class="form-control" maxlength="999" placeholder="Текст отзыва"></textarea>
            @if ($errors->has('text'))
                <span class="help-block">
                                        <strong>{{ $errors->first('text') }}</strong>
                                    </span>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <button type="submit" class="form_submit_btn">Добавить</button>
        </div>
    </div>
</form>