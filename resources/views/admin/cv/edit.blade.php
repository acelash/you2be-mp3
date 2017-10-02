{{--/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.10.2016
 * Time: 23:58
 */--}}
@extends('admin.layouts.admin')

@section('pageTitle', 'Editare CV ID:' . $cv->id)

@section('headerScripts', '
    <link rel="stylesheet" href="'.env("APP_URL").'/public/css/style.css">
    <link rel="stylesheet" href="'.env("APP_URL").'/public/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
    <script src="'.env("APP_URL").'/public/js/bootstrap-selectpicker.js"></script>
    <script src="'.env("APP_URL").'/public/js/functions.js"></script>
')

@section('footerScripts', '')

@section('content')

    <div class="">
        <div class="page-title">
            <div class="title_left" >
                <h3> Editare CV: <a style="text-decoration: underline" href="{{url('cv/'.prepareSlugUrl($cv->id,$cv->position))}}">{{$cv->position}}</a> </h3>
            </div>

        </div>

        <div class="clearfix"></div>

        <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">


                        <script>
                            var cities = <?php echo json_encode($cities) ?>,
                                categories = <?php echo json_encode($categories) ?>,
                                subcategories = <?php echo json_encode($subcategories) ?>;
                        </script>
                        <div id="CvsList">

                            <form class="form-horizontal user_profile_form white_bg large_form"  method="POST"
                                  action="{{ url('admin/cv/'.$cv->id) }}">

                                @if (session('message'))
                                    <div class=" alert
                                    @if(session('success') == true) alert-success
                                      @elseif(session(
                                      'success') == false) alert-error
                                    @endif">
                                        <ul>
                                            <li>{{ session('message') }}</li>
                                        </ul>
                                    </div>
                                @endif
                                {{--
                                                              @if (count($errors) > 0)
                                                                    <div class="alert-error">
                                                                        <ul>
                                                                            @foreach ($errors->all() as $error)
                                                                                <li>{{ $error }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                @endif--}}
                                {{ csrf_field() }}

                                <div class="cv-block">
                                    <div class="col-sm-2 padding_left_0  @if ($errors->has('logo_file'))  error @endif" data-input_holder="logo_file">

                                        <div id="profileImage">
                                            <div class="cv-image candidate_logo">
                                                <img src="{{$cv->cv_logo ? url($cv->cv_logo) : url('public/images/new-user-image-default.png')}}">
                                            </div>
                                            <label for="newImgProf" class="imgLabel"> + Adauga poza </label>
                                            <input type="file" id="newImgProf" name="logo_file" class="select-file"  >
                                        </div>

                                        {!! printFieldErrors($errors,'logo_file') !!}

                                    </div>
                                    <div class="cv-form-group col-sm-10 padding_right_0" style="padding:10px 0 0 25px;">
                                        <label class="col-sm-2 control-label" for="CVName">Nume<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-4  @if ($errors->has('nume'))  error @endif">
                                            <input type="text" value="{{ old('nume') ?: $cv->nume  }}" name="nume" maxlength="20"
                                                   class="form-control" id="CVName" placeholder="Nume">
                                            {!! printFieldErrors($errors,'nume') !!}
                                        </div>
                                        <label class="col-sm-2 control-label" for="CVPrName">Prenume<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-4 padding_right_0  @if ($errors->has('prenume'))  error @endif">
                                            <input type="text" value="{{ old('prenume') ?: $cv->prenume  }}" maxlength="20"
                                                   name="prenume" class="form-control" id="CVPrName"
                                                   placeholder="Prenume">
                                            {!! printFieldErrors($errors,'prenume') !!}
                                        </div>
                                    </div>


                                    <div class="cv-form-group col-sm-10" style="padding:10px 0 0 25px;">

                                        <div class=" col-sm-6 padding_right_0 padding_left_0">
                                            <label for="name" class="col-sm-4 control-label">Judeţ<span class="required">*</span></label>
                                            <div class="col-sm-8  @if ($errors->has('region_id'))  error @endif">
                                                <select name="region_id" class="selectpicker" title="Selecteaza..."
                                                        data-live-search="true">
                                                    @foreach($regions as $region)
                                                        <option @if(old('region_id') == $region['id'] || (!old('region_id') && $cv->region_id == $region['id'])) selected
                                                                @endif value="{{$region['id']}}">{{$region['name']}}</option>
                                                    @endforeach
                                                </select>
                                                {!! printFieldErrors($errors,'region_id') !!}
                                            </div>
                                        </div>

                                        <div class=" col-sm-6 padding_right_0 padding_left_0" id="city_id"
                                             @if(!old('region_id') && !$cv->region_id) style="display: none" @endif>
                                            <label for="name" class="col-sm-4 control-label">Oraş</label>
                                            <div class="col-sm-8 padding_right_0  @if ($errors->has('city_id'))  error @endif">
                                                <select name="city_id" class="selectpicker" title="Selectează...">
                                                    @if(old('region_id') ||  $cv->region_id )
                                                        @foreach($cities[old('region_id') ?: $cv->region_id] as $city)
                                                            <option @if(old('city_id') == $city['id'] || (!old('city_id') && $cv->city_id == $city['id'])) selected
                                                                    @endif
                                                                    value="{{$city['id']}}">{{$city['name']}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                {!! printFieldErrors($errors,'city_id') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cv-block">
                                    <div class="cv-block-title">
                                        <span class="material-icons">&#xE7FD;</span>
                                        Informaţie personală
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-2 control-label" for="CVPhone">Telefon:<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-4  @if ($errors->has('tel_1'))  error @endif">
                                            <input type="text" value="{{ old('tel_1') ?: $cv->tel_1  }}" maxlength="20"
                                                   name="tel_1" class="form-control"
                                                   id="CVPhone" onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                   placeholder="Telefon">
                                            {!! printFieldErrors($errors,'tel_1') !!}
                                        </div>
                                        <label class="col-sm-2 control-label" for="CVPhone">Telefon 2:</label>
                                        <div class="col-sm-4  @if ($errors->has('tel_2'))  error @endif">
                                            <input type="text" value="{{ old('tel_2') ?: $cv->tel_2  }}" maxlength="20"
                                                   name="tel_2" class="form-control"
                                                   id="CVPhone" onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                   placeholder="Telefon">
                                            {!! printFieldErrors($errors,'tel_2') !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="col-sm-2 control-label" for="email">E-mail:<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-4  @if ($errors->has('email'))  error @endif">
                                            <input type="email" value="{{old('email') ?: $cv->email  }}" name="email" maxlength="70"
                                                   class="form-control"
                                                   id="email"
                                                   placeholder="E-mail">
                                            {!! printFieldErrors($errors,'email') !!}
                                        </div>
                                        <label class="col-sm-2 control-label" for="CVPhone">Data naşterii:<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-4  @if ($errors->has('birth_date'))  error @endif">
                                            <input type="text" onkeyup="return  checkDateField(this)"
                                                   value="{{old('birth_date') ?: $cv->birth_date ? date("d.m.Y", strtotime($cv->birth_date)) : ''  }}" name="birth_date"
                                                   class="form-control"
                                                   id="birth_date"
                                                   placeholder="zz.ll.aaaa">
                                            {!! printFieldErrors($errors,'birth_date') !!}
                                        </div>

                                    </div>

                                    <div class="row">
                                        <label class="col-sm-2 control-label" for="email">Website:</label>
                                        <div class="col-sm-4  @if ($errors->has('website'))  error @endif">
                                            <input type="text" {{old('website') ?: $cv->website  }} name="website" maxlength="90"
                                                   class="form-control"
                                                   id="email"
                                                   placeholder="Website">
                                            {!! printFieldErrors($errors,'website') !!}
                                        </div>
                                        <label class="col-sm-2 control-label" for="CVPhone">Skype:</label>
                                        <div class="col-sm-4  @if ($errors->has('skype'))  error @endif">
                                            <input type="text" {{old('skype') ?: $cv->skype }} name="skype"
                                                   class="form-control"
                                                   maxlength="70"
                                                   placeholder="Skype">
                                            {!! printFieldErrors($errors,'skype') !!}
                                        </div>
                                    </div>

                                    <div class="row">

                                        <label class="col-sm-2 control-label" for="CVPhone"
                                               style="padding-right: 25px;">Gen:<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-2  @if ($errors->has('sex'))  error @endif">
                                            <select name="sex" class="selectpicker" title="Selectează...">
                                                @foreach(config("constants.SEX") as $option)
                                                    <option @if(old('sex') == $option['value'] || (!old('sex') && $cv->sex == $option['value'])) selected
                                                            @endif value="{{$option['value']}}">{{$option['label']}}</option>
                                                @endforeach
                                            </select>
                                            {!! printFieldErrors($errors,'sex') !!}
                                        </div>

                                        <label class="col-sm-2 control-label" for="CVPhone">Stare civilă:<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-2  @if ($errors->has('stare_civila'))  error @endif">
                                            <select name="stare_civila" class="selectpicker" title="Selectează...">
                                                @foreach(config("constants.STARE_CIVILA") as $option)
                                                    <option @if(old('stare_civila') == $option['value'] || (!old('stare_civila') && $cv->stare_civila == $option['value'])) selected
                                                            @endif value="{{$option['value']}}">{{$option['label']}}</option>
                                                @endforeach
                                            </select>
                                            {!! printFieldErrors($errors,'stare_civila') !!}
                                        </div>


                                        <label class="col-sm-2 control-label" for="CVPhone">Copii:<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-2 @if ($errors->has('copii'))  error @endif">
                                            <select name="copii" class="selectpicker" title="Selectează...">
                                                @foreach(config("constants.COPII") as $option)
                                                    <option @if(old('copii') == $option['value'] || (!old('copii') && $cv->copii == $option['value'])) selected
                                                            @endif value="{{$option['value']}}">{{$option['label']}}</option>
                                                @endforeach
                                            </select>
                                            {!! printFieldErrors($errors,'copii') !!}
                                        </div>

                                    </div>

                                    <div class="row">
                                        <label class="col-sm-2 control-label">Despre mine:</label>
                                        <div class="col-sm-10 @if ($errors->has('description'))  error @endif">
                                            <textarea name="description" class="form-control" rows="5" maxlength="1000"
                                                      id="CVPreferedPos"
                                                      placeholder="">{{old('description') ?: $cv->description}}</textarea>
                                            {!! printFieldErrors($errors,'description') !!}
                                        </div>
                                    </div>

                                </div>
                                <div class="cv-block">
                                    <div class="cv-block-title">
                                        <span class="material-icons">&#xE8F9;</span>
                                        Cerinţe de bază:
                                    </div>

                                    <div class="row">
                                        <label class="col-sm-2 control-label">Postul dorit:<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-10 @if ($errors->has('position'))  error @endif">
                                            <input type="text" value="{{old('position') ?: $cv->position}}" maxlength="100"
                                                   name="position" class="form-control"
                                                   id="CVPreferedPos" placeholder="Postul dorit">
                                            {!! printFieldErrors($errors,'position') !!}
                                        </div>
                                    </div>

                                    <div class="row">

                                        <label for="name" class="col-sm-2 control-label">Domeniu de activitate<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-4 @if ($errors->has('category_id'))  error @endif">
                                            <select name="category_id" class="selectpicker" title="Selecteaza...">
                                                @foreach($categories as $category)
                                                    <option @if(old('category_id') == $category['id'] || (!old('category_id') && $cv->category_id == $category['id'])) selected
                                                            @endif value="{{$category['id']}}">{{$category['name']}}</option>
                                                @endforeach
                                            </select>
                                            {!! printFieldErrors($errors,'category_id') !!}
                                        </div>
                                        <div class=" col-sm-6 padding_right_0" id="subcategories"
                                             @if(!old('category_id') && !$cv->category_id) style="display: none" @endif >
                                            <label for="name" class="col-sm-3 control-label">Activitate</label>
                                            <div class="col-sm-9 @if ($errors->has('subcategory_id'))  error @endif">
                                                <select name="subcategory_id" class="selectpicker">
                                                    @if(old('category_id') || $cv->category_id)
                                                        @foreach($subcategories[old('category_id') ?:  $cv->category_id] as $subcategory)
                                                            <option @if(old('subcategory_id') == $subcategory['id'] || (!old('subcategory_id') && $cv->subcategory_id ==  $subcategory['id'])) selected
                                                                    @endif value="{{$subcategory['id']}}">{{$subcategory['name']}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                {!! printFieldErrors($errors,'subcategory_id') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="col-sm-2 control-label">Salariu minim:<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-3 @if ($errors->has('salary_min'))  error @endif">
                                            <input type="number" value="{{$cv->salary_min}}"
                                                   name="salary_min" class="form-control" min="0" max="9999999" maxlength="7"
                                                   id="salary_min" placeholder="De la">
                                            {!! printFieldErrors($errors,'salary_min') !!}
                                        </div>
                                        <div class="col-sm-2 @if ($errors->has('salary_currency'))  error @endif">
                                            <select name="salary_currency" id="salary_currency" class="selectpicker">
                                                @foreach(config("constants.CURRENCY") as $option)
                                                    <option @if(old('salary_currency') == $option['value'] || $cv->salary_currency ==  $option['value']) selected
                                                            @endif value="{{$option['value']}}">{{$option['label']}}</option>
                                                @endforeach
                                            </select>
                                            {!! printFieldErrors($errors,'salary_currency') !!}
                                        </div>
                                        <div class="col-sm-2 @if ($errors->has('negotiable'))  error @endif">
                                            <div class="checkbox">
                                                <label style="font-size: 15px;">
                                                    <input @if( $cv->negotiable == 1) checked
                                                           @endif type="checkbox"
                                                           name="negotiable" class=""
                                                           id="negotiable"
                                                           value="1">
                                                    Negociabil
                                                </label>
                                            </div>
                                            {!! printFieldErrors($errors,'negotiable') !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label for="name" class="col-sm-2 control-label">Tip contract:<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-10 @if ($errors->has('contract_type'))  error @endif">
                                            <select name="contract_type" class="selectpicker">
                                                @foreach(config("constants.TIP_CONTRACT") as $option)
                                                    <option @if(old('contract_type') == $option['value'] || $cv->contract_type == $option['value']) selected
                                                            @endif value="{{$option['value']}}">{{$option['label']}}</option>
                                                @endforeach
                                            </select>
                                            {!! printFieldErrors($errors,'contract_type') !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label for="name" class="col-sm-2 control-label">Grafic de muncă:<span
                                                    class="required">*</span></label>
                                        <div class="col-sm-10 @if ($errors->has('work_graphic'))  error @endif">
                                            <select name="work_graphic" class="selectpicker">
                                                @foreach(config("constants.GRAFIC_DE_LUCRU") as $option)
                                                    <option @if(old('work_graphic') == $option['value'] || $cv->work_graphic == $option['value']) selected
                                                            @endif value="{{$option['value']}}">{{$option['label']}}</option>
                                                @endforeach
                                            </select>
                                            {!! printFieldErrors($errors,'work_graphic') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="cv-block">
                                    <div class="cv-block-title">
                                    <span class="material-icons">
                                      assignment
                                    </span>
                                        Experienţă profesională:
                                    </div>

                                    <?php
                                    $old_experience_position = old('experience_position') ?: [];
                                    $experiencesCount = $cv->experience()->count() > count($old_experience_position) ? $cv->experience()->count() : count($old_experience_position);
                                    if ($cv->experience()->count() > 0) {
                                        $experiences = $cv->experience()->get()->toArray();
                                        $firstExperience = $cv->experience()->first()->toArray();
                                    } else {
                                        $firstExperience = [];
                                        $experiences = [];
                                    }
                                    ?>


                                    {{--aratam default un block--}}

                                    <div class="experience_block">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Funcţia ocupată:</label>
                                            <div class="col-sm-10 @if ($errors->has('experience_position_0'))  error @endif">
                                                <input type="text" maxlength="100"
                                                       value="{{fillFormField('experience_position',$firstExperience,null,'position')}}"
                                                       name="experience_position[]"
                                                       class="form-control"
                                                       placeholder="Functia">
                                                {!! printFieldErrors($errors,'experience_position_0') !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Compania:</label>
                                            <div class="col-sm-10 @if ($errors->has('experience_company_0'))  error @endif">
                                                <input type="text" maxlength="100"
                                                       value="{{fillFormField('experience_company',$firstExperience,null,'company')}}"
                                                       name="experience_company[]"
                                                       class="form-control"
                                                       placeholder="Compania in care ai activat">
                                                {!! printFieldErrors($errors,'experience_company_0') !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Perioada:</label>
                                            <div class="col-sm-3 @if ($errors->has('experience_from_0'))  error @endif">
                                                <input type="text"
                                                       value="{{fillFormField('experience_from',$firstExperience,null,'from')}}"
                                                       name="experience_from[]" onkeyup="return  checkDateField(this,true)"
                                                       class="form-control"
                                                       placeholder="de la...">
                                                {!! printFieldErrors($errors,'experience_from_0') !!}
                                            </div>

                                            <div class="col-sm-3 @if ($errors->has('experience_till_0'))  error @endif">
                                                <input type="text" name="experience_till[]" onkeyup="return  checkDateField(this,true)"
                                                       class="form-control"
                                                       @if(fillFormField('experience_now',$firstExperience,null,'now') == 1)
                                                       disabled value=""
                                                       @else
                                                       value="{{fillFormField('experience_till',$firstExperience,null,'till')}}"
                                                       @endif
                                                       placeholder="pînă la...">
                                                {!! printFieldErrors($errors,'experience_till_0') !!}
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="checkbox">
                                                    <label style="font-size: 15px;">
                                                        <input type='hidden' value='0'
                                                               @if(fillFormField('experience_now',$firstExperience,null,'now') == 1)
                                                               disabled
                                                               @endif
                                                               name='experience_now[]'>
                                                        <input type="checkbox"

                                                               @if(fillFormField('experience_now',$firstExperience,null,'now') == 1) checked
                                                               @endif

                                                               name="experience_now[]" class=""
                                                               id="experience_now"
                                                               onchange="onPresentToggle(this,'experience')"
                                                               value="1">
                                                        Prezent
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Descriere:</label>
                                            <div class="col-sm-10 @if ($errors->has('experience_description_0'))  error @endif">
                                            <textarea rows="5" class="form-control" maxlength="500"
                                                      name="experience_description[]">{{fillFormField('experience_description',$firstExperience,null,'description')}}</textarea>
                                                {!! printFieldErrors($errors,'experience_description_0') !!}
                                            </div>
                                        </div>
                                    </div>

                                    {{--cite un block adaugator daca este in baza info sau doar in old()--}}
                                    @if($experiencesCount > 1)
                                        @for($i = 1; $i < $experiencesCount; $i++)
                                            <?php $id = uniqid(); ?>
                                            <div class="experience_block" id="{{$id}}">
                                                <hr>
                                                <div class="col-sm-11">
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Funcţia ocupată:</label>
                                                        <div class="col-sm-10 @if ($errors->has('experience_position_'.$i))  error @endif">
                                                            <input type="text" maxlength="100"
                                                                   value="{{fillFormField('experience_position',$experiences,$i,'position')}}"
                                                                   name="experience_position[]" class="form-control"
                                                                   id="CVHadPost" placeholder="Functia">
                                                            {!! printFieldErrors($errors,'experience_position_'.$i) !!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Compania:</label>
                                                        <div class="col-sm-10 @if ($errors->has('experience_company_'.$i))  error @endif">
                                                            <input type="text" maxlength="100"
                                                                   value="{{fillFormField('experience_company',$experiences,$i,'company')}}"
                                                                   name="experience_company[]" class="form-control"
                                                                   id="CVHComp"
                                                                   placeholder="Compania in care ai activat">
                                                            {!! printFieldErrors($errors,'experience_company_'.$i) !!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Perioada:</label>
                                                        <div class="col-sm-3 @if ($errors->has('experience_from_'.$i))  error @endif">
                                                            <input type="text" onkeyup="return  checkDateField(this,true)"
                                                                   value="{{fillFormField('experience_from',$experiences,$i,'from')}}"
                                                                   name="experience_from[]" class="form-control"
                                                                   id="CVHdate1Mon" placeholder="de la...">
                                                            {!! printFieldErrors($errors,'experience_from_'.$i) !!}
                                                        </div>

                                                        <div class="col-sm-3  @if ($errors->has('experience_till_'.$i))  error @endif">
                                                            <input type="text" onkeyup="return  checkDateField(this,true)"
                                                                   @if(fillFormField('experience_now',$experiences,$i,'now') == 1)
                                                                   disabled value=""
                                                                   @else
                                                                   value="{{fillFormField('experience_till',$experiences,$i,'till')}}"
                                                                   @endif
                                                                   name="experience_till[]" class="form-control"
                                                                   id="CVHdate2Mon" placeholder="pînă la...">
                                                            {!! printFieldErrors($errors,'experience_till_'.$i) !!}
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="checkbox">
                                                                <label style="font-size: 15px;">
                                                                    <input type='hidden' value='0'
                                                                           @if(fillFormField('experience_now',$experiences,$i,'now') == 1)
                                                                           disabled @endif
                                                                           name='experience_now[]'>
                                                                    <input type="checkbox"

                                                                           @if(fillFormField('experience_now',$experiences,$i,'now') == 1) checked
                                                                           @endif

                                                                           name="experience_now[]" class=""
                                                                           id="experience_now"
                                                                           onchange="onPresentToggle(this,'experience')"
                                                                           value="1">
                                                                    Prezent
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="CVDescription" class="col-sm-2 control-label">Descriere:</label>
                                                        <div class="col-sm-10 @if ($errors->has('experience_description_'.$i))  error @endif">
                                                         <textarea rows="5" class="form-control" maxlength="500"
                                                                   name="experience_description[]"
                                                                   id="CVDescription">{{fillFormField('experience_description',$experiences,$i,'description')}}</textarea>
                                                            {!! printFieldErrors($errors,'experience_description_'.$i) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1 remove_block"
                                                     onclick="removeBlock(this,'experience_block')">Şterge
                                                </div>
                                            </div>
                                        @endfor
                                    @endif

                                    <div class="col-sm-12 add_block" onclick="addBlock(this,'experience')">Adaugă încă
                                        un job
                                        anterior
                                    </div>
                                </div>


                                <div class="cv-block">
                                    <div class="cv-block-title">
                                    <span class="material-icons">
                                      &#xE80C;
                                    </span>
                                        Studii
                                    </div>

                                    <?php
                                    $old_study_institution = old('study_institution') ?: [];
                                    $studiesCount = $cv->study()->count() > count($old_study_institution) ? $cv->study()->count() : count($old_study_institution);
                                    if ($cv->study()->count() > 0) {
                                        $studies = $cv->study()->get()->toArray();
                                        $firstStudy = $cv->study()->first()->toArray();
                                    } else {
                                        $firstStudy = [];
                                        $studies = [];
                                    }
                                    ?>


                                    {{--aratam default un block--}}
                                    <div class="study_block">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Instituţia de învăţămînt:</label>
                                            <div class="col-sm-10  @if ($errors->has('study_institution_0'))  error @endif">
                                                <input type="text" maxlength="100"
                                                       value="{{fillFormField('study_institution',$firstStudy,null,'institution')}}"
                                                       name="study_institution[]"
                                                       class="form-control"
                                                       placeholder="Instituţia de învăţămînt">
                                                {!! printFieldErrors($errors,'study_institution_0') !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Domeniul de studii:</label>
                                            <div class="col-sm-10 @if ($errors->has('study_speciality_0'))  error @endif">
                                                <input type="text" maxlength="100"
                                                       value="{{fillFormField('study_speciality',$firstStudy,null,'speciality')}}"
                                                       name="study_speciality[]"
                                                       class="form-control"
                                                       placeholder="Facultatea, specialitatea...">
                                                {!! printFieldErrors($errors,'study_speciality_0') !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Perioada:</label>
                                            <div class="col-sm-3 @if ($errors->has('study_from_0'))  error @endif">
                                                <input type="text" onkeyup="return  checkDateField(this,true)"
                                                       value="{{fillFormField('study_from',$firstStudy,null,'from')}}"
                                                       name="study_from[]"
                                                       class="form-control"
                                                       placeholder="de la...">
                                                {!! printFieldErrors($errors,'study_from_0') !!}
                                            </div>

                                            <div class="col-sm-3 @if ($errors->has('study_till_0'))  error @endif">
                                                <input type="text" name="study_till[]" onkeyup="return  checkDateField(this,true)"
                                                       class="form-control"
                                                       @if(fillFormField('study_now',$firstStudy,null,'now') == 1)
                                                       disabled value=""
                                                       @else
                                                       value="{{fillFormField('study_till',$firstStudy,null,'till')}}"
                                                       @endif

                                                       placeholder="pînă la...">
                                                {!! printFieldErrors($errors,'study_till_0') !!}
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="checkbox">
                                                    <label style="font-size: 15px;">
                                                        <input type='hidden' value='0'
                                                               @if(fillFormField('study_now',$firstStudy,null,'now') == 1)
                                                               disabled
                                                               @endif
                                                               name='study_now[]'>
                                                        <input type="checkbox"

                                                               @if(fillFormField('study_now',$firstStudy,null,'now') == 1) checked
                                                               @endif

                                                               name="study_now[]" class=""
                                                               id="study_now"
                                                               onchange="onPresentToggle(this,'study')"
                                                               value="1">
                                                        Prezent
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Descriere:</label>
                                            <div class="col-sm-10 @if ($errors->has('study_description_0'))  error @endif">
                                            <textarea rows="5" class="form-control" maxlength="500"
                                                      name="study_description[]">{{fillFormField('study_description',$firstStudy,null,'description')}}</textarea>
                                                {!! printFieldErrors($errors,'study_description_0') !!}
                                            </div>
                                        </div>
                                    </div>

                                    {{--cite un block adaugator daca este in baza info sau doar in old()--}}
                                    @if($studiesCount > 1)
                                        @for($i = 1; $i < $studiesCount; $i++)
                                            <?php $id = uniqid(); ?>
                                            <div class="study_block" id="{{$id}}">
                                                <hr>
                                                <div class="col-sm-11">
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Instituţia de
                                                            învăţămînt:</label>
                                                        <div class="col-sm-10 @if ($errors->has('study_institution_'.$i))  error @endif">
                                                            <input type="text" maxlength="100"
                                                                   value="{{fillFormField('study_institution',$studies,$i,'institution')}}"
                                                                   name="study_institution[]" class="form-control"
                                                                   id="CVHadPost"
                                                                   placeholder="Instituţia de învăţămînt">
                                                            {!! printFieldErrors($errors,'study_institution_'.$i) !!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Domeniul de
                                                            studii:</label>
                                                        <div class="col-sm-10 @if ($errors->has('study_speciality_'.$i))  error @endif">
                                                            <input type="text" maxlength="100"
                                                                   value="{{fillFormField('study_speciality',$studies,$i,'speciality')}}"
                                                                   name="study_speciality[]" class="form-control"
                                                                   id="CVHComp"
                                                                   placeholder="Facultatea, specialitatea...">
                                                            {!! printFieldErrors($errors,'study_speciality_'.$i) !!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Perioada:</label>
                                                        <div class="col-sm-3 @if ($errors->has('study_from_'.$i))  error @endif">
                                                            <input type="text" onkeyup="return  checkDateField(this,true)"
                                                                   value="{{fillFormField('study_from',$studies,$i,'from')}}"
                                                                   name="study_from[]" class="form-control"
                                                                   id="CVHdate1Mon" placeholder="de la...">
                                                            {!! printFieldErrors($errors,'study_from_'.$i) !!}
                                                        </div>

                                                        <div class="col-sm-3 @if ($errors->has('study_till_'.$i))  error @endif">
                                                            <input type="text" onkeyup="return  checkDateField(this,true)"
                                                                   @if(fillFormField('study_now',$studies,$i,'now') == 1)
                                                                   disabled value=""
                                                                   @else
                                                                   value="{{fillFormField('study_till',$studies,$i,'till')}}"
                                                                   @endif
                                                                   name="study_till[]" class="form-control"
                                                                   id="CVHdate2Mon" placeholder="pînă la...">
                                                            {!! printFieldErrors($errors,'study_till_'.$i) !!}
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="checkbox">
                                                                <label style="font-size: 15px;">
                                                                    <input type='hidden' value='0'
                                                                           @if(fillFormField('study_now',$studies,$i,'now') == 1)
                                                                           disabled @endif
                                                                           name='study_now[]'>
                                                                    <input type="checkbox"

                                                                           @if(fillFormField('study_now',$studies,$i,'now') == 1) checked
                                                                           @endif

                                                                           name="study_now[]" class=""
                                                                           id="study_now"
                                                                           onchange="onPresentToggle(this,'study')"
                                                                           value="1">
                                                                    Prezent
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="CVDescription" class="col-sm-2 control-label">Descriere:</label>
                                                        <div class="col-sm-10 @if ($errors->has('study_description_'.$i))  error @endif">
                                                         <textarea rows="5" class="form-control" maxlength="500"
                                                                   name="study_description[]"
                                                                   id="CVDescription">{{fillFormField('study_description',$studies,$i,'description')}}</textarea>
                                                            {!! printFieldErrors($errors,'study_description_'.$i) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1 remove_block"
                                                     onclick="removeBlock(this,'study_block')">Şterge
                                                </div>
                                            </div>
                                        @endfor
                                    @endif

                                    <div class="col-sm-12 add_block" onclick="addBlock(this,'study')">Adaugă educaţie
                                    </div>
                                </div>

                                <div class="cv-block">
                                    <div class="cv-block-title">
                                    <span class="material-icons">
                                      &#xE614;
                                    </span>
                                        Aptitudini
                                    </div>

                                    <?php
                                    $old_lang_name = old('lang_name') ?: [];
                                    $languagesCount = $cv->language()->count() > count($old_lang_name) ? $cv->language()->count() : count($old_lang_name);
                                    if ($cv->language()->count() > 0) {
                                        $languages = $cv->language()->get()->toArray();
                                        $firstLanguage = $cv->language()->first()->toArray();
                                    } else {
                                        $firstLanguage = [];
                                        $languages = [];
                                    }
                                    ?>

                                    {{--aratam default un block--}}
                                    <div class="lang_block">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Limba:</label>
                                            <div class="col-sm-5">
                                                <select name="lang_name[]" class="selectpicker"
                                                        title="Selectează limba...">
                                                    @foreach(config("constants.LANGUAGES") as $option)
                                                        <option @if($option['value']==fillFormField('lang_name',$firstLanguage,null,'name'))) selected @endif value="{{$option['value']}}">{{$option['label']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="btn-group bootstrap-select">
                                                    <select name="lang_level[]" class="selectpicker"
                                                            title="Nivelul de cunoaştere...">
                                                        @foreach(config("constants.LANGUAGE_LEVEL") as $option)
                                                            <option @if($option['value']==fillFormField('lang_level',$firstLanguage,null,'level'))) selected @endif value="{{$option['value']}}">{{$option['label']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($languagesCount > 1)
                                        @for($i = 1; $i < $languagesCount; $i++)
                                            <div class="lang_block">
                                                <div class="col-sm-11">
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label"></label>
                                                        <div class="col-sm-5">
                                                            <select name="lang_name[]" class="selectpicker"
                                                                    title="Selectează limba...">
                                                                @foreach(config("constants.LANGUAGES") as $option)
                                                                    <option @if($option['value']==fillFormField('lang_name',$languages,$i,'name')) selected @endif  value="{{$option['value']}}">{{$option['label']}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <div class="btn-group bootstrap-select">
                                                                <select name="lang_level[]" class="selectpicker"
                                                                        title="Nivelul de cunoaştere...">
                                                                    @foreach(config("constants.LANGUAGE_LEVEL") as $option)
                                                                        <option @if($option['value']==fillFormField('lang_level',$languages,$i,'level')) selected @endif value="{{$option['value']}}">{{$option['label']}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-sm-1 remove_block"
                                                     onclick="removeBlock(this,'lang_block')">Şterge
                                                </div>
                                            </div>
                                        @endfor
                                    @endif
                                    <div class="col-sm-12 add_block"
                                         onclick="addBlock(this,'lang')">Adaugă încă o limba cunoscută
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Permis de conducere:</label>
                                    <div class="col-sm-10">
                                        <div class="col-sm-2">
                                            <div class="checkbox">
                                                <label style="font-size: 15px;">
                                                    <input type="checkbox"  @if(old('permis_a') == 1 || $cv->permis_a == 1) checked @endif name="permis_a"  value="1">
                                                    A
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="checkbox">
                                                <label style="font-size: 15px;">
                                                    <input type="checkbox"
                                                           @if(old('permis_b') == 1 || $cv->permis_b == 1) checked @endif
                                                           name="permis_b" class=""
                                                           value="1">
                                                    B
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="checkbox">
                                                <label style="font-size: 15px;">
                                                    <input type="checkbox" @if(old('permis_c') == 1 || $cv->permis_c == 1) checked @endif
                                                    name="permis_c" class=""
                                                           value="1">
                                                    C
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="checkbox">
                                                <label style="font-size: 15px;">
                                                    <input type="checkbox" @if(old('permis_d') == 1 || $cv->permis_d == 1) checked @endif
                                                    name="permis_d" class=""
                                                           value="1">
                                                    D
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-default form_submit_btn">
                                        @if($cv->state_id == config('constants.STATE_DRAFT')) Publică @else Salvează modificările @endif
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div style="display: none">
                            <div class="experience">
                                <hr>
                                <div class="col-sm-11">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Funcţia ocupată:</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="" name="experience_position[]" class="form-control"
                                                   id="CVHadPost" placeholder="Functia">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Compania:</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="" name="experience_company[]" class="form-control"
                                                   id="CVHComp"
                                                   placeholder="Compania in care ai activat">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Perioada:</label>
                                        <div class="col-sm-3">
                                            <input type="text" value="" name="experience_from[]" class="form-control"
                                                   id="CVHdate1Mon" placeholder="de la...">
                                        </div>

                                        <div class="col-sm-3">
                                            <input type="text" value="" name="experience_till[]" class="form-control"
                                                   id="CVHdate2Mon" placeholder="pînă la...">
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="checkbox">
                                                <label style="font-size: 15px;">
                                                    <input type='hidden' value='0' name='experience_now[]'>
                                                    <input type="checkbox"
                                                           name="experience_now[]" class=""
                                                           id="experience_now"
                                                           onchange="onPresentToggle(this,'experience')"
                                                           value="1">
                                                    Prezent
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="CVDescription" class="col-sm-2 control-label">Descriere:</label>
                                        <div class="col-sm-10">
                                            <textarea rows="5" class="form-control" maxlength="500"
                                                      name="experience_description[]" id="CVDescription"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-1 remove_block" onclick="removeBlock(this,'experience_block')">Şterge</div>
                            </div>

                            <div class="study">
                                <hr>
                                <div class="col-sm-11">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Instituţia de învăţămînt:</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="" name="study_institution[]" class="form-control"
                                                   id="CVHadPost" placeholder="Instituţia de învăţămînt">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Domeniul de studii:</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="" name="study_speciality[]" class="form-control"
                                                   id="CVHComp"
                                                   placeholder="Facultatea, specialitatea...">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Perioada:</label>
                                        <div class="col-sm-3">
                                            <input type="text" value="" name="study_from[]" class="form-control"
                                                   id="CVHdate1Mon" placeholder="de la...">
                                        </div>

                                        <div class="col-sm-3">
                                            <input type="text" value="" name="study_till[]" class="form-control"
                                                   id="CVHdate2Mon" placeholder="pînă la...">
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="checkbox">
                                                <label style="font-size: 15px;">
                                                    <input type='hidden' value='0' name='study_now[]'>
                                                    <input type="checkbox"
                                                           name="study_now[]" class=""
                                                           id="study_now"
                                                           onchange="onPresentToggle(this,'study')"
                                                           value="1">
                                                    Prezent
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="CVDescription" class="col-sm-2 control-label">Descriere:</label>
                                        <div class="col-sm-10">
                                            <textarea rows="5" class="form-control" maxlength="500"
                                                      name="study_description[]" id="CVDescription"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-1 remove_block" onclick="removeBlock(this,'study_block')">Şterge</div>
                            </div>
                            <div class="lang">
                                <hr>
                                <div class="col-sm-11">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> </label>
                                        <div class="col-sm-5">
                                            <select name="lang_name[]" class="selectpicker2"
                                                    title="Selectează limba...">
                                                @foreach(config("constants.LANGUAGES") as $option)
                                                    <option  value="{{$option['value']}}">{{$option['label']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="btn-group bootstrap-select">
                                                <select name="lang_level[]" class="selectpicker2"
                                                        title="Nivelul de cunoaştere...">
                                                    @foreach(config("constants.LANGUAGE_LEVEL") as $option)
                                                        <option  value="{{$option['value']}}">{{$option['label']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-1 remove_block"
                                     onclick="removeBlock(this,'lang_block')">Şterge
                                </div>
                            </div>
                        </div>
                        <script>

                            // on ready
                            $(document).ready(function () {

                                var negotiable_checkbox = $('#negotiable'),
                                    salary_currency = $("select[name='salary_currency']");

                                negotiable_checkbox.change(function () {
                                    if ($(this).is(":checked")) {
                                        $("#salary_min").val('').prop("disabled", true);
                                        salary_currency.prop("disabled", true);
                                    } else {
                                        $("#salary_min").prop("disabled", false);
                                        salary_currency.prop("disabled", false);
                                    }
                                    salary_currency.selectpicker('refresh');
                                });

                                if (negotiable_checkbox.is(":checked")) {
                                    $("#salary_min").val('').prop("disabled", true);
                                    salary_currency.prop("disabled", true);
                                    salary_currency.selectpicker('refresh');
                                }

                                $("select[name='category_id']").on('change', function () {
                                    var options = '<option value="">Toate</option>',
                                        categories = $("select[name='category_id']").selectpicker('val'),
                                        subcategories_select = $("select[name='subcategory_id']");

                                    $.each(subcategories[categories], function (i, subcategory) {
                                        options += '<option value="' + subcategory.id + '">' + subcategory.name + '</option>';
                                    });

                                    $("#subcategories").show();
                                    subcategories_select.html(options);
                                    subcategories_select.selectpicker('refresh');
                                });

                                $("select[name='region_id']").on('change', function () {
                                    var options = '<option value="">Toate</option>',
                                        region = $("select[name='region_id']").selectpicker('val'),
                                        cities_select = $("select[name='city_id']");

                                    if (region) {
                                        $.each(cities[region], function (i, city) {
                                            options += '<option value="' + city.id + '">' + city.name + '</option>';
                                        });

                                        $("#city_id").show();
                                        cities_select.html(options);
                                        cities_select.selectpicker('refresh');
                                    }
                                });
                            }); // end on ready

                            function addBlock(btn, css_class) {
                                var id = makeid();
                                $("<div id='" + id + "' class='" + css_class + "_block'>" + $("." + css_class).html() + "</div>").insertBefore($(btn));


                                switch (css_class){
                                    case 'lang':
                                        $('#'+id+' .selectpicker2').selectpicker('render');
                                        break;
                                        /* case 'experience':
                                         case 'study':
                                         $('#' + id + ' input[name="' + css_class + '_from[]"]').datetimepicker({
                                         viewMode: 'years',
                                         format: 'MM.YYYY',
                                         locale: 'ro',
                                         widgetPositioning: {
                                         horizontal: 'left',
                                         vertical: 'top'
                                         }
                                         });
                                         $('#' + id + ' input[name="' + css_class + '_till[]"]').datetimepicker({
                                         viewMode: 'years',
                                         format: 'MM.YYYY',
                                         locale: 'ro',
                                         widgetPositioning: {
                                         horizontal: 'left',
                                         vertical: 'top'
                                         }
                                         });
                                         break;*/
                                }
                            }

                            function removeBlock(btn, css_class) {
                                $(btn).closest('.' + css_class).hide(400).remove();
                            }

                            function onPresentToggle(checkbox, css_class) {
                                if ($(checkbox).prop('checked')) {
                                    $(checkbox).closest('.' + css_class + '_block').find('input[name="' + css_class + '_till[]"]').val('').prop('disabled', true);
                                    $(checkbox).parent().find('input[type="hidden"]').prop('disabled', true);
                                } else {
                                    $(checkbox).closest('.' + css_class + '_block').find('input[name="' + css_class + '_till[]"]').removeAttr("disabled");
                                    $(checkbox).parent().find('input[type="hidden"]').removeAttr("disabled");
                                }
                            }

                            var isCvLogoAjaxLoading = false;

                            $('#newImgProf').on('change', function () {

                                if (isCvLogoAjaxLoading) return true;
                                else isCvLogoAjaxLoading = true;

                                var form = $(".user_profile_form"),
                                    error_messages = form.find('.alert-error'),
                                    data = {};

                                if (error_messages.length) {
                                    clearErrorClass(form);
                                    error_messages.remove();
                                }

                                var file_data = $('#newImgProf').prop('files')[0];
                                var form_data = new FormData();
                                form_data.append('logo_file', file_data);

                                var previousSrc = $("#profileImage .cv-image img").prop('src');

                                $("#profileImage .cv-image img").prop('src',getBaseUrl()+'public/images/loader2.gif');
                                $.ajax({
                                    type: "POST",
                                    processData: false,
                                    contentType: false,
                                    url: '{{ url('admin/cv/update_logo/'.$cv->id) }}',
                                    data: form_data,
                                    //dataType: 'json',
                                    success: function (response) {
                                        isCvLogoAjaxLoading = false;
                                        if (response.status == 'ok') {
                                            $("#profileImage .cv-image img").prop('src',response.image);

                                        } else {
                                            form.prepend(prepareAjaxErrors(response.message,form));
                                            $("#profileImage .cv-image img").prop('src',previousSrc);
                                        }
                                    },
                                    error: function (request, status, error_message) {
                                        isCvLogoAjaxLoading = false;
                                        var response = request.responseJSON;
                                        form.prepend(prepareAjaxErrors(response, form,'cv_logo_alert'));
                                        $("#profileImage .cv-image img").prop('src',previousSrc);
                                    }
                                });


                            });

                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
            width: 100%;
        }
        .user_profile_form {
            padding: 0;
            box-shadow: none;
            border-radius: 0;
            margin: 10px auto 0;
        }
        #CvsList, #poket-container {

             padding: 0;
            background: white;
        }
        .page-title .title_left {
            width: 100%
        }
    </style>

@endsection
