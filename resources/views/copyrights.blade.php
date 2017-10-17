@extends('layouts.app')

@section('pageTitle', trans('words.home_title')." | mp3cloud.su")
@section('pageDescription', trans('words.home_description'))

@section('content')
    <style>{!!file_get_contents(public_path('css/homepage.css'))!!}</style>
    <div class="container page_content">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1 style="text-align: center;padding: 0 30px;">
                    @lang('words.copyrights')
                </h1>

                @if($locale == 'ru')

                    <ul>
                        <li>Мы уважаем права интеллектуальной собственности других лиц. Мы можем удалить любой Контент, у которого есть основания полагать,
                            нарушает любой из интеллектуальных прав собственности других лиц.
                        </li>
                        <li>Хотя мы не подпадаем под действие закона Соединенных Штатов, мы добровольно подчиняемся цифровому
                            Закон об авторском праве в области культуры. В соответствии спунктом 17 раздела 512 (c) (2) Кодекса Соединенных Штатов,
                            если вы считаете, что какой-либо из ваших материалов, защищенных авторским правом, нарушается на Сайте,
                            мы назначили агента для получения уведомлений о заявленном нарушении авторских прав.
                            Уведомления должны отправляться по электронной почте: <img src="{{asset('public/images/mail.png')}}">
                        </li>
                        <li>Все уведомления, не имеющие отношения к нам или недействительные по закону, не получат ответа или действия.
                            Эффективное уведомление о заявленном нарушении должно включать в себя в основном следующее:                                                        
                            <ul>
                                <li> Идентификация защищенной авторским правом работы, которая считается нарушенной. Пожалуйста опишите работу и,
                                    по возможности, включить копию или местоположение (например, URL-адрес) утвержденной версии работы;
                                </li>
                                <li> Идентификация материала, который считается нарушающим и его местоположение или, для результатов поиска,
                                    идентификация ссылки или ссылки на материал или деятельность, заявленная как нарушающая.
                                    Опишите материал и укажите URL-адрес или любая другая соответствующая информация, которая позволит нам
                                    найти материал на Сайте или в Интернете;
                                </li>
                                <li> Информация, которая позволит нам связаться с вами, включая ваш адрес, номер телефона и, если имеется,
                                    ваш адрес электронной почты;
                                </li>                     
                                <li> Утверждение, что информация в уведомлении является точной и что вы являетесь владельцем или уполномочены
                                    действовать от имени владелеца произведения, которое, как утверждается, нарушено;                                                                    
                                </li>
                                <li> Физическая или электронная подпись от владельца авторских прав или уполномоченного представителя.</li>
                            </ul>
                        </li>
                    </ul>

                @else
                    <ul>
                        <li>We respect the intellectual property rights of others. We may in our sole
                            discretion remove any Content we have reason to believe violates any of the intellectual
                            property rights of others.
                        </li>

                        <li>Although we are not subject to United States law, we voluntarily comply with the Digital
                            Millennium Copyright Act. Pursuant to Title 17, Section 512(c)(2) of the United States Code,
                            if you believe that any of your copyrighted material is being infringed on the Website, we
                            have designated an agent to receive notifications of claimed copyright infringement.
                            Notifications should be e-mailed to <img src="{{asset('public/images/mail.png')}}">
                        </li>
                        <li>All notifications not relevant to us or ineffective under the law will receive no response
                            or action thereupon. An effective notification of claimed infringement must include substantially the following:
                            <ul>
                                <li>Identification of the copyrighted work that is believed to be infringed. Please
                                    describe the work and, where possible, include a copy or the location (e.g., a URL)
                                    of an authorized version of the work;
                                </li>
                                <li>Identification of the material that is believed to be infringing and its location
                                    or, for search results, identification of the reference or link to material or
                                    activity claimed to be infringing. Please describe the material and provide a URL or
                                    any other pertinent information that will allow us to locate the material on the
                                    Website or on the Internet;
                                </li>
                                <li>Information that will allow us to contact you, including your address, telephone
                                    number and, if available, your e-mail address;
                                </li>
                                <li>A statement that you have a good faith belief that the use of the material
                                    complained of is not authorized by you, your agent or the law;
                                </li>
                                <li>A statement that the information in the notification is accurate and that under
                                    penalty of perjury that you are the owner or are authorized to act on behalf of the
                                    owner of the work that is allegedly infringed;
                                </li>
                                <li>A physical or electronic signature from the copyright holder or an authorized
                                    representative.
                                </li>
                            </ul>
                        </li>
                    </ul>

                @endif


            </div>
        </div>
        @include("partials.footer")
    </div>

@endsection
