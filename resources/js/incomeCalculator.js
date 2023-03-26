import { createApp } from "vue";
import './bootstrap';
import './helpers';
import IMask from 'imask';

import.meta.glob(["../images/**", "../fonts/**"]);


const app_calc = createApp({
   data() {
        return {
			calcinput: 1000000, 
			isNumber: false,
        }
    },	
watch: {
   'calcinput': {
        handler: function(val) {
	 if (isNaN(val)) {
        this.isNumber = true;
        
      }
      else{
      this.isNumber = false;
      }
      
      return true;
        },
    }
},
      
    methods :{
	 checkInput: function (e) {
      if (this.calcinput.isFinite()) {
        return true;
      }
      
      alert('Укажите число');
      this.calcinput = 0;
      }
}
	
	
});


app_calc.component('tariff-card', {
 data() {
    return {
    }
  },
	props: {
  duration: '',
  amountstr:'' ,
  amount:1000000,
  annualrate: '',


},
computed: {
	  annualRateMonth() {
	
	    return (this.annualrate/12).toFixed(2)
	  },
	    profitabilityPerMounthRateProc(){
     return Number((((this.annualrate/12))).toFixed(2)) 
	},	
    profitabilityPerMounth() {

      return Number((this.amount * ((this.annualrate/12)/100)).toFixed(0))
    },
	
	
	plusToAmountPerYear(){
	  return Number((this.amount * ((this.annualrate)/100)).toFixed(0))
	},
	totalPerPeriod(){
		return Number((this.amount + this.profitabilityPerMounth*this.duration).toFixed(2));
	},
	totalPerYear(){
		
		return Number((this.amount + this.plusToAmountPerYear).toFixed(2));
	},
	profitabilityPerPeriod(){
	 return Number((this.totalPerPeriod / this.amount).toFixed(2))    	
	},
  },

  template: `  <table class="table">  
        <tr>
          <td>Количество месяцев</td>
          <td>{{ duration }}</td>
        </tr>
        <tr>
    
          <td>Сумма</td>
          <td>{{ amountstr }}</td>
        </tr>
        <tr>
          <td>Доходность ИП<br>
          (% ставка в год)
          </td>
          <td>{{ annualrate }}%</td>
        </tr>
        
        <tr>
          <td>Доходность ИП<br>
          (% ставка в мес)
          </td>
          <td>{{ annualRateMonth }}%</td>
        </tr>
        <tr>
	        <td>
	        "+" (к сумме<br> инвестиций)за 1 год
	        </td>
	        <td>
	        {{ plusToAmountPerYear}} ₽
	        </td>
        </tr>
          <tr>
	        <td>
	        Total ИП за 1 год
	        </td>
	        <td>
	        {{ totalPerYear }} ₽
	        </td>
        </tr>
        <tr>
	        <td>
	        Total ИП за период
	        </td>
	        <td>
	        {{ totalPerPeriod }} ₽
	        </td>
        </tr>
        <tr>
	        <td>
        Доходность ИП<br> (ROI / х) за период
            </td>
	        <td>
	        {{ profitabilityPerPeriod }}
	        </td>
        </tr>
        
    </table>` 
    })

app_calc.mount("#income_calculator");
