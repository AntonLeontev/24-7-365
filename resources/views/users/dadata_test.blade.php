@extends('layouts.app')


@section('title','Dadata Test')

@section('content')

@dd($data[0])


<div class="container">
<form id="profile-organization-form">
  @csrf
<div class="col mt-5"><h2>Данные по организации</h2></div>


<input type="hidden" id="profile-form-organization-url" name="handler_url" value = '{{ route("users.profile.save", $user->id) }}'>
<input type="hidden"  name="org_id" @isset($organization->id)  value='{{$organization->id}}' @endisset>

<div class="mb-3">
  <label  class="form-label">Название организации</label>
  <input type="text" class="form-control" name="title" placeholder="пример: ООО КомпанияН" @isset($organization->title) value="{{$organization->title}}" @endisset>
</div>
<div class="mb-3">
  <label class="form-label">Организационная форма</label>
  <select class="form-select" name="type" aria-label="Default select example">
  <option selected> --- </option>
  <option value="1" @if (isset($organization->type) && $organization->type==1)   selected  @endif >ООО</option>
  <option value="2" @if (isset($organization->type) && $organization->type==2)   selected  @endif >ИП</option>
</select>
</div>
<div class="mb-3">
  <label  class="form-label">ИНН</label>
  <input type="text" class="form-control"  name="inn" placeholder="пример: NNNNXXXXXXCC" @isset($organization->inn) value="{{$organization->inn}}" @endisset>
</div>
<div class="mb-3">
  <label  class="form-label">ОГРН (ИП)</label>
  <input type="text" class="form-control"  name="ogrn" placeholder="пример: 1026605606620" @isset($organization->ogrn) value="{{$organization->ogrn}}" @endisset>
</div>
<div class="mb-3">
  <label  class="form-label">КПП</label>
  <input type="text" class="form-control"  name="kpp" placeholder="пример: 771401001"  @isset($organization->kpp) value="{{$organization->kpp}}" @endisset>
</div>

<div class="mb-3">
  <label  class="form-label">Руководитель организации</label>
  <input type="text" class="form-control"  name="director" placeholder="пример: Федор Павлович Шмидт" @isset($organization->director) value="{{$organization->director}}" @endisset>
</div>
<div class="mb-3">
  <label  class="form-label">Должность руководителя</label>
  <input type="text" class="form-control"  name="director_post" placeholder="пример: Генеральный директор" @isset($organization->director_post) value="{{$organization->director_post}}" @endisset>
</div>
<div class="mb-3">
  <label  class="form-label">Главный бухгалтер</label>
  <input type="text" class="form-control"  name="accountant" placeholder="пример: Валерий Васильевич Учетов"  @isset($organization->accountant) value="{{$organization->accountant}}" @endisset>
</div>


<div class="mb-3">
  <label  class="form-label">Юридический адрес</label>
  <input type="text" class="form-control" id="form-legal-address"  name="legal_address" placeholder="пример: ул. Труда  8 - 88 " @isset($organization->legal_address) value="{{$organization->legal_address}}" @endisset>
  <div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="identical_addresses" >
  <label class="form-check-label" for="flexCheckChecked">
    Совпадает с фактическим
  </label>
</div>
</div>
<div class="mb-3">
  <label  class="form-label">Фактический адрес</label>
  <input type="text" class="form-control"  id="form-actual-address" name="actual_address" placeholder="пример: ул. Труда  7 - 77" @isset($organization->actual_address) value="{{$organization->actual_address}}" @endisset>
</div>



<div class="col-12">
    <button type="submit" class="btn btn-primary">сохранить</button>
  </div>

</form>
</div>








<div class="container">
<form id="profile-requisites-form">
  @csrf
<input type="hidden" id="profile-form-requisites-url" name="handler_url" value = '{{ route("users.profile.save", $user->id) }}'>
<input type="hidden"  name="req_id" @isset($requisites->id)  value='{{$requisites->id}}' @endisset>

<div class="col mt-5"><h2>Реквизиты</h2></div>
<div class="mb-3">
  <label  class="form-label">Расчетный счёт</label>
  <input type="text" class="form-control"  name="payment_account" placeholder="40817810099910004312" @isset($requisites->payment_account) value="{{$requisites->payment_account}}" @endisset>
</div>
<div class="mb-3">
  <label  class="form-label">Корреспондентский счёт</label>
  <input type="text" class="form-control" name="correspondent_account" placeholder="40817810077710003542" @isset($requisites->correspondent_account) value="{{$requisites->correspondent_account}}" @endisset>
</div>
<div class="mb-3">
  <label  class="form-label">БИК</label>
  <input type="text" class="form-control"  name="bik" placeholder="044525974" @isset($requisites->bik) value="{{$requisites->bik}}" @endisset>
</div>
<div class="mb-3">
  <label  class="form-label">Банк</label>
  <input type="text" class="form-control"  name="bank" placeholder="Российский Банк" @isset($requisites->bank) value="{{$requisites->bank}}" @endisset>
</div>

<div class="col-12">
    <button type="submit" class="btn btn-primary">сохранить</button>
  </div>
  </form>


</div>
	
	
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

 @vite(['resources/js/userProfile.js'])
 <!--  
<script src="{{ asset('resources/js/userProfile.js')}}"></script>	
-->	
@endsection
