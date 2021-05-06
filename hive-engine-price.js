// hive-engine-price.js

const rpc = 'https://api.hive-engine.com/rpc/contracts';

function getSellBook(token) {
	return new Promise((resolve, reject) => {
		const query = {'id': 1,
					   jsonrpc: '2.0',
					   method: 'find',
					   params: {
						contract: 'market',
						table: 'sellBook',
						query: {
							symbol: token
						}
					}}
		let nextBlock = false
		axios.post(rpc, query).then((result) => {
			return resolve(result.data.result)
		}).catch((err) => {
			console.log(err)
			return reject(err)
		})
	})
}

function getHivePrice() {
	return new Promise((resolve, reject) => {
		const coingecko_api = 'https://api.coingecko.com/api/v3/simple/price?ids=hive&vs_currencies=USD'
		axios.get(coingecko_api).then((result) => {
			return resolve(result.data.hive.usd)
		}).catch((err) => {
			console.log(err)
			return reject(err)
		})
	})
}

function getTokenPrice(token, div) {
	var hivePrice = getHivePrice()
	var sellBook = getSellBook(token)

	Promise.all([hivePrice,sellBook]).then((result) => {
		let hivePrice = result[0]

		let sellBook = result[1]
		sellBook = sellBook.sort( (a,b) => Number(a.price) - Number(b.price) )

		var targetDiv = div

		let sellOrder = sellBook[0]

		let quantity = sellOrder.quantity
		let symbol = sellOrder.symbol
		let price = sellOrder.price
		targetDiv.innerHTML = (`${Number(price).toFixed(3)} HIVE <br> ${(price * hivePrice).toFixed(3)} USD`)
	})
}

Array.from(document.querySelectorAll('div.hep_widget_content')).forEach( (div) => {
	let token_name = div.getAttribute('data-token-name')
	let div_id = div.getAttribute('id')
	getTokenPrice(token_name.toUpperCase(), div)
})


