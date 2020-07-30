const $total_profits = $("#total_profits")
const $transactions = $(".transaction")
const number_of_transactions = $transactions.length

let loaded_transactions = 0
let profits = 0

$transactions.each(function() {
	const symbol = $(this).data("symbol");
	const price = $(this).data("price");
	const quantity = $(this).data("quantity");

	const $current_price_col = $(this).find(".current_price")

	const $price_col = $(this).find(".price")
	const $change_col = $(this).find(".change")
	const $pchange_col = $(this).find(".pchange")
	const $profit_col = $(this).find(".profit")


	$.ajax({
		method: "GET",
		url: "https://cloud.iexapis.com/v1/stock/market/batch",
		data: {
			types: "quote",
			symbols: symbol,
			token: "pk_407f1361e96a4e0f90122a14b6aebab7"
		}
	})
	.done(function( response ) {
		response = response[symbol];
		$current_price_col.html(response.quote.latestPrice)


		let latestPrice = response.quote.latestPrice
		let change = latestPrice - price
		let pchange = (change / price) * 100
		let profit = change * quantity

		profits += profit

		latestPrice = latestPrice.toFixed(3)
		change = change.toFixed(2)
		pchange = pchange.toFixed(2)
		profit = profit.toFixed(2)

		if(change < 0){
			$change_col.css("color", "red")
		}
		else if(change > 0){
			$change_col.css("color","green")
		}

		if(pchange < 0){
			$pchange_col.css("color", "red")
		}
		else if(pchange > 0){
			$pchange_col.css("color","green")
		}

		if(profit < 0){
			$profit_col.css("color", "red")
		}
		else if(profit > 0){
			$profit_col.css("color","green")
		}

		$change_col.html(change)
		$pchange_col.html(pchange)
		$profit_col.html(profit)

		loaded_transactions++;
			//$total_profits.html(profits.toFixed(2))

		if(loaded_transactions == number_of_transactions) {
			$total_profits.html(profits.toFixed(2))
		}
	});
})