jQuery(document).ready(function() {
	

});

function submitGoal() {
	if (typeof web3 !== 'undefined') {
    	web3 = new Web3(web3.currentProvider);
	} else {
    	// set the provider you want from Web3.providers
		web3 = new Web3(new Web3.providers.HttpProvider("http://127.0.0.1:8545"));
	}
	
	contractspec = web3.eth.contract([{"constant":false,"inputs":[{"name":"commitmentDesc","type":"string"}],"name":"addGoal","outputs":[],"payable":true,"stateMutability":"payable","type":"function"},{"constant":true,"inputs":[{"name":"","type":"address"}],"name":"commitments","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"}]);
	
	var contract = contractspec.at("0x38aa4452dc3c457e44fc3840d19a5c2c8f19ce0e");
	
	web3.eth.getCoinbase(function(error, result) {
		contract.addGoal(jQuery('#newgoal').val(),{
			from: result
		},function(error,txHash) { jQuery("#formSubmit").submit();});
	});
	
}