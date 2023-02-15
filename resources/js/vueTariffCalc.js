import { createApp } from "vue";


 createApp({
  data(){
      return{
    amount: 1000000,
	annualRate: 1,
	period:1,
	tariffTitle: 'Выбирете Тариф',
	}
  },
  methods: {
  
	setCalcData: function(annualRate, period, title){
		this.annualRate = annualRate;
		this.period = period;
		this.tariffTitle = title;
	},
	setAnnualRate: function(value){
		this.annualRate = value;
	},
  },
  computed: {
    profitabilityPerMounthRateProc(){
     return Number((((this.annualRate/12))).toFixed(2)) 
	},	
    profitabilityPerMounth() {

      return Number((this.amount * ((this.annualRate/12)/100)).toFixed(0))
    },
	
	
	plusToAmountPerYear(){
	  return Number((this.amount * ((this.annualRate)/100)).toFixed(0))
	},
	totalPerPeriod(){
		return Number((this.amount+this.profitabilityPerMounth*this.period).toFixed(2));
	},
	totalPerYear(){
		return Number((this.amount+this.plusToAmountPerYear).toFixed(2));
	},
	profitabilityPerPeriod(){
	 return Number((this.totalPerPeriod / this.amount).toFixed(2))    	
	},
  }
  
  
  
}).mount('#tariffs')
	
