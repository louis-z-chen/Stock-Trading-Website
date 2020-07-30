// Alpha Vantage API key = TSFIH79KDCL3ZILA
//IEX Cloud API Key = pk_407f1361e96a4e0f90122a14b6aebab7

function ajax(endpoint,returnFunction){
	let xhr = new XMLHttpRequest();
	xhr.open("GET", endpoint);
	xhr.send();
	xhr.onreadystatechange = function(){
		if(this.readyState == this.DONE){
			if(xhr.status == 200){
				returnFunction(xhr.responseText);
			}
			else{
				alert("AJAX error");
			}
		}
	}
}

function display_results(result_object){
	result_object = JSON.parse(result_object);

	//let results = document.querySelector("stock-columns");
	var table = document.querySelector("#stock-table tbody");

	while(table.hasChildNodes()){
		table.removeChild(table.lastChild);
	}

	//let totalRowCount = result_object.results.length;
	//console.log(totalRowCount);

	Object.values(result_object).forEach(function(res) {
		//symbol
		let symbol = document.createElement("td");
		symbol.innerHTML=res.quote.symbol;

		//company
		let company = document.createElement("td");
		company.innerHTML=res.quote.companyName;

		//price
		let price = document.createElement("td");
		price.innerHTML=res.quote.latestPrice;

		//change
		let change = document.createElement("td");
		change.innerHTML=res.quote.change;
		if(res.quote.change > 0){
			change.style.color="green";
		}
		else if(res.quote.change < 0){
			change.style.color="red";
		}
		else{
			change.style.color="black";
		}

		//%change
		let percentchange = document.createElement("td");
		let x = 100;
		let y = res.quote.changePercent;
		let temp = x * y; 
		let temp2 = temp.toFixed(2);
		percentchange.innerHTML=temp2;

		if(res.quote.changePercent > 0){
			percentchange.style.color="green";
		}
		else if(res.quote.changePercent < 0){
			percentchange.style.color="red";
		}
		else{
			percentchange.style.color="black";
		}

		//exchange
		let open = document.createElement("td");
		open.innerHTML=res.quote.primaryExchange;

		//high
		let high = document.createElement("td");
		high.innerHTML=res.quote.week52High;

		//low
		let low = document.createElement("td");
		low.innerHTML=res.quote.week52Low;

		//add button
		let buybutton = document.createElement("td");
		let form = document.createElement("form");
		form.setAttribute('method',"POST");
		form.setAttribute('action',"dashboard.php");
		form.setAttribute('onsubmit',"return checkQuantity(this)");
		form.setAttribute('name',"quantity_form");

			let input = document.createElement('input');
			input.setAttribute('id','quantity-input');
			input.setAttribute('min','1');
			input.setAttribute('type','number');
			input.setAttribute('placeholder',' Quantity');
			input.setAttribute('name','quantity');
			input.style.width = "100px";
			form.appendChild(input);

		//hidden inputs
			//current date and time
			//let curr_datetime = new Date(); 
			let curr_datetime = new Date().toJSON().slice(0, 19).replace('T', ' ')

			let hidden_datetime = document.createElement('input');
			hidden_datetime.setAttribute('name','hidden_datetime')
			//hidden_datetime.setAttribute('type','text');
			hidden_datetime.setAttribute('type','hidden');
			hidden_datetime.setAttribute('value',curr_datetime);
			form.appendChild(hidden_datetime);

			//symbol
			let curr_symbol = res.quote.symbol;

			let hidden_symbol = document.createElement('input');
			hidden_symbol.setAttribute('name','hidden_symbol')
			//hidden_symbol.setAttribute('type','text');
			hidden_symbol.setAttribute('type','hidden');
			hidden_symbol.setAttribute('value',curr_symbol);
			form.appendChild(hidden_symbol);

			//company name
			let curr_company = res.quote.companyName;

			let hidden_company = document.createElement('input');
			hidden_company.setAttribute('name','hidden_company')
			//hidden_company.setAttribute('type','text');
			hidden_company.setAttribute('type','hidden');
			hidden_company.setAttribute('value',curr_company);
			form.appendChild(hidden_company);

			//price
			let curr_price = res.quote.latestPrice;

			let hidden_price = document.createElement('input');
			hidden_price.setAttribute('name','hidden_price')
			//hidden_price.setAttribute('type','text');
			hidden_price.setAttribute('type','hidden');
			hidden_price.setAttribute('value',curr_price);
			form.appendChild(hidden_price);

			//type (stock/cypto)
			let curr_type = 0;

			let hidden_type = document.createElement('input');
			hidden_type.setAttribute('name','hidden_type')
			//hidden_type.setAttribute('type','text');
			hidden_type.setAttribute('type','hidden');
			hidden_type.setAttribute('value',curr_type);
			form.appendChild(hidden_type);

			//user id
			let hidden_id = document.createElement('input');
			hidden_id.setAttribute('name','hidden_id')
			hidden_id.setAttribute('type','hidden');
			hidden_id.setAttribute('value',user_id);
			form.appendChild(hidden_id);

		let submit = document.createElement('input');
		submit.setAttribute('type','submit');
		submit.setAttribute('value','Buy');
		submit.classList.add("buybutton");
		submit.classList.add("btn");
		submit.classList.add("btn-outline-success");

		form.appendChild(submit);
		buybutton.appendChild(form);

		//create tr object
		let row = document.createElement("tr");
		row.appendChild(symbol);
		row.appendChild(company);
		row.appendChild(price);
		row.appendChild(change);
		row.appendChild(percentchange);
		row.appendChild(high);
		row.appendChild(low);
		//row.appendChild(volume);
		row.appendChild(open);
		console.log(buybutton);
		row.appendChild(buybutton);

		//add to table
		table.appendChild(row);
	});

}

document.querySelector("body").onload = function(event){
	//let endpoint = "https://api.iextrading.com/1.0/tops/last?symbols=SNAP,fb,AIG%2b";
	//let endpoint = "https://cloud.iexapis.com/stable/stock/aapl/quote?token=pk_407f1361e96a4e0f90122a14b6aebab7";
	//let endpoint = "https://cloud.iexapis.com/v1/stock/market/batch?&types=quote&symbols=aapl,fb&token=pk_407f1361e96a4e0f90122a14b6aebab7";
	let endpoint = "https://cloud.iexapis.com/v1/stock/market/batch?&types=quote&symbols=acb,f,ge,fit,gpro,msft,aapl,dis,cron,cgc,snap,amd,plug,fb,tsla,twtr,znga,amzn,baba,uber,chk,bac,nio,nflx,t,sbux,apha,nvda,grpn,sq,s,siri,ko,voo,bynd,crbp,work,nke,atvi,lyft,mu,v,vslr,intc,nok,pcg,csco,spy,jcp,tlry,pypl,mj,tcehy,gluu,brk.b,nrz,auy,iq,roku,crm,wmt,ba,gern,dnr,vktx,vti,pfe,enph,gm,googl,twlo,shop,jd,cprx,dbx,vz,cost,lk,pins,sne,bili,cara,aks,crsp,abbv,cvs,spwr,llnw,yeti,tgt,ugaz,teva,mcd,botz,uaa,sfix,pton,zm,jnj,sphd&token=pk_407f1361e96a4e0f90122a14b6aebab7";

    ajax(endpoint, display_results);
}


