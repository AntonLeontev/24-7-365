@extends('layouts.app')


@section('title', 'Калькулятор доходов')


@section('content')

<div id="income_calculator">

<div class="container-fluid ">
    <div class="row">
    <x-common.h1 class="mb-13">Калькулятор ваших доходов</x-common.h1>
    
    
         
    </div>
    <div class="row income-calculator-bar">
           <div class="col-lg-5 col-md-8">
                    <div class="card-header">Калькулятор</div>
                    <p>Введите желаемую сумму закупа и мы покажем примерную прибыль по всем тарифам</p>
                </div>
                
                <div class="col-lg-7 col-md-8 income-calculator-input-div">
                    <div class="form-input">
                        <input type="text" class="form-control" name="calc_amount" placeholder="1 000 000 000" id="calcinput" v-model.number="calcinput" autocorrect="off" autocomplete="off" autocapitalize="off">
                        <label class="form-label"> Укажите сумму закупа </label>
                        <label class="form-label text-primary" v-if="isNumber" >ВВЕДИТЕ ЧИСЛО</label>
                    </div>
                </div>
        
    </div>
    


</div>



@include('tariffs.part.tariffs_list',['tariffs'=>$tariffs])



</div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"
        integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>



    @vite(['resources/js/incomeCalculator.js'])

    <!--
    <blade ___scripts_2___/>
    -->
@endsection
